<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\teacher;
use App\Models\negMumtahinAplication;
use App\Models\students;
use App\Models\negran_mumtahin_log;
use App\Models\EducationalQualification;
use App\Models\admin\marhala_for_admin\Marhala;
use App\Models\admin\marhala_for_admin\MarhalaSubject;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class teacherController extends Controller
{




    public function searchTeacher(Request $request)
    {
        $mobile = $request->input('mobile');
        $code = $request->input('code');

        $query = teacher::query();

        if ($mobile) {
            $query->where('mobile', 'like', '%' . $mobile . '%');
        }

        if ($code) {
            $query->where('id', $code);
        }

        // যদি কোন ফিল্টার না দেওয়া হয়, তাহলে খালি অ্যারে রিটার্ন করুন
        if (!$mobile && !$code) {
            return response()->json([]);
        }

        $teachers = $query->get();

        return response()->json($teachers);
    }





    public function getbooks()
    {
        return view('books.getbooks');
    }

    public function getBookCategories()
    {
        $marhalas = Marhala::select('id', 'marhala_name_bn')->get();

        $bookCategories = [];

        foreach ($marhalas as $marhala) {
            $subjects = MarhalaSubject::where('marhala_id', $marhala->id)
                ->select('name_bangla')
                ->get()
                ->pluck('name_bangla')
                ->toArray();

            $bookCategories[] = [
                'id' => $marhala->id,
                'name' => $marhala->marhala_name_bn,
                'books' => $subjects,
                'showSubmenu' => false
            ];
        }

        return response()->json($bookCategories);
    }









  public function techerStore(Request $request)
{
    // Validate the request data
    $validated = $request->validate([
        'teachers.name' => 'nullable|string|max:255',
        'teachers.Mobile' => 'nullable|string|max:255',
        'teachers.DateofBirth' => 'nullable|date',
        'teachers.whatsapp' => 'nullable|string|max:255',
        'teachers.teaching_experience' => 'nullable|string|max:255',
        'teachers.selected_book' => 'nullable|string|max:255',
        'teachers.National_Id_Card' => 'nullable|string|max:255',
        'teachers.nid' => 'nullable|string|max:255',
        'teachers.gender' => 'nullable|string|max:255',
        'profileImage' => 'nullable|image|max:2048',
        'muhtamim_file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',  // নতুন যোগ করা হয়েছে
        'nid_file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',       // নতুন যোগ করা হয়েছে
        'birth_cert_file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png', // নতুন যোগ করা হয়েছে
        'applicationType' => 'required|string',
        'educationalQualifications' => 'nullable|array',
        'educationalQualifications.*.className' => 'nullable|string|max:255',
        'educationalQualifications.*.passYear' => 'nullable|string|max:255',
        'educationalQualifications.*.result' => 'nullable|string|max:255',
        'educationalQualifications.*.institution' => 'nullable|string|max:255',
    ]);

    // Handle profile image upload
    $profileImagePath = null;
    if ($request->hasFile('profileImage')) {
        $profileImagePath = $request->file('profileImage')->store('profile-images', 'public');
    }

    // Handle new file uploads
    $muhtamimFilePath = null;
    if ($request->hasFile('muhtamim_file')) {
        $muhtamimFilePath = $request->file('muhtamim_file')->store('muhtamim-files', 'public');
    }

    $nidFilePath = null;
    if ($request->hasFile('nid_file')) {
        $nidFilePath = $request->file('nid_file')->store('nid-files', 'public');
    }

    $birthCertFilePath = null;
    if ($request->hasFile('birth_cert_file')) {
        $birthCertFilePath = $request->file('birth_cert_file')->store('birth-cert-files', 'public');
    }

    // Get the authenticated user's ID
    $userId = Auth::id();

    // Get the madrasha_id from the users table
    $user = \App\Models\User::find($userId);
    $madrashaId = $user ? $user->madrasha_id : null;

    // Create new application using Eloquent ORM with mass assignment
    $application = negMumtahinAplication::create([
        'user_id' => $userId, // Add the user_id from authenticated user
        'madrasha_id' => $madrashaId, // Add the madrasha_id from user
        'name' => $request->input('teachers.name'),
        'mobile' => $request->input('teachers.Mobile'),
        'birth_date' => $request->input('teachers.DateofBirth'),
        'whatsapp' => $request->input('teachers.whatsapp'),
        'teaching_experience' => $request->input('teachers.teaching_experience'),
        'negaranComments' => $request->input('teachers.negaranComments'),
        'mumtahinComments' => $request->input('teachers.mumtahinComments'),
        'nagad_number' => $request->input('teachers.nagad_number'),
        'rocket_number' => $request->input('teachers.rocket_number'),
        'bkash_number' => $request->input('teachers.bkash_number'),
        'selected_book' => $request->input('teachers.selected_book'),
         'teaching_books' => $request->input('teachers.teaching_books'),
        'division' => $request->input('teachers.division'),
        'district' => $request->input('teachers.district'),
        'thana' => $request->input('teachers.thana'),
        'birth_reg_no' => $request->input('teachers.National_Id_Card'),
        'nid' => $request->input('teachers.nid'),
        'gender' => $request->input('teachers.gender'),
        'profile_image' => $profileImagePath,
        'muhtamim_file' => $muhtamimFilePath,  // নতুন যোগ করা হয়েছে
        'nid_file' => $nidFilePath,            // নতুন যোগ করা হয়েছে
        'birth_cert_file' => $birthCertFilePath, // নতুন যোগ করা হয়েছে
        'application_type' => $request->input('applicationType'),
        'status' => 'pending',
    ]);

    // Store educational qualifications
    if ($request->has('educationalQualifications') && is_array($request->educationalQualifications)) {
        foreach ($request->educationalQualifications as $qualification) {
            EducationalQualification::create([
                'application_id' => $application->id,
                'class_name' => $qualification['className'] ?? null,
                'pass_year' => $qualification['passYear'] ?? null,
                'result' => $qualification['result'] ?? null,
                'institution' => $qualification['institution'] ?? null,
            ]);
        }
    }

    // Return Inertia response with flash message
    if ($request->wantsJson()) {
        // For XHR/fetch requests, return JSON
        return response()->json([
            'success' => true,
            'message' => 'আপনার আবেদন সফলভাবে জমা হয়েছে',
            'application_id' => $application->id
        ]);
    } else {
        // For Inertia requests, return redirect with flash
        return redirect()->back()->with('success', 'আপনার আবেদন সফলভাবে জমা হয়েছে');
    }
}




    public function OldtecherStore(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'teachers.name' => 'nullable|string|max:255',
            'teachers.Mobile' => 'nullable|string|max:255',
            'teachers.DateofBirth' => 'nullable|date',
            'teachers.whatsapp' => 'nullable|string|max:255',
            'teachers.teaching_experience' => 'nullable|string|max:255',
            'teachers.selected_book' => 'nullable|string|max:255',
            'teachers.National_Id_Card' => 'nullable|string|max:255',
            'teachers.nid' => 'nullable|string|max:255',
            'teachers.gender' => 'nullable|string|max:255',
            'profileImage' => 'nullable|image|max:2048',
            'muhtamim_file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',  // নতুন যোগ করা হয়েছে
            'nid_file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',       // নতুন যোগ করা হয়েছে
            'birth_cert_file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png', // নতুন যোগ করা হয়েছে
            'applicationType' => 'required|string',
            'educationalQualifications' => 'nullable|array',
            'educationalQualifications.*.className' => 'nullable|string|max:255',
            'educationalQualifications.*.passYear' => 'nullable|string|max:255',
            'educationalQualifications.*.result' => 'nullable|string|max:255',
            'educationalQualifications.*.institution' => 'nullable|string|max:255',
        ]);

        // Handle profile image upload
        $profileImagePath = null;
        if ($request->hasFile('profileImage')) {
            $profileImagePath = $request->file('profileImage')->store('profile-images', 'public');
        }

        // Handle new file uploads
        $muhtamimFilePath = null;
        if ($request->hasFile('muhtamim_file')) {
            $muhtamimFilePath = $request->file('muhtamim_file')->store('muhtamim-files', 'public');
        }

        $nidFilePath = null;
        if ($request->hasFile('nid_file')) {
            $nidFilePath = $request->file('nid_file')->store('nid-files', 'public');
        }

        $birthCertFilePath = null;
        if ($request->hasFile('birth_cert_file')) {
            $birthCertFilePath = $request->file('birth_cert_file')->store('birth-cert-files', 'public');
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Create new application using Eloquent ORM with mass assignment
        $application = negMumtahinAplication::create([
            'user_id' => $userId, // Add the user_id from authenticated user
            'name' => $request->input('teachers.name'),
            'mobile' => $request->input('teachers.Mobile'),
            'birth_date' => $request->input('teachers.DateofBirth'),
            'whatsapp' => $request->input('teachers.whatsapp'),
            'teaching_experience' => $request->input('teachers.teaching_experience'),
            'negaranComments' => $request->input('teachers.negaranComments'),
            'mumtahinComments' => $request->input('teachers.mumtahinComments'),
            'nagad_number' => $request->input('teachers.nagad_number'),
            'rocket_number' => $request->input('teachers.rocket_number'),
            'bkash_number' => $request->input('teachers.bkash_number'),
            'selected_book' => $request->input('teachers.selected_book'),
            'division' => $request->input('teachers.division'),
            'district' => $request->input('teachers.district'),
            'thana' => $request->input('teachers.thana'),
            'birth_reg_no' => $request->input('teachers.National_Id_Card'),
            'nid' => $request->input('teachers.nid'),
            'gender' => $request->input('teachers.gender'),
            'profile_image' => $profileImagePath,
            'muhtamim_file' => $muhtamimFilePath,  // নতুন যোগ করা হয়েছে
            'nid_file' => $nidFilePath,            // নতুন যোগ করা হয়েছে
            'birth_cert_file' => $birthCertFilePath, // নতুন যোগ করা হয়েছে
            'application_type' => $request->input('applicationType'),
            'status' => 'pending',
        ]);

        // Store educational qualifications
        if ($request->has('educationalQualifications') && is_array($request->educationalQualifications)) {
            foreach ($request->educationalQualifications as $qualification) {
                EducationalQualification::create([
                    'application_id' => $application->id,
                    'class_name' => $qualification['className'] ?? null,
                    'pass_year' => $qualification['passYear'] ?? null,
                    'result' => $qualification['result'] ?? null,
                    'institution' => $qualification['institution'] ?? null,
                ]);
            }
        }

        // Return Inertia response with flash message
        if ($request->wantsJson()) {
            // For XHR/fetch requests, return JSON
            return response()->json([
                'success' => true,
                'message' => 'আপনার আবেদন সফলভাবে জমা হয়েছে',
                'application_id' => $application->id
            ]);
        } else {
            // For Inertia requests, return redirect with flash
            return redirect()->back()->with('success', 'আপনার আবেদন সফলভাবে জমা হয়েছে');
        }
    }





    public function destroy($id)
    {
        try {
            // Find the record
            $application = DB::table('neg_mumtahin_aplications')->where('id', $id);

            // Check if record exists
            if (!$application->exists()) {
                return response()->json(['message' => 'Record not found'], 404);
            }

            // Delete the record
            $application->delete();

            return response()->json(['message' => 'Record deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete record', 'error' => $e->getMessage()], 500);
        }
    }

















   public function getTeachers()
{
    // Get the authenticated user's ID
    $userId = Auth::id();

    // Get applications using ORM
    $applications = NegMumtahinAplication::select(
        'id',
        'name',
        'birth_date',
        'application_type',
        'mobile'
    )
    ->where('user_id', $userId)
    ->get();

    // For each application, get the latest status from logs
    foreach ($applications as $application) {
        $latestLog = negran_mumtahin_log::where('application_id', $application->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Set the status from logs if available, otherwise keep the original status
        if ($latestLog) {
            $application->status = $latestLog->status;
        } else {
            // If no log found, you can set a default status or keep it null
            $application->status = null;
        }
    }

    return response()->json($applications);
}





















   public function negedit($id)
{
    $teacher = NegMumtahinAplication::with('educationalQualifications')->findOrFail($id);
    return response()->json($teacher);
}


    public function negupdate(Request $request, $id)
{
    $teacher = NegMumtahinAplication::findOrFail($id);

    // আবেদনকারীর মূল তথ্য আপডেট করুন
    $teacher->update($request->except('educational_qualifications'));

    // শিক্ষাগত যোগ্যতা আপডেট করুন
    if ($request->has('educational_qualifications')) {
        foreach ($request->educational_qualifications as $qualification) {
            if (isset($qualification['id'])) {
                // বিদ্যমান যোগ্যতা আপডেট করুন
                EducationalQualification::where('id', $qualification['id'])
                    ->where('application_id', $id)
                    ->update([
                        'class_name' => $qualification['class_name'],
                        'pass_year' => $qualification['pass_year'],
                        'result' => $qualification['result'],
                        'institution' => $qualification['institution']
                    ]);
            } else {
                // নতুন যোগ্যতা যোগ করুন
                EducationalQualification::create([
                    'application_id' => $id,
                    'class_name' => $qualification['class_name'],
                    'pass_year' => $qualification['pass_year'],
                    'result' => $qualification['result'],
                    'institution' => $qualification['institution']
                ]);
            }
        }
    }

    // আপডেট করা ডাটা রিটার্ন করুন
    $updatedTeacher = NegMumtahinAplication::with('educationalQualifications')->findOrFail($id);
    return response()->json($updatedTeacher);
}




// submit to board


public function submitedToBoard($id)
{
    try {
        DB::beginTransaction();

        // Find the application by ID
        $application = NegMumtahinAplication::findOrFail($id);

        // Get current authenticated user
        $user = Auth::user();

        // Create new log entry
        $log = new negran_mumtahin_log();
        $log->application_id = $application->id;
        $log->user_id = $user->id;
           $log->admin_id = null;
        $log->status = 'বোর্ড দাখিল';
        $log->actor_type = 'user';
        $log->admin_name = '';
        $log->user_name = $user->name;
        $log->user_position = $user->position ?? '';
        $log->admin_position = '';
        $log->save();

        // আমরা neg_mumtahin_aplications টেবিলের স্ট্যাটাস আপডেট করছি না

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'আবেদনটি সফলভাবে বোর্ডে দাখিল করা হয়েছে',
            'data' => $log
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'আবেদন দাখিল করতে সমস্যা হয়েছে: ' . $e->getMessage()
        ], 500);
    }
}


public function getStudentList(Request $request)
    {
        $query = students::query();


        // Filter by year if provided
        if ($request->filled('year')) {
            $query->where('years', $request->year);
        }

        // Filter by class if provided
        if ($request->filled('class')) {
            $query->where('Class', $request->class);
        }

        // Filter by SRType if provided
        if ($request->filled('srtype')) {
            $query->where('SRType', $request->srtype);
        }

        // Global search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Name', 'like', '%' . $search . '%')
                  ->orWhere('Roll', 'like', '%' . $search . '%')
                  ->orWhere('Father', 'like', '%' . $search . '%')
                  ->orWhere('Madrasha', 'like', '%' . $search . '%');
            });
        }

        // Sorting functionality with validation
        $allowedSortFields = ['Roll', 'Name', 'Father', 'DateofBirth', 'Madrasha', 'Class', 'years'];
        $sortBy = $request->get('sortBy', 'Roll');
        $sortDirection = $request->get('sortDirection', 'asc');

        // Validate sort field
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('Roll', 'asc');
        }

        // Paginate with custom per_page parameter (default 10, max 100)
        $perPage = min($request->get('per_page', 10), 100);
        $students = $query->paginate($perPage);

        // ইউনিক years, CID (class), SRType ডাটা
        $years = students::select('years')->distinct()->orderBy('years', 'desc')->pluck('years');
        $classes = students::select('CID')->distinct()->orderBy('CID')->pluck('CID');
        $srtypes = students::select('SRType')->distinct()->pluck('SRType');

        return response()->json([
            'students' => $students,
            'years' => $years,
            'classes' => $classes,
            'srtypes' => $srtypes
        ]);
    }

}
