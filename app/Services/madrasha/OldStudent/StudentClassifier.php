<?php

namespace App\Services\madrasha\OldStudent;

class StudentClassifier
{
    public function getSubjectCounts($student)
    {
        $failed = 0;
        $zero = 0;
        $absent = 0;

        for ($i = 1; $i <= 8; $i++) {
            $label = 'SubLabel_' . $i;
            $value = 'SubValue_' . $i;
            $absence = 'Absence_' . $i;

            if (!empty($student->$label)) {
                if (isset($student->$value) && $student->$value < 33 && $student->$value > 0) $failed++;
                if (isset($student->$value) && $student->$value === 0) $zero++;
                if (isset($student->$absence) && $student->$absence === 'অনুপস্থিত') $absent++;
            }
        }

        return compact('failed', 'zero', 'absent');
    }

    public function classifyStudent($counts, $student)
    {
        $failed = $counts['failed'];
        $zero = $counts['zero'];
        $absent = $counts['absent'];

        if (
            (($failed == 1 || $failed == 2) && $student->Division === 'রাসিব') ||
            (($zero == 1 || $zero == 2) && $failed == 0) ||
            ($zero == 1 && $failed == 1)
        ) {
            return 'অনিয়মিত যেমনী';
        }

        if (
            ($failed > 2 && $student->Division === 'রাসিব') ||
            ($zero == 2 && $failed == 1) ||
            ($zero == 1 && $failed == 2) ||
            ($zero == 1 && $failed > 2) ||
            ($zero > 1 && $failed > 1) ||
            ($zero > 2 && $student->Absence === 'অনুপস্থিত')
        ) {
            return 'অনিয়মিত অন্যান্য';
        }

        if ($student->Division !== 'রাসিব' && $student->Division !== 'মুমতায') {
            return 'মানউন্নয়ন';
        }

        return 'নিয়মিত';
    }

    public function getIrregularSubjects($student, $type)
    {
        $subjects = [];

        if ($type === 'অনিয়মিত অন্যান্য') {
            for ($i = 1; $i <= 8; $i++) {
                $label = 'SubLabel_' . $i;
                if (!empty($student->$label)) $subjects[] = $student->$label;
            }
        } elseif ($type === 'অনিয়মিত যেমনী') {
            for ($i = 1; $i <= 8; $i++) {
                $label = 'SubLabel_' . $i;
                $value = 'SubValue_' . $i;
                if (!empty($student->$label) && isset($student->$value) && $student->$value < 33) {
                    $subjects[] = $student->$label;
                }
            }
        }

        return implode(', ', $subjects);
    }
}
