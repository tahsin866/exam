<?php

namespace App\Services\markaz\OldStudent;

use App\DTOs\markaz\OldStudent\StudentSearchDTO;
use App\Repositories\markaz\OldStudent\StudentRepository;
use App\Helpers\markaz\OldStudent\StudentTypeHelper;
use Illuminate\Database\Eloquent\Collection;

class StudentSearchService
{
    public function __construct(
        private StudentRepository $studentRepository
    ) {}

    /**
     * Search students with filtering and classification
     */
    public function searchStudents(StudentSearchDTO $searchDTO): array
    {
        // Validate user type restrictions
        $this->validateUserTypeRestrictions($searchDTO);

        // Get students from repository
        $students = $this->studentRepository->searchStudents($searchDTO);

        // Check if students match user's madrasa type
        $this->validateStudentTypes($students, $searchDTO->userMType);

        // Process and filter students
        $processedStudents = $this->processStudents($students, $searchDTO->marhalaId);

        // Check if results are empty
        if ($processedStudents->isEmpty()) {
            throw new \Exception('রেজাল্ট পাওয়া যায়নি', 404);
        }

        return $processedStudents->toArray();
    }

    /**
     * Validate user type restrictions
     */
    private function validateUserTypeRestrictions(StudentSearchDTO $searchDTO): void
    {
        // Add any user type validation logic here if needed
        // For now, this is a placeholder for future validation rules
    }

    /**
     * Validate student types against user's madrasa type
     */
    private function validateStudentTypes(Collection $students, int $userMType): void
    {
        if ($userMType == 1) { // Male madrasa
            $nonMatchingStudents = $students->where('SRType', '!=', 1);
            if ($nonMatchingStudents->isNotEmpty()) {
                throw new \Exception('পুরুষ মাদরাসায় ছাত্রীদের রোল অনুসন্ধান করা হয়েছে', 403);
            }
        } elseif ($userMType == 0) { // Female madrasa
            $nonMatchingStudents = $students->where('SRType', '!=', 0);
            if ($nonMatchingStudents->isNotEmpty()) {
                throw new \Exception('আপনি মহিলা মাদরাসা হয়ে ছাত্রদের রোল অনুসন্ধান করেছেন', 403);
            }
        }
    }

    /**
     * Process students: translate, filter, and classify
     */
    private function processStudents(Collection $students, ?int $marhalaId): Collection
    {
        $filteredStudents = collect();

        foreach ($students as $student) {
            // Translate student fields
            StudentTypeHelper::translateStudentFields($student);

            // Skip students based on division and criteria
            if (StudentTypeHelper::shouldFilterStudent($student, $marhalaId)) {
                continue;
            }

            // Classify student type if rules should be applied
            if (StudentTypeHelper::shouldApplyRules($student, $marhalaId)) {
                $this->classifyStudent($student, $marhalaId);
            } else {
                $student->student_type = 'নিয়মিত';
            }

            // Remove SRType from response
            unset($student->SRType);

            $filteredStudents->push($student);
        }

        return $filteredStudents;
    }

    /**
     * Classify student based on subject performance
     */
    private function classifyStudent(object $student, ?int $marhalaId): void
    {
        // Calculate subject statistics
        $stats = StudentTypeHelper::calculateSubjectStats($student);

        // Determine student type
        $student->student_type = StudentTypeHelper::determineStudentType($student, $stats);

        // Add debugging info
        $student->failed_subjects = $stats['failed'];
        $student->absent_subjects = $stats['absent'];
        $student->zero_subjects = $stats['zero'];

        // Add irregular subjects for irregular students
        if (in_array($student->student_type, ['অনিয়মিত যেমনী', 'অনিয়মিত অন্যান্য'])) {
            $student->irregular_subjects = StudentTypeHelper::getIrregularSubjects(
                $student,
                $student->student_type
            );
        }
    }
}
