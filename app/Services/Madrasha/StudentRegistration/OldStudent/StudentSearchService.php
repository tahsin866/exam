<?php

namespace App\Services\madrasha\StudentRegistration\OldStudent;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;

class StudentSearchService
{
    private Translator $translator;

    public function __construct()
    {
        $this->translator = new Translator();
    }

    /**
     * Search students based on filters
     */
    public function searchStudents(array $filters): Collection
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('অনুগ্রহ করে লগইন করুন');
        }

        // Build query
        $students = $this->buildQuery($filters)->get();

        // Validate gender compatibility
        $this->validateGenderCompatibility($user, $students);

        // Process and filter students
        $filteredStudents = $this->processStudents($students, $filters['marhalaId'] ?? null);

        if ($filteredStudents->isEmpty()) {
            throw new \Exception('রেজাল্ট পাওয়া যায়নি');
        }

        return $filteredStudents;
    }

    /**
     * Build the query based on filters
     */
    private function buildQuery(array $filters)
    {
        $query = Student::query();

        if (!empty($filters['marhala'])) {
            $query->where('CID', $filters['marhala']);
        }

        $query->where('years', $filters['year']);

        if (!empty($filters['roll'])) {
            $query->where('Roll', $filters['roll']);
        }

        if (!empty($filters['registration'])) {
            $query->where('reg_id', $filters['registration']);
        }

        return $query->select([
            'Name', 'Madrasha', 'Father', 'DateofBirth', 'Class',
            'Markaj', 'Division', 'CID', 'years', 'Roll', 'reg_id',
            'Absence', 'SRType'
        ]);
    }

    /**
     * Validate gender compatibility between user and students
     */
    private function validateGenderCompatibility($user, Collection $students): void
    {
        if ($user->MType == 1) { // Male madrasa
            $nonMatchingStudents = $students->where('SRType', '!=', 1);
            if ($nonMatchingStudents->isNotEmpty()) {
                throw new \Exception('পুরুষ মাদরাসায় ছাত্রীদের রোল অনুসন্ধান করা হয়েছে');
            }
        } elseif ($user->MType == 0) { // Female madrasa
            $nonMatchingStudents = $students->where('SRType', '!=', 0);
            if ($nonMatchingStudents->isNotEmpty()) {
                throw new \Exception('আপনি মহিলা মাদরাসা হয়ে ছাত্রদের রোল অনুসন্ধান করেছেন');
            }
        }
    }

    /**
     * Process students - translate and filter
     */
    private function processStudents(Collection $students, ?string $marhalaId): Collection
    {
        $filteredStudents = collect();

        foreach ($students as $student) {
            // Translate Bengali fields
            $this->translateStudentFields($student);

            // Apply special filtering rules
            if ($this->shouldSkipStudent($student, $marhalaId)) {
                continue;
            }

            // Determine student type
            $this->determineStudentType($student, $marhalaId);

            // Remove sensitive data
            unset($student->SRType);

            $filteredStudents->push($student);
        }

        return $filteredStudents;
    }

    /**
     * Translate Bengali fields
     */
    private function translateStudentFields($student): void
    {
        $student->Madrasha = $this->translator->translate($student->Madrasha);
        $student->Name = $this->translator->translate($student->Name);
        $student->Father = $this->translator->translate($student->Father);
        $student->Division = $this->translator->translate($student->Division);
        $student->Class = $this->translator->translate($student->Class);
        $student->Markaj = $this->translator->translate($student->Markaj);
        $student->DateofBirth = $this->translator->translate($student->DateofBirth);
    }

    /**
     * Check if student should be skipped based on special conditions
     */
    private function shouldSkipStudent($student, ?string $marhalaId): bool
    {
        return $student->Division == 'রাসিব' &&
               $student->years == '2024' && (
                   ($student->CID == 3 && $marhalaId == 9) ||
                   ($student->CID == 4 && $marhalaId == 10) ||
                   ($student->CID == 5 && $marhalaId == 11) ||
                   ($student->CID == 6 && $marhalaId == 12)
               );
    }

    /**
     * Determine student type based on business rules
     */
    private function determineStudentType($student, ?string $marhalaId): void
    {
        $student->student_type = 'নিয়মিত'; // Default

        if (!$this->shouldApplyClassificationRules($student, $marhalaId)) {
            return;
        }

        $subjectAnalysis = $this->analyzeSubjects($student);
        $student->student_type = $this->classifyStudent($student, $subjectAnalysis);

        // Add debug info
        $student->failed_subjects = $subjectAnalysis['failedSubjects'];
        $student->absent_subjects = $subjectAnalysis['absentSubjects'];
        $student->zero_subjects = $subjectAnalysis['zeroSubjects'];
    }

    /**
     * Check if classification rules should apply
     */
    private function shouldApplyClassificationRules($student, ?string $marhalaId): bool
    {
        return ($marhalaId == 9 && $student->CID == 2 && $student->years == '2024') ||
               ($marhalaId == 10 && $student->CID == 3 && $student->years == '2024') ||
               ($marhalaId == 11 && $student->CID == 4 && $student->years == '2024') ||
               ($marhalaId == 12 && $student->CID == 5 && $student->years == '2024') ||
               ($marhalaId == 14 && $student->CID == 6 && $student->years == '2024');
    }

    /**
     * Analyze student's subject performance
     */
    private function analyzeSubjects($student): array
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
                // Count failed subjects (marks below 33 but not 0)
                if (isset($student->$field) && $student->$field < 33 && $student->$field > 0) {
                    $failedSubjects++;
                }

                // Count zero-mark subjects
                if (isset($student->$field) && $student->$field === 0) {
                    $zeroSubjects++;
                }

                // Count absent subjects
                if (isset($student->$absenceField) && $student->$absenceField === 'অনুপস্থিত') {
                    $absentSubjects++;
                }
            }
        }

        return compact('failedSubjects', 'absentSubjects', 'zeroSubjects');
    }

    /**
     * Classify student based on performance analysis
     */
    private function classifyStudent($student, array $analysis): string
    {
        $failedSubjects = $analysis['failedSubjects'];
        $absentSubjects = $analysis['absentSubjects'];
        $zeroSubjects = $analysis['zeroSubjects'];

        // "অনিয়মিত যেমনী" conditions
        if ($this->isIrregularSameLevel($student, $failedSubjects, $zeroSubjects)) {
            return 'অনিয়মিত যেমনী';
        }

        // "অনিয়মিত অন্যান্য" conditions
        if ($this->isIrregularOther($student, $failedSubjects, $zeroSubjects)) {
            return 'অনিয়মিত অন্যান্য';
        }

        // "মানউন্নয়ন" condition
        if ($student->Division !== 'রাসিব' && $student->Division !== 'মুমতায') {
            return 'মানউন্নয়ন';
        }

        return 'নিয়মিত';
    }

    /**
     * Check if student is "অনিয়মিত যেমনী"
     */
    private function isIrregularSameLevel($student, int $failedSubjects, int $zeroSubjects): bool
    {
        return (($failedSubjects == 1 || $failedSubjects == 2) && $student->Division === 'রাসিব') ||
               (($zeroSubjects == 1 || $zeroSubjects == 2) && $failedSubjects == 0) ||
               ($zeroSubjects == 1 && $failedSubjects == 1);
    }

    /**
     * Check if student is "অনিয়মিত অন্যান্য"
     */
    private function isIrregularOther($student, int $failedSubjects, int $zeroSubjects): bool
    {
        return ($failedSubjects > 2 && $student->Division === 'রাসিব') ||
               ($zeroSubjects == 2 && $failedSubjects == 1) ||
               ($zeroSubjects == 1 && $failedSubjects == 2) ||
               ($zeroSubjects == 1 && $failedSubjects > 2) ||
               ($zeroSubjects > 1 && $failedSubjects > 1) ||
               ($zeroSubjects > 2 && $student->Absence === 'অনুপস্থিত');
    }
}
