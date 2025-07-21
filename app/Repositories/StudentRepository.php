<?php

namespace App\Repositories\madrasha\OldStudent;

use App\Models\student;
use App\Models\admin\marhala_for_admin\ExamSetup;
use App\Models\admin\marhala_for_admin\Marhala;

class StudentRepository
{
    protected $studentClassifier;

    public function __construct($studentClassifier)
    {
        $this->studentClassifier = $studentClassifier;
    }

    public function search($request)
    {
        return student::query()
            ->when($request->filled('marhala'), fn ($q) => $q->where('CID', $request->marhala))
            ->when($request->filled('year'), fn ($q) => $q->where('years', $request->year), fn ($q) => $q->where('years', '2024'))
            ->when($request->filled('roll'), fn ($q) => $q->where('Roll', $request->roll))
            ->when($request->filled('registration'), fn ($q) => $q->where('reg_id', $request->registration))
            ->select(
                'Name', 'Madrasha', 'Father', 'DateofBirth', 'Class', 'Markaj', 'Division',
                'CID', 'years', 'Roll', 'reg_id', 'Absence', 'SRType',
                'SubLabel_1', 'SubLabel_2', 'SubLabel_3', 'SubLabel_4', 'SubLabel_5', 'SubLabel_6', 'SubLabel_7', 'SubLabel_8',
                'SubValue_1', 'SubValue_2', 'SubValue_3', 'SubValue_4', 'SubValue_5', 'SubValue_6', 'SubValue_7', 'SubValue_8',
                'Absence_1', 'Absence_2', 'Absence_3', 'Absence_4', 'Absence_5', 'Absence_6', 'Absence_7', 'Absence_8',
                'MID', 'Mother'
            )
            ->get();
    }

   public function getRegistrationData($marhalaId)
{
    $examName = $this->studentClassifier->getExamName($marhalaId);

    $marhala = Marhala::find($marhalaId);

    $defaultMap = [
        '2' => 'ফযীলত',
        '3' => 'সানাবিয়া উলইয়া',
        '4' => 'সানাবিয়া',
        '5' => 'মুতাওয়াসসিতাহ',
        '6' => 'ইবতিদাইয়্যাহ',
        '7' => 'হিফযুল কুরআন',
        '8' => 'ইলমুত তাজবীদ ওয়াল ক্বিরাআত'
    ];

    $marhalaName = $marhala?->marhala_name_bn ?? ($defaultMap[$marhalaId] ?? 'Unknown');

    return response()->json([
        'examName' => $examName,
        'marhalaName' => $marhalaName,
    ]);
}

}
