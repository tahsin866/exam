<?php

namespace App\Http\Controllers\madrasha\OldStudent;
use App\Models\student;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class studentLogicController extends Controller
{

public function searchStudents(Request $request)
{
    // Get the authenticated user
    $user = Auth::user();

    // Check if user exists
    if (!$user) {
        return response()->json(['error' => 'অনুগ্রহ করে লগইন করুন'], 401);
    }

    $query = Student::query();

    // Apply filters
    if ($request->filled('marhala')) {
        $query->where('CID', $request->marhala);
    }

    // Set default year to 2024 if not provided
    $year = $request->filled('year') ? $request->year : '2024';
    $query->where('years', $year);

    if ($request->filled('roll')) {
        $query->where('Roll', $request->roll);
    }

    if ($request->filled('registration')) {
        $query->where('reg_id', $request->registration);
    }

    // Get the results with specific fields
    $students = $query->select(
        'Name',
        'Madrasha',
        'Father',
        'DateofBirth',
        'Class',
        'Markaj',
        'Division',
        'CID',
        'years',
        'Roll',
        'reg_id',
        'Absence',
        'SRType', // Added SRType field to check student gender

    )
        ->get();

    // Check if the user's MType matches with student's SRType
    if ($user->MType == 1) { // Male madrasa
        // Check if any student has SRType != 1 (not male)
        $nonMatchingStudents = $students->where('SRType', '!=', 1);
        if ($nonMatchingStudents->isNotEmpty()) {
            return response()->json(['error' => 'পুরুষ মাদরাসায় ছাত্রীদের রোল অনুসন্ধান করা হয়েছে'], 403);
        }
    } elseif ($user->MType == 0) { // Female madrasa
        // Check if any student has SRType != 2 (not female)
        $nonMatchingStudents = $students->where('SRType', '!=', 0);
        if ($nonMatchingStudents->isNotEmpty()) {
            return response()->json(['error' => 'আপনি মহিলা মাদরাসা হয়ে ছাত্রদের রোল অনুসন্ধান করেছেন'], 403);
        }
    }

    // Create translator instance
    $translator = new Translator();

    // Get marhalaId from header
    $marhalaId = $request->header('marhalaId');

    // Filter students based on special conditions
    $filteredStudents = collect();
    foreach ($students as $student) {
        // Translate fields
        $student->Madrasha = $translator->translate($student->Madrasha);
        $student->Name = $translator->translate($student->Name);
        $student->Father = $translator->translate($student->Father);
        $student->Division = $translator->translate($student->Division);
        $student->Class = $translator->translate($student->Class);
        $student->Markaj = $translator->translate($student->Markaj);
        // $student->Roll = $translator->translate($student->Roll);
        // $student->reg_id = $translator->translate($student->reg_id);
        $student->DateofBirth = $translator->translate($student->DateofBirth);

        // Skip students with Division = রাসিব for specific CID and marhalaId combinations
        if (
            $student->Division == 'রাসিব' && $student->years == '2024' && (
                ($student->CID == 3 && $marhalaId == 9) ||
                ($student->CID == 4 && $marhalaId == 10) ||
                ($student->CID == 5 && $marhalaId == 11) ||
                ($student->CID == 6 && $marhalaId == 12)
            )
        ) {
            // Skip this student
            continue;
        }

        // For all other cases, include the student
        $filteredStudents->push($student);
    }

    // Process all students based on new conditions
    foreach ($filteredStudents as $student) {
        // Initialize shouldApplyRules to false by default
        $shouldApplyRules = false;

        // Check for the specific combinations where rules should apply
        if (
            ($marhalaId == 9 && $student->CID == 2 && $student->years == '2024') ||
            ($marhalaId == 10 && $student->CID == 3 && $student->years == '2024') ||
            ($marhalaId == 11 && $student->CID == 4 && $student->years == '2024') ||
            ($marhalaId == 12 && $student->CID == 5 && $student->years == '2024') ||
            ($marhalaId == 14 && $student->CID == 6 && $student->years == '2024')
        ) {
            $shouldApplyRules = true;
        }

        // Only apply the classification rules if the student matches the specific combinations
        if ($shouldApplyRules) {
            // Initialize counters
            $failedSubjects = 0;
            $absentSubjects = 0; // অনুপস্থিত (Absence field = 'অনুপস্থিত')
            $zeroSubjects = 0;   // অনু (SubValue = 0)

            $subjectFields = [
                'SubValue_1',
                'SubValue_2',
                'SubValue_3',
                'SubValue_4',
                'SubValue_5',
                'SubValue_6',
                'SubValue_7',
                'SubValue_8'
            ];

            foreach ($subjectFields as $index => $field) {
                $labelField = 'SubLabel_' . ($index + 1);
                $absenceField = 'Absence_' . ($index + 1);

                // Only count subjects that have a label (name)
                if (isset($student->$labelField) && !empty($student->$labelField)) {
                    // Count subjects with marks below 33 but not 0 (ফেল)
                    if (isset($student->$field) && $student->$field < 33 && $student->$field > 0) {
                        $failedSubjects++;
                    }

                    // Count subjects with 0 marks (অনু)
                    if (isset($student->$field) && $student->$field === 0) {
                        $zeroSubjects++;
                    }

                    // Count subjects marked as 'অনুপস্থিত'
                    if (isset($student->$absenceField) && $student->$absenceField === 'অনুপস্থিত') {
                        $absentSubjects++;
                    }
                }
            }

            // Determine student type based on the new rules
            // "অনিয়মিত যেমনী" conditions
            if (
                // 1. এক বা দুই বিষয়ে ফেল এবং বিভাগ রাসিব
                (($failedSubjects == 1 || $failedSubjects == 2) && $student->Division === 'রাসিব') ||
                // 2. এক বা দুই বিষয়ে অনু (SubValue = 0) এবং কোন বিষয়ে ফেল নেই
                (($zeroSubjects == 1 || $zeroSubjects == 2) && $failedSubjects == 0) ||
                // 3. এক বিষয়ে অনু এবং এক বিষয়ে ফেল
                ($zeroSubjects == 1 && $failedSubjects == 1)
            ) {
                $student->student_type = 'অনিয়মিত যেমনী';
            }
            // "অনিয়মিত অন্যান্য" conditions
            elseif (
                // 1. দুয়ের অধিক বিষয়ে ফেল এবং বিভাগ রাসিব
                ($failedSubjects > 2 && $student->Division === 'রাসিব') ||
                // 2. দুই বিষয় অনু এক বিষয়ে ফেল বা এক বিষয়ে অনু দুই বিষয়ে ফেল
                ($zeroSubjects == 2 && $failedSubjects == 1) ||
                ($zeroSubjects == 1 && $failedSubjects == 2) ||
                // 3. এক বিষয়ে অনু এবং দুয়ের অধিক বিষয়ে ফেল
                ($zeroSubjects == 1 && $failedSubjects > 2) ||
                // 4. একের অধিক অনু একের অধিক ফেল
                ($zeroSubjects > 1 && $failedSubjects > 1) ||
                // 5. দুয়ের অধিক অনু বিভাগ অনু
                ($zeroSubjects > 2 && $student->Absence === 'অনুপস্থিত')
            ) {
                $student->student_type = 'অনিয়মিত অন্যান্য';
            }
            // Division = রাসিব এবং মুমতায নয় এমন হলে মানউন্নয়ন
            elseif ($student->Division !== 'রাসিব' && $student->Division !== 'মুমতায') {
                $student->student_type = 'মানউন্নয়ন';
            }

            // Debugging info
            $student->failed_subjects = $failedSubjects;
            $student->absent_subjects = $absentSubjects;
            $student->zero_subjects = $zeroSubjects;
        } else {
            // For students that don't match the specific combinations, set a default type
            $student->student_type = 'নিয়মিত'; // Or any other default type you prefer
        }

        // Remove SRType from the response
        unset($student->SRType);
    }

    // যদি রেজাল্ট ফাঁকা হয়, তাহলে error message দিবে
    if ($filteredStudents->isEmpty()) {
        return response()->json(['error' => 'রেজাল্ট পাওয়া যায়নি'], 404);
    }

    return response()->json($filteredStudents);
}






public function editStudentRegistration(Request $request)
    {
        // Check if we have encoded data
        if ($request->has('data')) {
            // Decode the data
            try {
                $decodedData = base64_decode($request->data);
                list($roll, $reg_id, $CID) = explode(':', $decodedData);

                return Inertia::render('students_registration/old_stu_reg_edit', [
                    'roll' => $roll,
                    'reg_id' => $reg_id,
                    'CID' => $CID
                ]);
            } catch (\Exception $e) {
                // Handle decoding error
                return redirect()->back()->with('error', 'Invalid student data');
            }
        }
        // Fallback to direct parameters if encoded data is not provided
        else if ($request->has('roll') && $request->has('reg_id') && $request->has('CID')) {
            return Inertia::render('students_registration/old_stu_reg_edit', [
                'roll' => $request->roll,
                'reg_id' => $request->reg_id,
                'CID' => $request->CID
            ]);
        }
        // No valid data provided
        else {
            return redirect()->back()->with('error', 'Student information is missing');
        }
    }







}
