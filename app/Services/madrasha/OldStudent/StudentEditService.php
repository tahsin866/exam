<?php

namespace App\Services\madrasha\OldStudent;

use App\Models\student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentEditService
{
    public static function handleEditPage(Request $request)
    {
        if ($request->has('data')) {
            try {
                $decoded = base64_decode($request->data);
                list($roll, $reg_id, $CID) = explode(':', $decoded);

                return Inertia::render('students_registration/old_stu_reg_edit', compact('roll', 'reg_id', 'CID'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Invalid student data');
            }
        }

        if ($request->has(['roll', 'reg_id', 'CID'])) {
            return Inertia::render('students_registration/old_stu_reg_edit', [
                'roll' => $request->roll,
                'reg_id' => $request->reg_id,
                'CID' => $request->CID
            ]);
        }

        return redirect()->back()->with('error', 'Student information is missing');
    }

    public static function fetchStudentForEdit(Request $request)
    {
        $roll = $request->query('roll');
        $reg_id = $request->query('reg_id');
        $CID = $request->query('CID');
        $marhalaId = $request->header('marhalaId');

        $student = Student::where('Roll', $roll)
            ->where('reg_id', $reg_id)
            ->where('CID', $CID)
            ->first();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $markazFromRledger = DB::table('stu_rledger_p')
            ->where('MRID', $student->MID)
            ->value('MDID');

        $markazName = $markazFromRledger ? $student->Markaj : $student->Markaj;

        $response = [
            'pastExam' => [
                'id' => $student->id,
                'Name' => $student->Name,
                'Father' => $student->Father,
                'Mother' => $student->Mother,
                'DateofBirth' => $student->DateofBirth,
                'Roll' => $student->Roll,
                'reg_id' => $student->reg_id,
                'Madrasha' => $student->Madrasha,
                'Markaj' => $student->Markaj,
                'Class' => $student->Class,
                'Division' => $student->Division,
                'years' => $student->years
            ],
            'currentExam' => [
                'Madrasha' => Auth::user()->madrasha_name,
                'Markaj' => $markazName,
                'marhalaId' => $marhalaId
            ]
        ];

        $studentTypeService = new StudentTypeService();
        $studentTypeService->determineStudentType($response['currentExam'], $marhalaId, $student);

        return response()->json($response);
    }
}
