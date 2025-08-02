<?php

namespace App\Helpers\markaz\OldStudent;

use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;
use Illuminate\Database\Eloquent\Collection;

class StudentSearchHelper
{
    private Translator $translator;

    public function __construct()
    {
        $this->translator = new Translator();
    }

    /**
     * Validate gender compatibility between user and students
     */
    public function validateGenderCompatibility(Collection $students, int $userMType): ?string
    {
        if ($userMType === 1) { // Male madrasa
            $nonMatchingStudents = $students->where('SRType', '!=', 1);
            if ($nonMatchingStudents->isNotEmpty()) {
                return 'পুরুষ মাদরাসায় ছাত্রীদের রোল অনুসন্ধান করা হয়েছে';
            }
        } elseif ($userMType === 0) { // Female madrasa
            $nonMatchingStudents = $students->where('SRType', '!=', 0);
            if ($nonMatchingStudents->isNotEmpty()) {
                return 'আপনি মহিলা মাদরাসা হয়ে ছাত্রদের রোল অনুসন্ধান করেছেন';
            }
        }

        return null;
    }

    /**
     * Translate student fields from Bijoy to Avro
     */
    public function translateStudentFields($student): void
    {
        $fieldsToTranslate = ['Madrasha', 'Name', 'Father', 'Division', 'Class', 'Markaj', 'DateofBirth'];

        foreach ($fieldsToTranslate as $field) {
            if (isset($student->$field)) {
                $student->$field = $this->translator->translate($student->$field);
            }
        }
    }

    /**
     * Check if student should be filtered based on special conditions
     */
    public function shouldFilterStudent($student, ?string $marhalaId): bool
    {
        // Skip students with Division = রাসিব for specific CID and marhalaId combinations
        return $student->Division == 'রাসিব' &&
               $student->years == '2024' &&
               $this->isSpecialCombination($student->CID, $marhalaId);
    }

    /**
     * Check if it's a special CID and marhalaId combination
     */
    private function isSpecialCombination(int $cid, ?string $marhalaId): bool
    {
        $combinations = [
            3 => 9,
            4 => 10,
            5 => 11,
            6 => 12
        ];

        return isset($combinations[$cid]) && $combinations[$cid] == $marhalaId;
    }

    /**
     * Check if rules should apply for student classification
     */
    public function shouldApplyRules($student, ?string $marhalaId): bool
    {
        $applicableRules = [
            ['marhalaId' => 9, 'CID' => 2, 'years' => '2024'],
            ['marhalaId' => 10, 'CID' => 3, 'years' => '2024'],
            ['marhalaId' => 11, 'CID' => 4, 'years' => '2024'],
            ['marhalaId' => 12, 'CID' => 5, 'years' => '2024'],
            ['marhalaId' => 14, 'CID' => 6, 'years' => '2024'],
        ];

        foreach ($applicableRules as $rule) {
            if ($marhalaId == $rule['marhalaId'] &&
                $student->CID == $rule['CID'] &&
                $student->years == $rule['years']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate subject statistics for a student
     */
    public function calculateSubjectStatistics($student): array
    {
        $failedSubjects = 0;
        $absentSubjects = 0;
        $zeroSubjects = 0;

        $subjectFields = [
            'SubValue_1', 'SubValue_2', 'SubValue_3', 'SubValue_4',
            'SubValue_5', 'SubValue_6', 'SubValue_7', 'SubValue_8'
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

        return [
            'failed' => $failedSubjects,
            'absent' => $absentSubjects,
            'zero' => $zeroSubjects
        ];
    }

    /**
     * Determine student type based on subject statistics
     */
    public function determineStudentType($student, array $subjectStats): string
    {
        $failedSubjects = $subjectStats['failed'];
        $absentSubjects = $subjectStats['absent'];
        $zeroSubjects = $subjectStats['zero'];

        // "অনিয়মিত যেমনী" conditions
        if (
            (($failedSubjects == 1 || $failedSubjects == 2) && $student->Division === 'রাসিব') ||
            (($zeroSubjects == 1 || $zeroSubjects == 2) && $failedSubjects == 0) ||
            ($zeroSubjects == 1 && $failedSubjects == 1)
        ) {
            return 'অনিয়মিত যেমনী';
        }

        // "অনিয়মিত অন্যান্য" conditions
        elseif (
            ($failedSubjects > 2 && $student->Division === 'রাসিব') ||
            ($zeroSubjects == 2 && $failedSubjects == 1) ||
            ($zeroSubjects == 1 && $failedSubjects == 2) ||
            ($zeroSubjects == 1 && $failedSubjects > 2) ||
            ($zeroSubjects > 1 && $failedSubjects > 1) ||
            ($zeroSubjects > 2 && $student->Absence === 'অনুপস্থিত')
        ) {
            return 'অনিয়মিত অন্যান্য';
        }

        // Division = রাসিব এবং মুমতায নয় এমন হলে মানউন্নয়ন
        elseif ($student->Division !== 'রাসিব' && $student->Division !== 'মুমতায') {
            return 'মানউন্নয়ন';
        }

        return 'নিয়মিত';
    }

    /**
     * Clean up student data for response
     */
    public function cleanupStudentData($student): void
    {
        unset($student->SRType);
    }
}
