<?php

namespace App\Http\Resources\Madrasha\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamFeeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ExamFeeResource::collection($this->collection),
            'meta' => [
                'exam_name' => $this->additional['examName'] ?? null,
                'total_count' => $this->collection->count(),
            ],
        ];
    }
}
