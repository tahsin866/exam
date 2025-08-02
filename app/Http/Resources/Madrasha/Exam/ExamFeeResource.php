<?php

namespace App\Http\Resources\Madrasha\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamFeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'],
            'marhala_id' => $this->resource['marhala_id'],
            'marhala_name_bn' => $this->resource['marhala_name_bn'],
            'exam_setup_name' => $this->resource['exam_setup_name'],
            'regular_period' => [
                'start_date' => $this->resource['regularStartDate'],
                'end_date' => $this->resource['regularEndDate'],
                'fee' => $this->resource['regularFee'],
            ],
            'late_period' => [
                'start_date' => $this->resource['lateStartDate'],
                'end_date' => $this->resource['lateEndDate'],
                'fee' => $this->resource['lateFee'],
            ],
            'student_counts' => [
                'regular' => $this->resource['regularStudents'],
                'irregular' => $this->resource['irregularStudents'],
            ],
        ];
    }
}
