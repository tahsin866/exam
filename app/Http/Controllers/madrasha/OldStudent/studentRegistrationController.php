<?php

namespace App\Http\Controllers\madrasha\OldStudent;

use App\Models\reg_stu_information;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;


class studentRegistrationController extends Controller
{






// public function editStudentRegistration(Request $request)
//     {
//         // Check if we have encoded data
//         if ($request->has('data')) {
//             // Decode the data
//             try {
//                 $decodedData = base64_decode($request->data);
//                 list($roll, $reg_id, $CID) = explode(':', $decodedData);

//                 return Inertia::render('students_registration/old_stu_reg_edit', [
//                     'roll' => $roll,
//                     'reg_id' => $reg_id,
//                     'CID' => $CID
//                 ]);
//             } catch (\Exception $e) {
//                 // Handle decoding error
//                 return redirect()->back()->with('error', 'Invalid student data');
//             }
//         }
//         // Fallback to direct parameters if encoded data is not provided
//         else if ($request->has('roll') && $request->has('reg_id') && $request->has('CID')) {
//             return Inertia::render('students_registration/old_stu_reg_edit', [
//                 'roll' => $request->roll,
//                 'reg_id' => $request->reg_id,
//                 'CID' => $request->CID
//             ]);
//         }
//         // No valid data provided
//         else {
//             return redirect()->back()->with('error', 'Student information is missing');
//         }
//     }














   public function saveStudentInfo(Request $request, $marhalaId)
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
            // 'student_id' => 'required|exists:students,id',

            'present_division_name' => 'nullable|string|max:255',
            'presernt_DID' => 'nullable|numeric',
            'present_district_name' => 'nullable|string|max:255',
            'present_des_id' => 'nullable|numeric',
            'present_thana_name' => 'nullable|string|max:255',
            'present_TID' => 'nullable|numeric',
            'parmanent_division_name' => 'nullable|string|max:45',
            'parmanent_DID' => 'nullable|numeric',
            'parmanent_district_name' => 'nullable|string|max:45',
            'parmanent_desId' => 'nullable|numeric',
            'parmanent_thana_name' => 'nullable|string|max:45',
            'parmanent_TID' => 'nullable|numeric',

            'student_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'NID_attach' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',


        ]);

        // ফাইল আপলোড হ্যান্ডলিং
        $studentImagePath = null;
        if ($request->hasFile('student_image')) {
            $studentImagePath = $request->file('student_image')->store('student_images', 'public');
        }

        $nidAttachmentPath = null;
        if ($request->hasFile('NID_attach')) {
            $nidAttachmentPath = $request->file('NID_attach')->store('nid_attachments', 'public');
        }

        $examSetup = DB::table('exam_setups')
            ->select('id', 'exam_name')
            ->latest('id')
            ->first();



            $classMap = [
                '2' => 'ফযিলত',
                '3' => 'সানাবিয়া উলইয়া',
                '4' => 'সানাবিয়া',
                '5' => 'মুতাওয়াসসিতাহ',
                '6' => 'ইবতিদাইয়্যাহ',
                '7' => 'হিফযুল কুরআন',
                '8' => 'ইলমুত তাজবীদ ওয়াল ক্বিরাআত'
            ];

            // Get class name from mapping
            $className = isset($classMap[$marhalaId]) ? $classMap[$marhalaId] : null;





            $marhala = DB::table('marhalas')
            ->select('id', 'marhala_name_bn')
            ->where('id', $marhalaId)
            ->first();


            $markazId = null;
            $markazName = null;

            // Get markaz ID from stu_rledger_p table
            $markazFromRledger = DB::table('stu_rledger_p')
                ->where('MRID', Auth::user()->madrasha_id)
                ->select('MDID')
                ->first();

            if ($markazFromRledger) {
                $markazId = $markazFromRledger->MDID;

                // Get markaz name from madrasha table using the markaz ID
                $markazInfo = DB::table('madrasha')
                    ->where('id', $markazId)
                    ->select('MName')
                    ->first();

                if ($markazInfo) {
                    $markazName = $markazInfo->MName;
                }
            }



        // Create new record in reg_stu_informations table
        reg_stu_information::updateOrCreate(
            // ['student_id' => $request->IDs], // Find by student_id
            [

                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'madrasha_id' => Auth::user()->madrasha_id,
                // Add exam information from exam_setups table
                'current_class' => $className,
                'marhala_id' => $marhalaId,
                'markaz_id' => $markazId,
                'exam_id' => $examSetup ? $examSetup->id : null,
                'exam_name_Bn' => $examSetup ? $examSetup->exam_name : null,
                'name_bn' => $request->name_bn,
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'father_name_bn' => $request->father_name_bn,
                'father_name_en' => $request->father_name_en,
                'father_name_ar' => $request->father_name_ar,
                'mother_name_bn' => $request->mother_name_bn,
                'mother_name_en' => $request->mother_name_en,
                'mother_name_ar' => $request->mother_name_ar,
                'BRN_no' => $request->BRN_no,
                'NID_no' => $request->NID_no,
                'past_Roll' => $request->past_Roll,
                'past_reg_id' => $request->past_reg_id,
                'madrasha_name' => $request->madrasha_name,
                'class' => $request->class,
                'Division' => $request->Division,
                'Date_of_birth' => $request->Date_of_birth,
                'current_madrasha' => $request->current_madrasha,
          'current_markaz' => $markazName,
                'student_type' => $request->student_type,
                // 'current_class' => $request->current_class,
                'exam_books_name' => $request->exam_books_name,
                'mobile_no' => $request->mobile_no,
                'markaz_name' => $request->markaz_name,
                'passing_year' => $request->passing_year,

                'present_division_name' => $request->present_division_name,
                'presernt_DID' => $request->presernt_DID,
                'present_district_name' => $request->present_district_name,
                'present_desId' => $request->present_desId,
                'present_thana_name' => $request->present_thana_name,
                'present_TID' => $request->present_TID,

                // New fields for permanent address
                'parmanent_division_name' => $request->parmanent_division_name,
                'parmanent_DID' => $request->parmanent_DID,
                'parmanent_district_name' => $request->parmanent_district_name,
                'parmanent_desId' => $request->parmanent_desId,
                'parmanent_thana_name' => $request->parmanent_thana_name,
                'parmanent_TID' => $request->parmanent_TID,
                'student_image' => $request->student_image,

                // 'student_image' => $studentImagePath,
                'NID_attach' => $nidAttachmentPath,

                'is_old_student' => 1,
            ]
        );

        return response()->json(['message' => 'Student information saved successfully']);
    }




}
