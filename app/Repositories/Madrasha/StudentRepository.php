<?php

namespace App\Repositories\Madrasha;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StudentRepository
{
    /**
     * Find students based on search criteria
     *
     * @param array $filters
     * @param string $year
     * @return Collection
     */
    public function findStudents(array $filters, string $year = '2024'): Collection
    {
        $query = Student::query();

        if (isset($filters['marhala'])) {
            $query->where('CID', $filters['marhala']);
        }

        $query->where('years', $year);

        if (isset($filters['roll'])) {
            $query->where('Roll', $filters['roll']);
        }

        if (isset($filters['registration'])) {
            $query->where('reg_id', $filters['registration']);
        }

        return $query->select(
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
            'SubValue_1',
            'SubValue_2',
            'SubValue_3',
            'SubValue_4',
            'SubValue_5',
            'SubValue_6',
            'SubValue_7',
            'SubValue_8',
            'SubLabel_1',
            'SubLabel_2',
            'SubLabel_3',
            'SubLabel_4',
            'SubLabel_5',
            'SubLabel_6',
            'SubLabel_7',
            'SubLabel_8',
            'Absence_1',
            'Absence_2',
            'Absence_3',
            'Absence_4',
            'Absence_5',
            'Absence_6',
            'Absence_7',
            'Absence_8'
        )->get();
    }

    /**
     * Find a specific student by roll, registration ID, and CID
     *
     * @param string $roll
     * @param string $regId
     * @param string $cid
     * @return Student|null
     */
    public function findStudent(string $roll, string $regId, string $cid): ?Student
    {
        return Student::where('Roll', $roll)
            ->where('reg_id', $regId)
            ->where('CID', $cid)
            ->first();
    }

    /**
     * Get markaz info from stu_rledger_p table
     *
     * @param int $mrid
     * @return object|null
     */
    public function getMarkazInfo(int $mrid)
    {
        return DB::table('stu_rledger_p')
            ->where('MRID', $mrid)
            ->select('MDID')
            ->first();
    }
}
