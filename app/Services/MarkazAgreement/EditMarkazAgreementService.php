<?php

namespace App\Services\MarkazAgreement;

use App\Models\MarkazAgreement;
use Inertia\Inertia;

class EditMarkazAgreementService
{
    public function execute($id)
    {
        $markazAgreement = MarkazAgreement::with('associatedMadrasas')->findOrFail($id);

        $markazData = $markazAgreement->toArray();
        $markazData['noc_file_url'] = $markazAgreement->noc_file ? url('/storage/' . $markazAgreement->noc_file) : null;
        $markazData['resolution_file_url'] = $markazAgreement->resolution_file ? url('/storage/' . $markazAgreement->resolution_file) : null;
        $markazData['muhtamim_consent_url'] = $markazAgreement->muhtamim_consent ? url('/storage/' . $markazAgreement->muhtamim_consent) : null;
        $markazData['president_consent_url'] = $markazAgreement->president_consent ? url('/storage/' . $markazAgreement->president_consent) : null;
        $markazData['committee_resolution_url'] = $markazAgreement->committee_resolution ? url('/storage/' . $markazAgreement->committee_resolution) : null;

        $markazData['noc_file'] = $markazAgreement->noc_file;
        $markazData['resolution_file'] = $markazAgreement->resolution_file;
        $markazData['muhtamim_consent'] = $markazAgreement->muhtamim_consent;
        $markazData['president_consent'] = $markazAgreement->president_consent;
        $markazData['committee_resolution'] = $markazAgreement->committee_resolution;

        $associatedMadrasas = $markazAgreement->associatedMadrasas->map(function ($madrasa) {
            return [
                'id' => $madrasa->id,
                'madrasa_Name' => $madrasa->madrasa_Name,
                'madrasa_id' => $madrasa->madrasa_id,
                'fazilat' => $madrasa->fazilat,
                'sanabiya_ulya' => $madrasa->sanabiya_ulya,
                'sanabiya' => $madrasa->sanabiya,
                'mutawassita' => $madrasa->mutawassita,
                'ibtedaiyyah' => $madrasa->ibtedaiyyah,
                'hifzul_quran' => $madrasa->hifzul_quran,
                'qirat' => $madrasa->qirat,
                'noc_file' => $madrasa->noc_file,
                'noc_file_url' => $madrasa->noc_file ? url('/storage/' . $madrasa->noc_file) : null,
                'resolution_file' => $madrasa->resolution_file,
                'resolution_file_url' => $madrasa->resolution_file ? url('/storage/' . $madrasa->resolution_file) : null,
            ];
        });

        return [
            'markazData' => $markazData,
            'associatedMadrasas' => $associatedMadrasas
        ];
    }
}
