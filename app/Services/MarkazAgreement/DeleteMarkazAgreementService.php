<?php

namespace App\Services\MarkazAgreement;

use App\Models\MarkazAgreement;

class DeleteMarkazAgreementService
{
    public function execute($id)
    {
        $agreement = MarkazAgreement::find($id);
        if (!$agreement) {
            return ['error' => 'আবেদন পাওয়া যায়নি!'];
        }
        $agreement->delete();
        return ['success' => 'আবেদন সফলভাবে মুছে ফেলা হয়েছে!'];
    }
}
