<?php

namespace App\Helpers\markaz\OldStudent;

use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;

class StudentTypeHelper
{
    private static ?Translator $translator = null;

    /**
     * Get translator instance
     */
    private static function getTranslator(): Translator
    {
        if (self::$translator === null) {
            self::$translator = new Translator();
        }
        return self::$translator;
    }

    /**
     * Translate student fields from Bijoy to Avro
     */
    public static function translateStudentFields(object $student): void
    {
        $translator = self::getTranslator();

        $student->Madrasha = $translator->translate($student->Madrasha);
        $student->Name = $translator->translate($student->Name);
        $student->Father = $translator->translate($student->Father);
        $student->Division = $translator->translate($student->Division);
        $student->Class = $translator->translate($student->Class);
        $student->Markaj = $translator->translate($student->Markaj);
        $student->DateofBirth = $translator->translate($student->DateofBirth);
    }

    /**
     * Check if student should be filtered based on division and other criteria
     */
    public static function shouldFilterStudent(object $student, ?int $marhalaId): bool
    {
        // Skip students with Division = রাসিব for specific CID and marhalaId combinations
        return $student->Division == 'রাসিব' &&
               $student->years == '2024' &&
               self::isSpecificCidMarhalaCombo($student->CID, $marhalaId);
    }

    /**
     * Check if CID and marhalaId combination requires special filtering
     */
    private static function isSpecificCidMarhalaCombo(int $cid, ?int $marhalaId): bool
    {
        $combinations = [
            3 => 9,
            4 => 10,
            5 => 11,
            6 => 12
        ];

        return isset($combinations[$cid]) && $combinations[$cid] === $marhalaId;
    }

    /**
     * Check if student rules should be applied
     */
    public static function shouldApplyRules(object $student, ?int $marhalaId): bool
    {
        $validCombinations = [
            ['marhalaId' => 9, 'cid' => 2, 'year' => '2024'],
            ['marhalaId' => 10, 'cid' => 3, 'year' => '2024'],
            ['marhalaId' => 11, 'cid' => 4, 'year' => '2024'],
            ['marhalaId' => 12, 'cid' => 5, 'year' => '2024'],
            ['marhalaId' => 14, 'cid' => 6, 'year' => '2024']
        ];

        foreach ($validCombinations as $combo) {
            if ($marhalaId == $combo['marhalaId'] &&
                $student->CID == $combo['cid'] &&
                $student->years == $combo['year']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate subject statistics for student classification
     */
    public static function calculateSubjectStats(object $student): array
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
     * Determine student type based on subject statistics and division
     */
    public static function determineStudentType(object $student, array $stats): string
    {
        $failedSubjects = $stats['failed'];
        $absentSubjects = $stats['absent'];
        $zeroSubjects = $stats['zero'];

        // "অনিয়মিত যেমনী" conditions
        if (self::isIrregularSame($student, $failedSubjects, $zeroSubjects)) {
            return 'অনিয়মিত যেমনী';
        }

        // "অনিয়মিত অন্যান্য" conditions
        if (self::isIrregularOther($student, $failedSubjects, $zeroSubjects)) {
            return 'অনিয়মিত অন্যান্য';
        }

        // Division = রাসিব এবং মুমতায নয় এমন হলে মানউন্নয়ন
        if ($student->Division !== 'রাসিব' && $student->Division !== 'মুমতায') {
            return 'মানউন্নয়ন';
        }

        return 'নিয়মিত';
    }

    /**
     * Check if student qualifies as "অনিয়মিত যেমনী"
     */
    private static function isIrregularSame(object $student, int $failedSubjects, int $zeroSubjects): bool
    {
        return (
            // 1. এক বা দুই বিষয়ে ফেল এবং বিভাগ রাসিব
            (($failedSubjects == 1 || $failedSubjects == 2) && $student->Division === 'রাসিব') ||
            // 2. এক বা দুই বিষয়ে অনু (SubValue = 0) এবং কোন বিষয়ে ফেল নেই
            (($zeroSubjects == 1 || $zeroSubjects == 2) && $failedSubjects == 0) ||
            // 3. এক বিষয়ে অনু এবং এক বিষয়ে ফেল
            ($zeroSubjects == 1 && $failedSubjects == 1)
        );
    }

    /**
     * Check if student qualifies as "অনিয়মিত অন্যান্য"
     */
    private static function isIrregularOther(object $student, int $failedSubjects, int $zeroSubjects): bool
    {
        return (
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
        );
    }

    /**
     * Get irregular subjects for display
     */
    public static function getIrregularSubjects(object $student, string $studentType): string
    {
        if ($studentType !== 'অনিয়মিত যেমনী' && $studentType !== 'অনিয়মিত অন্যান্য') {
            return '';
        }

        $subjectFields = [
            'SubValue_1', 'SubValue_2', 'SubValue_3', 'SubValue_4',
            'SubValue_5', 'SubValue_6', 'SubValue_7', 'SubValue_8'
        ];

        // If student type is "অনিয়মিত অন্যান্য", show all subjects
        if ($studentType === 'অনিয়মিত অন্যান্য') {
            $allSubjects = [];
            foreach ($subjectFields as $index => $field) {
                $labelField = 'SubLabel_' . ($index + 1);
                if (isset($student->$labelField) && !empty($student->$labelField)) {
                    $allSubjects[] = $student->$labelField;
                }
            }
            return implode(', ', $allSubjects);
        }

        // For "অনিয়মিত যেমনী", only show failed or zero-mark subjects
        $irregularSubjects = [];
        foreach ($subjectFields as $index => $field) {
            $labelField = 'SubLabel_' . ($index + 1);

            if (isset($student->$labelField) && !empty($student->$labelField)) {
                if ((isset($student->$field) && $student->$field < 33) ||
                    (isset($student->$field) && $student->$field === 0)) {
                    $irregularSubjects[] = $student->$labelField;
                }
            }
        }

        return implode(', ', $irregularSubjects);
    }
}
