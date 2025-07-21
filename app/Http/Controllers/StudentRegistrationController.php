<?php

namespace App\Http\Controllers;
use App\Models\reg_stu_information;
use App\Models\reg_stu_information_log;
use App\Models\MarkazAgreementMadrasa;
use Illuminate\Support\Facades\Storage;

use App\Models\student;
use App\Models\User;
use Illuminate\Http\Request;
use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\admin\marhala_for_admin\ExamFee;
use App\Models\MarkazAgreement;
use Illuminate\Support\Facades\DB;

class StudentRegistrationController extends Controller
{


    public function editOldStudent($marhalaId, $roll, $reg_id)
    {
        // Find the student from students table
        $student = Student::where('Roll', $roll)
            ->where('reg_id', $reg_id)
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'ছাত্র খুঁজে পাওয়া যায়নি');
        }

        // Check if student already has data in reg_stu_informations table
        $studentInfo = reg_stu_information::where('roll', $roll)
            ->where('reg_id', $reg_id)
            ->first();

        // Create translator instance for displaying Bengali data
        $translator = new Translator();

        // Translate the student data
        $student->Name = $translator->translate($student->Name);
        $student->Father = $translator->translate($student->Father);
        $student->Mother = $translator->translate($student->Mother ?? '');
        $student->DateofBirth = $translator->translate($student->DateofBirth);

        return Inertia::render('StudentRegistration/old_stu_reg_Form', [
            'student' => $student,
            'studentInfo' => $studentInfo,
            'marhalaId' => $marhalaId ?? null
        ]);
    }






    public function updateStudentInfo(Request $request)
    {
        $validated = $request->validate([
            'roll' => 'required',
            'reg_id' => 'required',
            'name_bn' => 'required',
            'name_en' => 'nullable',
            'name_ar' => 'nullable',
            'father_name_bn' => 'required',
            'father_name_en' => 'nullable',
            'father_name_ar' => 'nullable',
            'mother_name_bn' => 'nullable',
            'mother_name_en' => 'nullable',
            'mother_name_ar' => 'nullable',
            'BRN_no' => 'nullable',
            'NID_no' => 'nullable',
        ]);

        // Update or create record in reg_stu_informations table
        reg_stu_information::updateOrCreate(
            ['roll' => $validated['roll'], 'reg_id' => $validated['reg_id']],
            $validated
        );

        // Update mother's name in students table if provided
        if (!empty($validated['mother_name_bn'])) {
            Student::where('Roll', $validated['roll'])
                ->where('reg_id', $validated['reg_id'])
                ->update(['Mother' => $validated['mother_name_bn']]);
        }

        return redirect()->back()->with('success', 'ছাত্রের তথ্য সফলভাবে আপডেট করা হয়েছে');
    }




















    public function getStudents()
    {
        $students = reg_stu_information::where('user_id', Auth::id())->select(
            'id',
            'student_image',
            'name_bn',
            'father_name_bn',
            'current_madrasha',
            'current_class',
            'Date_of_birth',
            'student_type',
            'exam_name_Bn'
        )->get();

        // প্রতিটি ছাত্রের জন্য status এবং payment_status যোগ করা
        foreach ($students as $student) {
            // রেজিস্ট্রেশন স্ট্যাটাস
            $log = reg_stu_information_log::where('reg_student_id', $student->id)
                ->latest()
                ->first();
            $student->status = $log ? $log->status : null;

            // পেমেন্ট স্ট্যাটাস
            $payment = DB::table('student_reg_payments')
                ->where('students_id', $student->id)
                ->latest()
                ->first();

            $student->payment_status = $payment && $payment->payment_status == 1 ? 'পরিশোধিত' : 'অপরিশোধিত';
            $student->is_paid = $payment && $payment->payment_status == 1;
        }

        return response()->json($students);
    }






public function reg_submitApplication($id)
{
    // 1️⃣ First check if registration exists
    $registration = reg_stu_information::find($id);
    if (!$registration) {
        return back()->withErrors(['error' => 'আবেদন পাওয়া যায়নি!']);
    }

    // 2️⃣ Now check schedule timing before anything else
    $schedule = ExamFee::where('exam_setup_id', $registration->exam_id)
        ->first();

    if (!$schedule) {
        return back()->withErrors(['error' => 'সময়সূচী পাওয়া যায়নি!']);
    }

    // 3️⃣ Check if within submission period
    $currentDate = now();
    $startDate = \Carbon\Carbon::parse($schedule->reg_date_from);
    $endDate = \Carbon\Carbon::parse($schedule->reg_date_to);

    if ($currentDate->lt($startDate)) {
        return back()->withErrors([
            'error' => 'আবেদনের সময় শুরু হয়নি! ' . $startDate->format('d-m-Y') . ' তারিখে আবার চেষ্টা করুন।'
        ]);
    }

    if ($currentDate->gt($endDate)) {
        return back()->withErrors(['error' => 'আবেদনের সময় শেষ! আগামী বছর আবার চেষ্টা করুন।']);
    }

    // 4️⃣ Check if application already submitted
    $existingLog = reg_stu_information_log::where('reg_student_id', $registration->id)
        ->where('status', 'submitted')
        ->exists();

    if ($existingLog) {
        return back()->with('info', 'এই আবেদনটি ইতিমধ্যে বোর্ডে দাখিল করা হয়েছে।');
    }

    // 5️⃣ If all checks pass, then create activity log
    reg_stu_information_log::create([
        'reg_student_id' => $registration->id,
        'actor_id' => Auth::id(),  // ইউজারের আইডি যোগ করা হয়েছে
        'actor_type' => 'user',    // কোটেশন মার্ক সহ
        'status' => 'submitted',
        'message' => null,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return back()->with('success', 'আবেদন সফলভাবে সাবমিট হয়েছে!');
}









public function submitAllApplications(){
    // Get all applications for the current user (without status condition)
    $registrations = reg_stu_information::where('user_id', Auth::id())->get();

    if ($registrations->isEmpty()) {
        return back()->withErrors(['error' => 'কোন আবেদন পাওয়া যায়নি!']);
    }

    // Get the schedule information
    $firstRegistration = $registrations->first();
    $schedule = ExamFee::where('exam_setup_id', $firstRegistration->exam_id)
        ->first();

    if (!$schedule) {
        return back()->withErrors(['error' => 'সময়সূচী পাওয়া যায়নি!']);
    }

    // Check if within submission period
    $currentDate = now();
    $startDate = \Carbon\Carbon::parse($schedule->reg_date_from);
    $endDate = \Carbon\Carbon::parse($schedule->reg_date_to);

    if ($currentDate->lt($startDate)) {
        return back()->withErrors(['error' => 'আবেদনের সময় শুরু হয়নি! ' . $startDate->format('d-m-Y') . ' তারিখে আবার চেষ্টা করুন।']);
    }

    if ($currentDate->gt($endDate)) {
        return back()->withErrors(['error' => 'আবেদনের সময় শেষ! আগামী বছর আবার চেষ্টা করুন।']);
    }

    // Process all applications
    $successCount = 0;
    $alreadySubmittedCount = 0;
    $user = Auth::user();

    foreach ($registrations as $registration) {
        // Get the latest log entry for this registration
        $latestLog = reg_stu_information_log::where('reg_student_id', $registration->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Check if can submit:
        // 1. No previous log exists (first time submission)
        // 2. Latest status is 'returned' (admin returned it for correction)
        $canSubmit = !$latestLog || $latestLog->status === 'returned';

        if ($canSubmit) {
            // Create activity log for each application
            reg_stu_information_log::create([
                'reg_student_id' => $registration->id,
                'actor_id' => Auth::id(),
                'actor_type' => 'user',
                'status' => 'submitted',
                'message' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $successCount++;
        } else {
            // Count applications that are already submitted and not returned
            $alreadySubmittedCount++;
        }
    }

    // Return appropriate message
    if ($successCount > 0 && $alreadySubmittedCount > 0) {
        return back()->with('success', $successCount . ' টি আবেদন সফলভাবে বোর্ড দাখিল করা হয়েছে! ' . $alreadySubmittedCount . ' টি আবেদন ইতিমধ্যে দাখিল করা ছিল।');
    } elseif ($successCount > 0) {
        return back()->with('success', $successCount . ' টি আবেদন সফলভাবে বোর্ড দাখিল করা হয়েছে!');
    } else {
        return back()->with('info', 'সকল আবেদন ইতিমধ্যে বোর্ডে দাখিল করা হয়েছে।');
    }
}







    // এডমিন পার্ট












    public function getMarkazStudents()
    {
        $self = $this;

        $students = reg_stu_information::select(
            'reg_stu_informations.markaz_id as id',
            'madrasha.MName as madrasha_Name',
            'madrasha.ElhaqNo as Elhaq_no',
            'madrasha.Mobile as Mobile',
            'madrasha.DID',
            'madrasha.DISID',
            'madrasha.TID',
            'division.Division as division_name',
            'district.District as district_name',
            'thana.Thana as thana_name',
            'reg_stu_informations.exam_name_Bn'
        )
        ->selectRaw('COUNT(reg_stu_informations.id) as student_count')
        ->leftJoin('madrasha', 'reg_stu_informations.markaz_id', '=', 'madrasha.id')
        ->leftJoin('division', 'madrasha.DID', '=', 'division.id')
        ->leftJoin('district', 'madrasha.DISID', '=', 'district.DesID')
        ->leftJoin('thana', 'madrasha.TID', '=', 'thana.Thana_ID')
        ->whereNotNull('reg_stu_informations.markaz_id')
        ->groupBy(
            'reg_stu_informations.markaz_id',
            'reg_stu_informations.exam_name_Bn',
            'madrasha.MName',
            'madrasha.ElhaqNo',
            'madrasha.Mobile',
            'madrasha.DID',
            'madrasha.DISID',
            'madrasha.TID',
            'division.Division',
            'district.District',
            'thana.Thana'
        )
        ->havingRaw('COUNT(reg_stu_informations.id) > 0')
        ->get()
        ->map(function ($item) use ($self) {
            return [
                'id' => $item->id,
                'madrasha_Name' => $item->madrasha_Name,
                'Elhaq_no' => $item->Elhaq_no,
                'Mobile' => $item->Mobile,
                'DID' => $item->DID,
                'DISID' => $item->DISID,
                'TID' => $item->TID,
                'division_name' => $item->division_name,
                'district_name' => $item->district_name,
                'thana_name' => $item->thana_name,
                'exam_name_Bn' => $item->exam_name_Bn,
                'student_count' => $item->student_count,
                'madrasha_code' => $self->getMadrashaCode($item->id)
            ];
        });

        return response()->json($students);
    }








    // পাবলিক মেথড হিসেবে ডিফাইন করা
    public function getMadrashaCode($markazId)
    {
        $user = User::whereHas('regStuInformation', function ($query) use ($markazId) {
            $query->where('markaz_id', $markazId);
        })
        ->select('custom_code')
        ->first();

        return $user ? $user->custom_code : null;
    }

























public function getMarkazMadrasaList($markaz_id)
{
    // Get all MRID values from stu_rledger_p table where MDID matches the markaz ID
    $mridValues = DB::table('stu_rledger_p')
        ->where('MDID', $markaz_id)
        ->pluck('MRID')
        ->unique()
        ->toArray();

    // Get madrashas where id matches any of the MRID values
    $madrashaList = DB::table('madrasha')
        ->whereIn('id', $mridValues)
        ->select('id', 'MName as name', 'ElhaqNo as Elhaq_no', 'Mobile as mobile_no')
        ->get();

    // Get student count for each madrasha
    foreach ($madrashaList as $madrasha) {
        $madrasha->student_count = DB::table('reg_stu_informations')
            ->where('madrasha_id', $madrasha->id)
            ->count();
    }

    // Get the markaz name
    $markazName = DB::table('madrasha')
        ->where('id', $markaz_id)
        ->value('MName');

    // Get total student count for this markaz
    $totalStudents = DB::table('reg_stu_informations')
        ->whereIn('madrasha_id', $mridValues)
        ->count();

    return response()->json([
        'madrashaList' => $madrashaList,
        'markazName' => $markazName,
        'totalStudents' => $totalStudents
    ]);
}















    public function abandonStuList($markaz_id)
    {
        // Just render the view, data will be fetched on the client side
        return Inertia::render('nibondon_for_admin/abandon_stu_list', [
            'markaz_id' => $markaz_id
        ]);
    }




    public function madrashaWariStuNibondList($madrasha_id = null)
    {
        // মাদরাসা আইডি অনুসারে ছাত্রদের তালিকা পাওয়া
        $students = [];

        if ($madrasha_id) {
            // Eloquent ব্যবহার করে ছাত্রদের তথ্য সংগ্রহ করা
            $students = reg_stu_information::with(['latestLog' => function($query) {
                    $query->latest();
                }])
                ->where('madrasha_id', $madrasha_id)
                ->get()
                ->map(function($student) {
                    return [
                        'id' => $student->id,
                        'name_bn' => $student->name_bn,
                        'Date_of_birth' => $student->Date_of_birth,
                        'father_name_bn' => $student->father_name_bn,
                        'mother_name_bn' => $student->mother_name_bn,
                        'current_madrasha' => $student->current_madrasha,
                        'current_class' => $student->current_class,
                        'student_image' => $student->student_image,
                        'status' => $student->latestLog ? $student->latestLog->status : null,
                        'status_date' => $student->latestLog ? $student->latestLog->created_at : null
                    ];
                });
        }

        return Inertia::render('nibondon_for_admin/madrashaWari_stu_nibond_list', [
            'students' => $students,
            'madrasha_id' => $madrasha_id
        ]);
    }










    public function editStudentRegistration($id)
    {
        // Find the student record
        $student = reg_stu_information::findOrFail($id);

        // Get marhalas for dropdown
        $marhalas = DB::table('marhalas')->get();

        // Return Inertia response to render Vue component
        return Inertia::render('students_registration/student_registration_edit', [
            'student' => $student,
            'marhalas' => $marhalas
        ]);
    }

    public function updateStudentRegistration(Request $request, $id)
    {
        $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'father_name_bn' => 'required|string|max:255',
            'father_name_en' => 'nullable|string|max:255',
            'father_name_ar' => 'nullable|string|max:255',
            'mother_name_bn' => 'nullable|string|max:255',
            'mother_name_en' => 'nullable|string|max:255',
            'mother_name_ar' => 'nullable|string|max:255',
            'BRN_no' => 'nullable|string|max:255',
            'NID_no' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:255',

        ]);

        // Find the student record
        $student = reg_stu_information::findOrFail($id);

        // Handle file uploads
        if ($request->hasFile('student_image')) {
            // Delete old image if exists
            if ($student->student_image) {
                Storage::disk('public')->delete($student->student_image);
            }
            $studentImagePath = $request->file('student_image')->store('student_images', 'public');
            $student->student_image = $studentImagePath;
        }

        if ($request->hasFile('NID_attach')) {
            // Delete old attachment if exists
            if ($student->NID_attach) {
                Storage::disk('public')->delete($student->NID_attach);
            }
            $nidAttachmentPath = $request->file('NID_attach')->store('nid_attachments', 'public');
            $student->NID_attach = $nidAttachmentPath;
        }

        // Get marhala information
        $marhala = null;
        if ($request->marhala_id) {
            $marhala = DB::table('marhalas')
                ->select('id', 'marhala_name_bn')
                ->where('id', $request->marhala_id)
                ->first();
        }

        // Update student information
        $student->name_bn = $request->name_bn;
        $student->name_en = $request->name_en;
        $student->name_ar = $request->name_ar;
        $student->father_name_bn = $request->father_name_bn;
        $student->father_name_en = $request->father_name_en;
        $student->father_name_ar = $request->father_name_ar;
        $student->mother_name_bn = $request->mother_name_bn;
        $student->mother_name_en = $request->mother_name_en;
        $student->mother_name_ar = $request->mother_name_ar;
        $student->board_name = $request->board_name;

        $student->present_division_name = $request->present_division_name;
        $student->present_district_name = $request->present_district_name;
        $student->present_thana_name = $request->present_thana_name;
        $student->presernt_DID = $request->presernt_DID;
        $student->present_desId = $request->present_desId;
        $student->present_TID = $request->present_TID;

        $student->parmanent_division_name = $request->parmanent_division_name;
        $student->parmanent_district_name = $request->parmanent_district_name;
        $student->parmanent_thana_name = $request->parmanent_thana_name;
        $student->parmanent_DID = $request->parmanent_DID;
        $student->parmanent_desId = $request->parmanent_desId;
        $student->parmanent_TID = $request->parmanent_TID;

        $student->BRN_no = $request->BRN_no;
        $student->NID_no = $request->NID_no;
        $student->mobile_no = $request->mobile_no;
        $student->Date_of_birth = $request->Date_of_birth;
        $student->student_type = $request->student_type ?? 'নিয়মিত';

        // Update marhala/class information if provided
        if ($marhala) {
            $student->current_class = $marhala->marhala_name_bn;
            $student->marhala_id = $marhala->id;
        }

        // Update address information


        $student->save();

        return redirect()->back()
            ->with('success', 'Student information updated successfully');

    }




// for madrasa view

   public function studentRegistrationView($id)
{
    // reg_stu_informations টেবিল থেকে ডাটা নিয়ে আসা
    $student = reg_stu_information::findOrFail($id);

    // reg_stu_information_logs টেবিল থেকে ডাটা নিয়ে আসা
    // যেখানে reg_student_id ফিল্ড reg_stu_information টেবিলের আইডির সাথে মিলে
    $studentLogs = reg_stu_information_log::where('reg_student_id', $student->id)->get();

    // পুরো ডাটা পাঠানো হচ্ছে
    return inertia('students_registration/student_registraion_view', [
        'student' => $student,
        'studentLogs' => $studentLogs
    ]);
}



// for admin view

public function AdminstudentRegistrationView($id)
{
    // reg_stu_informations টেবিল থেকে ডাটা নিয়ে আসা
    $student = reg_stu_information::findOrFail($id);

    // পুরো ডাটা পাঠানো হচ্ছে
    return inertia('nibondon_for_admin/student_detiles_For_nibondon', [
        'student' => $student
    ]);
}





// for madrahsa delete

    public function studentRegistrationDelete($id)
    {
        $student = reg_stu_information::find($id);

        if (!$student) {
            return response()->json(['error' => 'আবেদন পাওয়া যায়নি!'], 404);
        }

        $student->delete();

        return redirect()->back()->with('success', 'আবেদন সফলভাবে মুছে ফেলা হয়েছে!');
    }





    // for admin student registration aprove

public function StuApproveApplication(Request $request, $id)
{
    // Find the student application
    $student = reg_stu_information::find($id);
    if (!$student) {
        return back()->withErrors(['error' => 'আবেদন পাওয়া যায়নি!']);
    }

    // Get admin information
    $admin = Auth::guard('admin')->user();
    if (!$admin) {
        return back()->withErrors(['error' => 'অ্যাডমিন লগইন করা নেই!']);
    }

    // Check if application already approved
    $existingLog = reg_stu_information_log::where('reg_student_id', $student->id)
        ->where('status', 'approved')
        ->exists();
    if ($existingLog) {
        return back()->with('info', 'এই আবেদনটি ইতিমধ্যে অনুমোদন করা হয়েছে।');
    }

    // Determine actor_type based on admin designation
    $actorType = 'admin'; // Default value

    if ($admin->designation == 1) {
        $actorType = 'সুপার এডমিন';
    } elseif ($admin->designation == 2) {
        $actorType = 'সহ সুপার এডমিন';
    } elseif ($admin->designation == 3) {
        $actorType = 'বোর্ড এডমিন';
    }

    // Create activity log entry
    $logData = [
        'reg_student_id' => $student->id,
        'actor_id' => $admin->id,
         'actor_name' => $admin->name,
        'actor_type' => $actorType,
        'status' => 'approved',
        'message' => $request->message ?? null,
        'created_at' => now(),
        'updated_at' => now()
    ];

    // Insert into logs table
    $inserted = reg_stu_information_log::create($logData);

    if ($inserted) {
        return back()->with('success', 'আবেদন সফলভাবে অনুমোদন করা হয়েছে!');
    }

    return back()->withErrors(['error' => 'আপডেট করতে সমস্যা হয়েছে!']);
}








// admin return student registration


/**
 * Return a student application with feedback
 *
 * @param Request $request
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function studentReturn(Request $request, $id)
{
    // Find the student application
    $student = reg_stu_information::find($id);
    if (!$student) {
        return back()->withErrors(['error' => 'আবেদন পাওয়া যায়নি!']);
    }

    // Get admin information
    $admin = Auth::guard('admin')->user();
    if (!$admin) {
        return back()->withErrors(['error' => 'অ্যাডমিন লগইন করা নেই!']);
    }

    // Validate the request
    $request->validate([
        'message' => 'required|string|max:500',
        // 'image' => 'nullable|image|max:2048', // Uncomment if you need image upload
    ]);


 $existingLog = reg_stu_information_log::where('reg_student_id', $student->id)
        ->where('status', 'approved')
        ->exists();
    if ($existingLog) {
        return back()->with('info', 'এই আবেদনটি ইতিমধ্যে অনুমোদন করা হয়েছে।');
    }

    // Determine actor_type based on admin designation
    $actorType = 'admin'; // Default value

    if ($admin->designation == 1) {
        $actorType = 'সুপার এডমিন';
    } elseif ($admin->designation == 2) {
        $actorType = 'সহ সুপার এডমিন';
    } elseif ($admin->designation == 3) {
        $actorType = 'বোর্ড এডমিন';
    }


    // Initialize feedback data
    $feedbackData = [
       'reg_student_id' => $student->id,
        'actor_id' => $admin->id,
         'actor_name' => $admin->name,
        'actor_type' => $actorType,
        'status' => 'returned',
        'message' => $request->message ?? null,
        'created_at' => now(),
        'updated_at' => now()
    ];

    // Check if an image is uploaded and store it
    // if ($request->hasFile('image')) {
    //     $imagePath = $request->file('image')->store('images/feedback', 'public');
    //     $feedbackData['attachment'] = $imagePath; // Store the image path
    // }

    // Insert the feedback into the logs table
    $inserted = reg_stu_information_log::create($feedbackData);

    // If the insert was successful, return success message
    if ($inserted) {
        // Optionally update the student's status in the main table
        $student->update(['status' => 'returned']);

        return back()->with('success', 'আবেদন সফলভাবে ফেরত পাঠানো হয়েছে!');
    }

    return back()->withErrors(['error' => 'আপডেট করতে সমস্যা হয়েছে!']);
}



// public function studentRejected(Request $request, $id)
// {
//     // Find the student application
//     $student = reg_stu_information::find($id);
//     if (!$student) {
//         return back()->withErrors(['error' => 'আবেদন পাওয়া যায়নি!']);
//     }

//     // Get admin information
//     $admin = Auth::guard('admin')->user();
//     if (!$admin) {
//         return back()->withErrors(['error' => 'অ্যাডমিন লগইন করা নেই!']);
//     }

//     // Validate the request
//     $request->validate([
//         'message' => 'required|string|max:500',
//         // 'image' => 'nullable|image|max:2048', // Uncomment if you need image upload
//     ]);

//     // Initialize feedback data
//     $feedbackData = [
//         'reg_student_id' => $student->id,
//         'actor_id' => $admin->id,
//         'actor_type' => 'admin',
//         'status' => 'rejected',
//         'message' => $request->message,
//         'created_at' => now(),
//         'updated_at' => now()
//     ];

//     // Check if an image is uploaded and store it
//     // if ($request->hasFile('image')) {
//     //     $imagePath = $request->file('image')->store('images/feedback', 'public');
//     //     $feedbackData['attachment'] = $imagePath; // Store the image path
//     // }

//     // Insert the feedback into the logs table
//     $inserted = reg_stu_information_log::create($feedbackData);

//     // If the insert was successful, return success message
//     if ($inserted) {
//         // Optionally update the student's status in the main table
//         $student->update(['status' => 'rejected']);

//         return back()->with('success', 'আবেদন সফলভাবে ফেরত পাঠানো হয়েছে!');
//     }

//     return back()->withErrors(['error' => 'আপডেট করতে সমস্যা হয়েছে!']);
// }




}




