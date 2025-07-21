<?php

namespace App\Http\Controllers;
use App\Models\admin\marhala_for_admin\subject_settings;
use App\Models\admin\marhala_for_admin\MarhalaSubject;
use App\Models\admin\marhala_for_admin\ExamSetup;
use App\Models\admin\marhala_for_admin\ExamFee;
use App\Models\admin\marhala_for_admin\Marhala;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
class SubjectSettingsController extends Controller
{





    public function getData($marhalaId)
    {
        $marhala = Marhala::select('id', 'marhala_name_bn')->findOrFail($marhalaId);

        // এখানে subject_code নাম ঠিক করুন
        $subjects = MarhalaSubject::where('marhala_id', $marhalaId)
            ->select('id', 'name_bangla', 'subject_code') // subject_code as code না লিখে subject_code লিখুন
            ->get();

        return response()->json([
            'marhala' => $marhala,
            'subjects' => $subjects
        ]);
    }




public function subjectStore(Request $request)
{
    $validated = $request->validate([
        'marhala_id' => 'required',
        'subject_id' => 'required',
        'Marhala_type' => 'required',
        'Subject_Names' => 'required',
        'student_type' => 'required',
        'syllabus_type' => 'required',
        'markaz_type' => 'required',
        'subject_type' => 'required',
        'subject_code' => 'required',
        'total_marks' => 'required',
        'pass_marks' => 'required',
        'status' => 'required|in:active,inactive'
    ]);
 subject_settings::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Subject settings saved successfully',

        ]);
}











public function getSubjectSetting($id)
{
    $subjectSetting = subject_settings::findOrFail($id);

    return response()->json([
        'success' => true,
        'subjectSetting' => $subjectSetting
    ]);
}

public function getsubjecData($marhalaId)
{
    $marhala = Marhala::select('id', 'marhala_name_bn')->findOrFail($marhalaId);
    $subjects = MarhalaSubject::where('marhala_id', $marhalaId)
        ->select('id', 'name_bangla', 'subject_code')
        ->get();

    return response()->json([
        'marhala' => $marhala,
        'subjects' => $subjects
    ]);
}

public function updateSubjectSetting(Request $request, $id)
{
    $validated = $request->validate([
        'marhala_id' => 'required',
        'subject_id' => 'required',
        'Marhala_type' => 'required',
        'Subject_Names' => 'required',
        'student_type' => 'required',
        'syllabus_type' => 'required',
        'markaz_type' => 'required',
        'subject_type' => 'required',
        // 'subject_code' => 'required',
        'total_marks' => 'required',
        'pass_marks' => 'required',
        'status' => 'required|in:active,inactive'
    ]);

    $subjectSetting = subject_settings::findOrFail($id);
    $subjectSetting->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'বিষয় সেটাপ সঠিকভাবে আপডেট করা হয়েছে',
    ]);
}











public function search(Request $request)
{
    $query = subject_settings::with('MarhalaSubject');

    // Search by subject code or name
    if ($request->filled('search_term')) {
        $query->where(function($q) use ($request) {
            $q->whereHas('MarhalaSubject', function($subQuery) use ($request) {
                $subQuery->where('subject_code', 'LIKE', '%' . $request->search_term . '%');
            })
            ->orWhere('Subject_Names', 'LIKE', '%' . $request->search_term . '%');
        });
    }

    // Filter by markaz type
    if ($request->filled('markaz_type')) {
        $query->where('markaz_type', $request->markaz_type);
    }

    // Filter by marhala type
    if ($request->filled('marhala_type')) {
        $query->where('Marhala_type', $request->marhala_type);
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $subjects = $query->get()->map(function ($subject) {
        return [
            'id' => $subject->id,
            'code' => $subject->MarhalaSubject->subject_code ?? 'N/A',
            'Subject_Names' => $subject->Subject_Names,
            'student_type' => $subject->student_type,
            'syllabus_type' => $subject->syllabus_type,
            'subject_type' => $subject->subject_type,
            'Marhala_type' => $subject->Marhala_type,
            'markaz_type' => $subject->markaz_type,
            'total_marks' => $subject->total_marks,
            'pass_marks' => $subject->pass_marks,
            'status' => $subject->status,
        ];
    });

    // Get unique marhala types for dropdown
    $marhalaTypes = subject_settings::distinct()->pluck('Marhala_type');

    return response()->json([
        'subjects' => $subjects,
        'marhalaTypes' => $marhalaTypes
    ]);
}




public function store_1(Request $request)
    {
        $validated = $request->validate([
            'exam_name' => 'required|string|max:255',
            'arabic_year' => 'required|string',
            'bangla_year' => 'required|string',
            'english_year' => 'required|string',
        ]);

        $examSetup = ExamSetup::create($validated);

        return response()->json($examSetup, 201);

    }






    public function getLatest()
    {
        return ExamSetup::latest()->first();
    }
    public function index_1()
    {
        return Marhala::select('id', 'marhala_name_bn')->get();
    }



 public function store_2(Request $request)
{
    try {
        $validated = $request->validate([
            'fees' => 'required|array',
            'fees.*.exam_setup_id' => 'required|exists:exam_setups,id',
            'fees.*.exam_name' => 'required|string',
            'fees.*.reg_date_from' => 'required|date',
            'fees.*.reg_date_to' => 'required|date',
            'fees.*.reg_regular_fee' => 'required|numeric',
            'fees.*.reg_irregular_jemni' => 'required|numeric',
            'fees.*.reg_irregular_manonnoyon' => 'required|numeric',
            'fees.*.reg_irregular_others' => 'required|numeric',
            'fees.*.late_date_from' => 'required|date',
            'fees.*.late_date_to' => 'required|date',
            'fees.*.late_regular_fee' => 'required|numeric',
            'fees.*.late_irregular_jemni' => 'required|numeric',
            'fees.*.late_irregular_manonnoyon' => 'required|numeric',
            'fees.*.late_irregular_others' => 'required|numeric'
        ]);

        foreach ($request->fees as $fee) {
            ExamFee::create($fee);
        }

        return response()->json(['message' => 'Fees saved successfully'], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}





}













