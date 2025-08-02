<?php

namespace App\Repositories\admin\markaz;

use App\Models\MarkazAgreement;

interface AdminMarkazRepositoryInterface
{
    public function findById(int $id): ?MarkazAgreement;
    public function updateAgreement(MarkazAgreement $agreement, array $data): bool;
    public function getAgreementWithRelations(int $id): ?MarkazAgreement;
}
