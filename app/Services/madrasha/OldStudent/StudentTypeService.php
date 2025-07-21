<?php

namespace App\Services\madrasha\OldStudent;

use Illuminate\Support\Collection;
use App\Helpers\madrasha\OldStudent\BanglaTranslatorHelper;

class StudentTypeService
{
    public function validateGenderMismatch(Collection $students, $user)
    {
        if ($user->MType == 1) {
            if ($students->where('SRType', '!=', 1)->isNotEmpty()) {
                abort(response()->json(['error' => 'পুরুষ মাদরাসায় ছাত্রীদের রোল অনুসন্ধান করা হয়েছে'], 403));
            }
        } elseif ($user->MType == 0) {
            if ($students->where('SRType', '!=', 0)->isNotEmpty()) {
                abort(response()->json(['error' => 'আপনি মহিলা মাদরাসা হয়ে ছাত্রদের রোল অনুসন্ধান করেছেন'], 403));
            }
        }
    }

    public function filterAndClassify(Collection $students, $marhalaId, BanglaTranslatorHelper $translator)
    {
        $filtered = collect();

        foreach ($students as $student) {
            $student = $translator->translateFields($student);

            if (
                $student->Division == 'রাসিব' &&
                $student->years == '2024' &&
                (
                    ($student->CID == 3 && $marhalaId == 9) ||
                    ($student->CID == 4 && $marhalaId == 10) ||
                    ($student->CID == 5 && $marhalaId == 11) ||
                    ($student->CID == 6 && $marhalaId == 12)
                )
            ) {
                continue;
            }

            $this->determineStudentType($student, $marhalaId);
            unset($student->SRType);
            $filtered->push($student);
        }

        return $filtered;
    }

    public function determineStudentType(&$student, $marhalaId)
    {
        $student->student_type = 'নিয়মিত';

        $applyRules = (
            ($marhalaId == 9 && $student->CID == 2 && $student->years == '2024') ||
            ($marhalaId == 10 && $student->CID == 3 && $student->years == '2024') ||
            ($marhalaId == 11 && $student->CID == 4 && $student->years == '2024') ||
            ($marhalaId == 12 && $student->CID == 5 && $student->years == '2024') ||
            ($marhalaId == 14 && $student->CID == 6 && $student->years == '2024')
        );

        if ($applyRules) {
            $classifier = new StudentClassifier();
            $counts = $classifier->getSubjectCounts($student);
            $student->student_type = $classifier->classifyStudent($counts, $student);

            $student->failed_subjects = $counts['failed'];
            $student->absent_subjects = $counts['absent'];
            $student->zero_subjects = $counts['zero'];
            $student->irregular_subjects = $classifier->getIrregularSubjects($student, $student->student_type);
        }
    }
}
