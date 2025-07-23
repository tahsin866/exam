<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkazAgreementMadrasa extends Model
{
    protected $fillable = [
        'markaz_agreement_id',
        'madrasa_id',
        'madrasa_Name',
        'fazilat',
        'sanabiya_ulya',
        'sanabiya',
        'mutawassita',
        'ibtedaiyyah',
        'hifzul_quran',
        'qirat',
        'noc_file',
        'resolution_file'
    ];

    public function markazAgreement()
    {
        return $this->belongsTo(MarkazAgreement::class);
    }
}
