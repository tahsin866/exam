<?php

namespace App\Http\Controllers;
use App\Models\admin\marhala_for_admin\ExamSetup;
use App\Models\admin\marhala_for_admin\ExamFee;
use App\Models\admin\marhala_for_admin\Marhala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class ExamSetupController extends Controller
{

    public function index_2()
{
    return ExamSetup::orderBy('id', 'desc')
        ->select('id', 'exam_name', 'arabic_year', 'bangla_year', 'english_year')
        ->get();
}
    //



    public function edit($id)
    {
        $examFees = ExamFee::where('exam_setup_id', $id)->get();
        $examSetup = ExamSetup::findOrFail($id);

        return Inertia::render('central_Exam_setup/central_exam_edit', [
            'examFees' => $examFees,
            'examSetup' => $examSetup
        ]);
    }






    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'reg_date_from' => 'required|date',
            'reg_date_to' => 'required|date',
            'reg_regular_fee' => 'required|numeric',
            'reg_irregular_jemni' => 'required|numeric',
            'reg_irregular_manonnoyon' => 'required|numeric',
            'reg_irregular_others' => 'required|numeric',
            'late_date_from' => 'required|date',
            'late_date_to' => 'required|date',
            'late_regular_fee' => 'required|numeric',
            'late_irregular_jemni' => 'required|numeric',
            'late_irregular_manonnoyon' => 'required|numeric',
            'late_irregular_others' => 'required|numeric'
        ]);

        $examFee = ExamFee::findOrFail($id);
        $examFee->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'পরীক্ষার ফি সফলভাবে আপডেট করা হয়েছে',
            'data' => $examFee
        ]);
    }









    // public function getExamSetup($id)
    // {
    //     $examSetup = ExamSetup::findOrFail($id);

    //     return response()->json([
    //         'success' => true,
    //         'examSetup' => $examSetup
    //     ]);
    // }



    public function updateExamSetup(Request $request, $id)
    {
        $validated = $request->validate([
            'exam_name' => 'required|string',
            'arabic_year' => 'required|string',
            'bangla_year' => 'required|string',
            'english_year' => 'required|string',
        ]);

        $examSetup = ExamSetup::findOrFail($id);
        $examSetup->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'পরীক্ষা সেটাপ সফলভাবে আপডেট করা হয়েছে!',
        ]);
    }









}
