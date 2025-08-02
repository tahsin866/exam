<?php

namespace App\Services\MarkazAgreement;

use App\Models\MarkazAgreement;
use App\Models\MarkazAgreementMadrasa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UpdateMarkazAgreementService
{
    public function execute($request, $id)
    {
        $markazAgreement = MarkazAgreement::findOrFail($id);

        $markazAgreement->update([
            'madrasha_Name' => $request->madrasha_Name,
            'markaz_type' => $request->markaz_type,
            'fazilat' => $request->fazilat,
            'sanabiya_ulya' => $request->sanabiya_ulya,
            'sanabiya' => $request->sanabiya,
            'mutawassita' => $request->mutawassita,
            'ibtedaiyyah' => $request->ibtedaiyyah,
            'hifzul_quran' => $request->hifzul_quran,
            'qirat' => $request->qirat,
            'requirements' => $request->requirements,
        ]);

        $fileFields = [
            'noc_file' => 'markaz/noc',
            'resolution_file' => 'markaz/resolution',
            'muhtamim_consent' => 'markaz/consent',
            'president_consent' => 'markaz/consent',
            'committee_resolution' => 'markaz/consent'
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                if ($markazAgreement->$field) {
                    Storage::delete($markazAgreement->$field);
                }
                $markazAgreement->$field = $request->file($field)->store($path, 'public');
            }
        }

        $markazAgreement->save();

        $existingMadrasaIds = MarkazAgreementMadrasa::where('markaz_agreement_id', $id)
            ->pluck('id')
            ->toArray();
        $updatedMadrasaIds = [];

        foreach ($request->associated_madrasas as $madrasaData) {
            if (isset($madrasaData['id'])) {
                $associatedMadrasa = MarkazAgreementMadrasa::find($madrasaData['id']);
                $updatedMadrasaIds[] = $madrasaData['id'];
            } else {
                $associatedMadrasa = new MarkazAgreementMadrasa();
                $associatedMadrasa->markaz_agreement_id = $id;
            }
            $associatedMadrasa->madrasa_Name = $madrasaData['madrasa_Name'];
            $associatedMadrasa->madrasa_id = $madrasaData['madrasa_id'] ?? null;
            $associatedMadrasa->fazilat = $madrasaData['fazilat'];
            $associatedMadrasa->sanabiya_ulya = $madrasaData['sanabiya_ulya'];
            $associatedMadrasa->sanabiya = $madrasaData['sanabiya'];
            $associatedMadrasa->mutawassita = $madrasaData['mutawassita'];
            $associatedMadrasa->ibtedaiyyah = $madrasaData['ibtedaiyyah'];
            $associatedMadrasa->hifzul_quran = $madrasaData['hifzul_quran'];
            $associatedMadrasa->qirat = $madrasaData['qirat'];

            if (isset($madrasaData['noc_file']) && $madrasaData['noc_file'] instanceof UploadedFile) {
                if ($associatedMadrasa->noc_file) {
                    Storage::delete($associatedMadrasa->noc_file);
                }
                $associatedMadrasa->noc_file = $madrasaData['noc_file']->store('markaz/associated/noc', 'public');
            }

            if (isset($madrasaData['resolution_file']) && $madrasaData['resolution_file'] instanceof UploadedFile) {
                if ($associatedMadrasa->resolution_file) {
                    Storage::delete($associatedMadrasa->resolution_file);
                }
                $associatedMadrasa->resolution_file = $madrasaData['resolution_file']->store('markaz/associated/resolution', 'public');
            }

            $associatedMadrasa->save();

            if (!isset($madrasaData['id'])) {
                $updatedMadrasaIds[] = $associatedMadrasa->id;
            }
        }

        $madrasasToDelete = array_diff($existingMadrasaIds, $updatedMadrasaIds);
        MarkazAgreementMadrasa::whereIn('id', $madrasasToDelete)->delete();
    }
}
