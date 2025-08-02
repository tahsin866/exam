<?php

namespace App\DTOs\markaz\OldStudent;

class StudentSearchDTO
{
    public function __construct(
        public readonly ?int $marhala = null,
        public readonly string $year = '2024',
        public readonly ?string $roll = null,
        public readonly ?string $registration = null,
        public readonly ?int $marhalaId = null,
        public readonly int $userMType = 1
    ) {}

    public static function fromRequest(array $data, ?int $marhalaId = null, int $userMType = 1): self
    {
        return new self(
            marhala: isset($data['marhala']) ? (int) $data['marhala'] : null,
            year: $data['year'] ?? '2024',
            roll: $data['roll'] ?? null,
            registration: $data['registration'] ?? null,
            marhalaId: $marhalaId,
            userMType: $userMType
        );
    }

    public function hasFilters(): bool
    {
        return !is_null($this->marhala) ||
               !is_null($this->roll) ||
               !is_null($this->registration);
    }
}
