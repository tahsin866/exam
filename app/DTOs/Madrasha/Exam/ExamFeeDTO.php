<?php

namespace App\DTOs\Madrasha\Exam;

class ExamFeeDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $marhalaId,
        public readonly string $marhalaNameBn,
        public readonly string $examSetupName,
        public readonly ?string $regularStartDate,
        public readonly ?string $regularEndDate,
        public readonly ?float $regularFee,
        public readonly ?string $lateStartDate,
        public readonly ?string $lateEndDate,
        public readonly ?float $lateFee,
        public readonly int $regularStudents = 0,
        public readonly int $irregularStudents = 0,
    ) {}

    /**
     * Create DTO from array
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            marhalaId: $data['marhala_id'],
            marhalaNameBn: $data['marhala_name_bn'],
            examSetupName: $data['exam_setup_name'],
            regularStartDate: $data['regularStartDate'] ?? null,
            regularEndDate: $data['regularEndDate'] ?? null,
            regularFee: $data['regularFee'] ?? null,
            lateStartDate: $data['lateStartDate'] ?? null,
            lateEndDate: $data['lateEndDate'] ?? null,
            lateFee: $data['lateFee'] ?? null,
            regularStudents: $data['regularStudents'] ?? 0,
            irregularStudents: $data['irregularStudents'] ?? 0,
        );
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'marhala_id' => $this->marhalaId,
            'marhala_name_bn' => $this->marhalaNameBn,
            'exam_setup_name' => $this->examSetupName,
            'regularStartDate' => $this->regularStartDate,
            'regularEndDate' => $this->regularEndDate,
            'regularFee' => $this->regularFee,
            'lateStartDate' => $this->lateStartDate,
            'lateEndDate' => $this->lateEndDate,
            'lateFee' => $this->lateFee,
            'regularStudents' => $this->regularStudents,
            'irregularStudents' => $this->irregularStudents,
        ];
    }
}
