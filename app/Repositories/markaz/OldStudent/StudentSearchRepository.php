<?php

namespace App\Repositories\markaz\OldStudent;

use App\Models\student;
use App\DTOs\markaz\OldStudent\StudentSearchDTO;
use Illuminate\Database\Eloquent\Collection;

class StudentSearchRepository
{
    /**
     * Search students based on provided criteria
     */
    public function searchStudents(StudentSearchDTO $searchDTO): Collection
    {
        $query = Student::query();

        // Apply filters
        if ($searchDTO->marhala) {
            $query->where('CID', $searchDTO->marhala);
        }

        $query->where('years', $searchDTO->year);

        if ($searchDTO->roll) {
            $query->where('Roll', $searchDTO->roll);
        }

        if ($searchDTO->registration) {
            $query->where('reg_id', $searchDTO->registration);
        }

        // Get the results with specific fields
        return $query->select([
            'Name',
            'Madrasha',
            'Father',
            'DateofBirth',
            'Class',
            'Markaj',
            'Division',
            'CID',
            'years',
            'Roll',
            'reg_id',
            'Absence',
            'SRType',
            'SubValue_1', 'SubValue_2', 'SubValue_3', 'SubValue_4',
            'SubValue_5', 'SubValue_6', 'SubValue_7', 'SubValue_8',
            'SubLabel_1', 'SubLabel_2', 'SubLabel_3', 'SubLabel_4',
            'SubLabel_5', 'SubLabel_6', 'SubLabel_7', 'SubLabel_8',
            'Absence_1', 'Absence_2', 'Absence_3', 'Absence_4',
            'Absence_5', 'Absence_6', 'Absence_7', 'Absence_8'
        ])->get();
    }
}
