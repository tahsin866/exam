<?php

namespace App\Services\Madrasha;

use App\Helpers\TranslationHelper;
use App\Repositories\Madrasha\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentService
{
    protected $studentRepository;
    protected $translationHelper;

    public function __construct(
        StudentRepository $studentRepository,
        TranslationHelper $translationHelper
    ) {
        $this->studentRepository = $studentRepository;
        $this->translationHelper = $translationHelper;
    }

    /**
     * Search for students based on filters
     *
     * @param array $filters
     * @param \App\Models\User $user
     * @param string|null $marhalaId
     * @return array|Collection
     */
    public function searchStudents(array $filters, $user, ?string $marhalaId = null)
    {
        // Apply default year if not specified
        $year = $filters['year'] ?? '2024';

        // Get students based on search criteria
        $students = $this->studentRepository->findStudents($filters, $year);

        // Verify gender matching between school type and students
        $genderResult = $this->verifyGenderMatching($students, $user);
        if (isset($genderResult['error'])) {
            return $genderResult;
        }

        // Process and filter students
        $filteredStudents = $this->processStudents($students, $marhalaId);

        if ($filteredStudents->isEmpty()) {
            return ['error' => 'রেজাল্ট পাওয়া যায়নি', 'status' => 404];
        }

        return $filteredStudents;
    }

    /**
     * Verify that student gender matches the madrasa type
     *
     * @param Collection $students
     * @param \App\Models\User $user
     * @return array|null Returns error array if mismatch found, null otherwise
     */
    private function verifyGenderMatching(Collection $students, $user): ?array
    {
        if ($user->MType == 1) { // Male madrasa
            $nonMatchingStudents = $students->where('SRType', '!=', 1);
            if ($nonMatchingStudents->isNotEmpty()) {
                return ['error' => 'পুরুষ মাদরাসায় ছাত্রীদের রোল অনুসন্ধান করা হয়েছে', 'status' => 403];
            }
        } elseif ($user->MType == 0) { // Female madrasa
            $nonMatchingStudents = $students->where('SRType', '!=', 0);
            if ($nonMatchingStudents->isNotEmpty()) {
                return ['error' => 'আপনি মহিলা মাদরাসা হয়ে ছাত্রদের রোল অনুসন্ধান করেছেন', 'status' => 403];
            }
        }

        return null;
    }

    /**
     * Process and filter students based on rules
     *
     * @param Collection $students
     * @param string|null $marhalaId
     * @return Collection
     */
    private function processStudents(Collection $students, ?string $marhalaId): Collection
    {
        $filteredStudents = collect();

        foreach ($students as $student) {
            // Translate fields using helper
            $this->translationHelper->translateStudentFields($student);

            // Skip students with Division = রাসিব for specific CID and marhalaId combinations
            if ($this->shouldSkipStudent($student, $marhalaId)) {
                continue;
            }

            // Classify student type
            $this->classifyStudentType($student, $marhalaId);

            // Remove SRType from the response for security
            unset($student->SRType);

            $filteredStudents->push($student);
        }

        return $filteredStudents;
    }

    /**
     * Check if student should be skipped based on specific rules
     *
     * @param \App\Models\Student $student
     * @param string|null $marhalaId
     * @return bool
     */
    private function shouldSkipStudent($student, ?string $marhalaId): bool
    {
        return $student->Division == 'রাসিব' &&
               $student->years == '2024' &&
               (
                   ($student->CID == 3 && $marhalaId == 9) ||
                   ($student->CID == 4 && $marhalaId == 10) ||
                   ($student->CID == 5 && $marhalaId == 11) ||
                   ($student->CID == 6 && $marhalaId == 12)
               );
    }

    /**
     * Classify student type based on academic performance
     *
     * @param \App\Models\Student $student
     * @param string|null $marhalaId
     * @return void
     */
    public function classifyStudentType($student, ?string $marhalaId): void
    {
        // Default student type
        $student->student_type = 'নিয়মিত';

        // Check if we should apply classification rules
        if (!$this->shouldApplyClassificationRules($student, $marhalaId)) {
            return;
        }

        // Count failed, absent, and zero-mark subjects
        $subjectCounts = $this->countSubjectPerformance($student);

        // Determine student type based on rules
        if ($this->isIrregularYemeni($student, $subjectCounts)) {
            $student->student_type = 'অনিয়মিত যেমনী';
        } elseif ($this->isIrregularOther($student, $subjectCounts)) {
            $student->student_type = 'অনিয়মিত অন্যান্য';
        } elseif ($this->isQualityImprovement($student)) {
            $student->student_type = 'মানউন্নয়ন';
        }

        // Add diagnostic data
        $student->failed_subjects = $subjectCounts['failed'];
        $student->absent_subjects = $subjectCounts['absent'];
        $student->zero_subjects = $subjectCounts['zero'];
    }

    /**
     * Check if classification rules should be applied
     *
     * @param \App\Models\Student $student
     * @param string|null $marhalaId
     * @return bool
     */
    private function shouldApplyClassificationRules($student, ?string $marhalaId): bool
    {
        return
            ($marhalaId == 9 && $student->CID == 2 && $student->years == '2024') ||
            ($marhalaId == 10 && $student->CID == 3 && $student->years == '2024') ||
            ($marhalaId == 11 && $student->CID == 4 && $student->years == '2024') ||
            ($marhalaId == 12 && $student->CID == 5 && $student->years == '2024') ||
            ($marhalaId == 14 && $student->CID == 6 && $student->years == '2024');
    }

    /**
     * Count subjects with different performance metrics
     *
     * @param \App\Models\Student $student
     * @return array
     */
    private function countSubjectPerformance($student): array
    {
        $failedSubjects = 0;
        $absentSubjects = 0;
        $zeroSubjects = 0;

        for ($i = 1; $i <= 8; $i++) {
            $labelField = "SubLabel_{$i}";
            $valueField = "SubValue_{$i}";
            $absenceField = "Absence_{$i}";

            // Only count subjects that have a label (name)
            if (isset($student->$labelField) && !empty($student->$labelField)) {
                // Count subjects with marks below 33 but not 0 (ফেল)
                if (isset($student->$valueField) && $student->$valueField < 33 && $student->$valueField > 0) {
                    $failedSubjects++;
                }

                // Count subjects with 0 marks (অনু)
                if (isset($student->$valueField) && $student->$valueField === 0) {
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
     * Check if student is "অনিয়মিত যেমনী" (Irregular Yemeni)
     *
     * @param \App\Models\Student $student
     * @param array $counts
     * @return bool
     */
    private function isIrregularYemeni($student, array $counts): bool
    {
        return
            // 1. এক বা দুই বিষয়ে ফেল এবং বিভাগ রাসিব
            (($counts['failed'] == 1 || $counts['failed'] == 2) && $student->Division === 'রাসিব') ||

            // 2. এক বা দুই বিষয়ে অনু (SubValue = 0) এবং কোন বিষয়ে ফেল নেই
            (($counts['zero'] == 1 || $counts['zero'] == 2) && $counts['failed'] == 0) ||

            // 3. এক বিষয়ে অনু এবং এক বিষয়ে ফেল
            ($counts['zero'] == 1 && $counts['failed'] == 1);
    }

    /**
     * Check if student is "অনিয়মিত অন্যান্য" (Irregular Other)
     *
     * @param \App\Models\Student $student
     * @param array $counts
     * @return bool
     */
    private function isIrregularOther($student, array $counts): bool
    {
        return
            // 1. দুয়ের অধিক বিষয়ে ফেল এবং বিভাগ রাসিব
            ($counts['failed'] > 2 && $student->Division === 'রাসিব') ||

            // 2. দুই বিষয় অনু এক বিষয়ে ফেল বা এক বিষয়ে অনু দুই বিষয়ে ফেল
            ($counts['zero'] == 2 && $counts['failed'] == 1) ||
            ($counts['zero'] == 1 && $counts['failed'] == 2) ||

            // 3. এক বিষয়ে অনু এবং দুয়ের অধিক বিষয়ে ফেল
            ($counts['zero'] == 1 && $counts['failed'] > 2) ||

            // 4. একের অধিক অনু একের অধিক ফেল
            ($counts['zero'] > 1 && $counts['failed'] > 1) ||

            // 5. দুয়ের অধিক অনু বিভাগ অনু
            ($counts['zero'] > 2 && $student->Absence === 'অনুপস্থিত');
    }

    /**
     * Check if student is "মানউন্নয়ন" (Quality Improvement)
     *
     * @param \App\Models\Student $student
     * @return bool
     */
    private function isQualityImprovement($student): bool
    {
        return $student->Division !== 'রাসিব' && $student->Division !== 'মুমতায';
    }

    /**
     * Get student data for edit form
     *
     * @param Request $request
     * @return array
     */
    public function getStudentDataForEdit(Request $request): array
    {
        if ($request->has('data')) {
            try {
                $decodedData = base64_decode($request->data);
                list($roll, $reg_id, $CID) = explode(':', $decodedData);

                return [
                    'roll' => $roll,
                    'reg_id' => $reg_id,
                    'CID' => $CID
                ];
            } catch (\Exception $e) {
                Log::error('Error decoding student data: ' . $e->getMessage());
                return [
                    'error' => 'Invalid student data'
                ];
            }
        } else {
            return [
                'roll' => $request->roll,
                'reg_id' => $request->reg_id,
                'CID' => $request->CID
            ];
        }
    }

    /**
     * Get detailed student information for editing
     *
     * @param string $roll
     * @param string $regId
     * @param string $cid
     * @param string|null $marhalaId
     * @return array|null
     */
    public function getStudentDetailsForEdit(string $roll, string $regId, string $cid, ?string $marhalaId): ?array
    {
        $student = $this->studentRepository->findStudent($roll, $regId, $cid);

        if (!$student) {
            return null;
        }

        // Get the markaz name
        $markazName = $student->Markaj;

        $markazFromRledger = $this->studentRepository->getMarkazInfo($student->MID);
        if ($markazFromRledger) {
            $markazName = $student->Markaj;
        }

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

        // Determine student type and add irregular subjects info
        $this->classifyStudentType($student, $marhalaId);
        $response['currentExam']['student_type'] = $student->student_type ?? 'নিয়মিত';
        $response['currentExam']['irregular_subjects'] = $this->getIrregularSubjects($student);

        return $response;
    }

    /**
     * Get irregular subjects for a student
     *
     * @param \App\Models\Student $student
     * @return string
     */
    private function getIrregularSubjects($student): string
    {
        $studentType = $student->student_type ?? '';
        $irregularSubjects = [];

        // Only process if student is irregular
        if ($studentType === 'অনিয়মিত যেমনী' || $studentType === 'অনিয়মিত অন্যান্য') {
            // If student type is "অনিয়মিত অন্যান্য", show all subjects
            if ($studentType === 'অনিয়মিত অন্যান্য') {
                for ($i = 1; $i <= 8; $i++) {
                    $labelField = "SubLabel_{$i}";
                    if (isset($student->$labelField) && !empty($student->$labelField)) {
                        $irregularSubjects[] = $student->$labelField;
                    }
                }
            } else {
                // For "অনিয়মিত যেমনী", only show failed or zero-mark subjects
                for ($i = 1; $i <= 8; $i++) {
                    $labelField = "SubLabel_{$i}";
                    $valueField = "SubValue_{$i}";

                    if (isset($student->$labelField) && !empty($student->$labelField)) {
                        if (
                            (isset($student->$valueField) && $student->$valueField < 33) ||
                            (isset($student->$valueField) && $student->$valueField === 0)
                        ) {
                            $irregularSubjects[] = $student->$labelField;
                        }
                    }
                }
            }
        }

        return implode(', ', $irregularSubjects);
    }
}
