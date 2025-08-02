<?php

namespace App\Services\StudentRegistration;

use App\Models\Student;
use App\Services\Translation\TranslatorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class OldStudentSearchService
{
    protected $translator;

    public function __construct(TranslatorService $translator)
    {
        $this->translator = $translator;
    }

    public function search(array $validatedData, ?string $marhalaId): Collection
    {
        $user = Auth::user();

        // Build query
        $query = Student::query()
            ->select([
                'Name', 'Madrasha', 'Father', 'DateofBirth', 'Class',
                'Markaj', 'Division', 'CID', 'years', 'Roll', 'reg_id',
                'Absence', 'SRType',
                'SubValue_1', 'SubValue_2', 'SubValue_3', 'SubValue_4',
                'SubValue_5', 'SubValue_6', 'SubValue_7', 'SubValue_8',
                'SubLabel_1', 'SubLabel_2', 'SubLabel_3', 'SubLabel_4',
                'SubLabel_5', 'SubLabel_6', 'SubLabel_7', 'SubLabel_8',
                'Absence_1', 'Absence_2', 'Absence_3', 'Absence_4',
                'Absence_5', 'Absence_6', 'Absence_7', 'Absence_8',
            ]);

        // Apply filters
        $this->applyFilters($query, $validatedData);

        $students = $query->get();

        // Validate gender match
        $this->validateGenderMatch($students, $user->MType);

        // Process students
        return $this->processStudents($students, $marhalaId);
    }

    protected function applyFilters($query, array $validatedData): void
    {
        if (!empty($validatedData['marhala'])) {
            $query->where('CID', $validatedData['marhala']);
        }

        $year = $validatedData['year'] ?? '2024';
        $query->where('years', $year);

        if (!empty($validatedData['roll'])) {
            $query->where('Roll', $validatedData['roll']);
        }

        if (!empty($validatedData['registration'])) {
            $query->where('reg_id', $validatedData['registration']);
        }
    }

    protected function validateGenderMatch(Collection $students, int $mType): void
    {
        if ($mType === 1) { // Male madrasa
            $nonMatching = $students->where('SRType', '!=', 1);
            if ($nonMatching->isNotEmpty()) {
                throw new \Exception('পুরুষ মাদরাসায় ছাত্রীদের রোল অনুসন্ধান করা হয়েছে', 403);
            }
        } elseif ($mType === 0) { // Female madrasa
            $nonMatching = $students->where('SRType', '!=', 0);
            if ($nonMatching->isNotEmpty()) {
                throw new \Exception('আপনি মহিলা মাদরাসা হয়ে ছাত্রদের রোল অনুসন্ধান করেছেন', 403);
            }
        }
    }

    protected function processStudents(Collection $students, ?string $marhalaId): Collection
    {
        $filteredStudents = collect();
        $marhalaCidMap = config('student_registration.marhala_cid_map');

        foreach ($students as $student) {
            // Translate fields
            $this->translateStudentFields($student);

            // Skip students with specific conditions
            if ($this->shouldSkipStudent($student, $marhalaId, $marhalaCidMap)) {
                continue;
            }

            // Apply classification rules
            $this->applyClassificationRules($student, $marhalaId, $marhalaCidMap);

            // Remove SRType from response
            unset($student->SRType);

            $filteredStudents->push($student);
        }

        return $filteredStudents;
    }

    protected function translateStudentFields($student): void
    {
        $fieldsToTranslate = ['Madrasha', 'Name', 'Father', 'Division', 'Class', 'Markaj', 'DateofBirth'];
        foreach ($fieldsToTranslate as $field) {
            $student->$field = $this->translator->translate($student->$field);
        }
    }

    protected function shouldSkipStudent($student, ?string $marhalaId, array $marhalaCidMap): bool
    {
        return $student->Division === 'রাসিব' &&
               $student->years === '2024' &&
               isset($marhalaCidMap[$marhalaId]) &&
               $student->CID == $marhalaCidMap[$marhalaId];
    }

    protected function applyClassificationRules($student, ?string $marhalaId, array $marhalaCidMap): void
    {
        $shouldApplyRules = isset($marhalaCidMap[$marhalaId]) &&
                           $student->CID == $marhalaCidMap[$marhalaId] &&
                           $student->years === '2024';

        if (!$shouldApplyRules) {
            $student->student_type = 'নিয়মিত';
            return;
        }

        $counts = $this->calculateSubjectCounts($student);

        // Apply classification rules
        if (
            ($counts['failed'] == 1 || $counts['failed'] == 2) && $student->Division === 'রাসিব' ||
            ($counts['zero'] == 1 || $counts['zero'] == 2) && $counts['failed'] == 0 ||
            ($counts['zero'] == 1 && $counts['failed'] == 1)
        ) {
            $student->student_type = 'অনিয়মিত যেমনী';
        } elseif (
            ($counts['failed'] > 2 && $student->Division === 'রাসিব') ||
            ($counts['zero'] == 2 && $counts['failed'] == 1) ||
            ($counts['zero'] == 1 && $counts['failed'] == 2) ||
            ($counts['zero'] == 1 && $counts['failed'] > 2) ||
            ($counts['zero'] > 1 && $counts['failed'] > 1) ||
            ($counts['zero'] > 2 && $student->Absence === 'অনুপস্থিত')
        ) {
            $student->student_type = 'অনিয়মিত অন্যান্য';
        } elseif ($student->Division !== 'রাসিব' && $student->Division !== 'মুমতায') {
            $student->student_type = 'মানউন্নয়ন';
        } else {
            $student->student_type = 'নিয়মিত';
        }

        // Add debugging info
        $student->failed_subjects = $counts['failed'];
        $student->absent_subjects = $counts['absent'];
        $student->zero_subjects = $counts['zero'];
    }

    protected function calculateSubjectCounts($student): array
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

            if (isset($student->$labelField) && !empty($student->$labelField)) {
                if (isset($student->$field) && $student->$field < 33 && $student->$field > 0) {
                    $failedSubjects++;
                }
                if (isset($student->$field) && $student->$field === 0) {
                    $zeroSubjects++;
                }
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
}
