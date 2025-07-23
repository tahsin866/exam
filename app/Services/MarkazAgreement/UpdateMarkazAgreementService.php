<?php

namespace App\Services\MarkazAgreement;

use App\Models\MarkazAgreement;
use App\Models\MarkazAgreementMadrasa;
use Illuminate\Support\Facades\Auth;

class UpdateMarkazAgreementService
{
    public function update($request, $id)
    {
        $markazAgreement = MarkazAgreement::findOrFail($id);

        // Basic information
        $markazAgreement->madrasha_id = Auth::user()->madrasha_id;
        $markazAgreement->application_type = $request->application_type;
        $markazAgreement->markaz_name = $request->markaz_name;
        $markazAgreement->establishment_date = $request->establishment_date;
        $markazAgreement->total_students = $request->total_students;

        // Address
        $markazAgreement->address = $request->address;
        $markazAgreement->district = $request->district;
        $markazAgreement->thana = $request->thana;

        // File handling for main files
        if ($request->hasFile('noc_file') && $request->file('noc_file') instanceof \Illuminate\Http\UploadedFile) {
            $markazAgreement->noc_file = $request->file('noc_file')->store('markaz/noc', 'public');
        }

        if ($request->hasFile('resolution_file') && $request->file('resolution_file') instanceof \Illuminate\Http\UploadedFile) {
            $markazAgreement->resolution_file = $request->file('resolution_file')->store('markaz/resolution', 'public');
        }

        // Requirements
        $markazAgreement->requirements = $request->requirements;

        // Consent files
        if ($request->hasFile('muhtamim_consent') && $request->file('muhtamim_consent') instanceof \Illuminate\Http\UploadedFile) {
            $markazAgreement->muhtamim_consent = $request->file('muhtamim_consent')->store('markaz/consent', 'public');
        }

        if ($request->hasFile('president_consent') && $request->file('president_consent') instanceof \Illuminate\Http\UploadedFile) {
            $markazAgreement->president_consent = $request->file('president_consent')->store('markaz/consent', 'public');
        }

        if ($request->hasFile('committee_resolution') && $request->file('committee_resolution') instanceof \Illuminate\Http\UploadedFile) {
            $markazAgreement->committee_resolution = $request->file('committee_resolution')->store('markaz/consent', 'public');
        }

        $markazAgreement->save();

        // Handle associated madrasas
        $this->updateAssociatedMadrasas($request, $markazAgreement);

        return $markazAgreement;
    }

    private function updateAssociatedMadrasas($request, $markazAgreement)
    {
        // Delete existing associated madrasas
        $markazAgreement->associatedMadrasas()->delete();

        // Add new ones
        if ($request->has('associated_madrasas') && is_array($request->associated_madrasas)) {
            foreach ($request->associated_madrasas as $madrasaData) {
                $this->createAssociatedMadrasa($markazAgreement->id, $madrasaData);
            }
        }
    }

    private function createAssociatedMadrasa($agreementId, $madrasaData)
    {
        $associated = new MarkazAgreementMadrasa();
        $associated->markaz_agreement_id = $agreementId;
        $associated->madrasa_Name = $madrasaData['madrasa_Name'] ?? '';
        $associated->madrasa_id = $madrasaData['madrasa_id'] ?? null;

        // Student counts
        $associated->fazilat = $madrasaData['fazilat'] ?? 0;
        $associated->sanabiya_ulya = $madrasaData['sanabiya_ulya'] ?? 0;
        $associated->sanabiya = $madrasaData['sanabiya'] ?? 0;
        $associated->mutawassita = $madrasaData['mutawassita'] ?? 0;
        $associated->ibtedaiyyah = $madrasaData['ibtedaiyyah'] ?? 0;
        $associated->hifzul_quran = $madrasaData['hifzul_quran'] ?? 0;
        $associated->qirat = $madrasaData['qirat'] ?? 0;

        // File handling
        if (isset($madrasaData['noc_file']) && $madrasaData['noc_file'] instanceof \Illuminate\Http\UploadedFile) {
            $associated->noc_file = $madrasaData['noc_file']->store('markaz/associated/noc', 'public');
        }

        if (isset($madrasaData['resolution_file']) && $madrasaData['resolution_file'] instanceof \Illuminate\Http\UploadedFile) {
            $associated->resolution_file = $madrasaData['resolution_file']->store('markaz/associated/resolution', 'public');
        }

        $associated->save();
    }
}
