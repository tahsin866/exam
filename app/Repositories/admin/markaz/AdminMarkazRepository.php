<?php

namespace App\Repositories\admin\markaz;

use App\Models\MarkazAgreement;
use App\Repositories\admin\markaz\AdminMarkazRepositoryInterface;

class AdminMarkazRepository implements AdminMarkazRepositoryInterface
{
    public function findById(int $id): ?MarkazAgreement
    {
        return MarkazAgreement::find($id);
    }

    public function updateAgreement(MarkazAgreement $agreement, array $data): bool
    {
        return $agreement->update($data);
    }

    public function getAgreementWithRelations(int $id): ?MarkazAgreement
    {
        return MarkazAgreement::with([
            'associatedMadrasas',
            'mainMadrasa:id,MName',
            'examSetup:id,exam_name',
            'creator.roles',
            'approver.roles',
            'logs.user.roles'
        ])->find($id);
    }
}
