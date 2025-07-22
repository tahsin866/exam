<?php

namespace App\Services\MarkazAgreement;
use App\Models\admin\marhala_for_admin\ExamSetup;
use App\Models\{madrasha,  MarkazAgreement, MarkazAgreementMadrasa, Division, District, Thana};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreMarkazAgreementService
{
    public function execute(Request $request, $user)
    {
        // Wrap in transaction for safety
        DB::transaction(function () use ($request, $user) {
            // Step 1: Get Data
            $madrashaData = $this->getMadrashaData($user);
            $examSetup = $this->getExamSetup();

            // Step 2: Create Main Agreement
            $markazAgreement = $this->createMarkazAgreement($request, $user, $madrashaData, $examSetup);

            // Step 3: Handle Associated Madrasas
            $this->handleAssociatedMadrasas($request, $markazAgreement);
        });
    }

    private function getMadrashaData($user)
    {
        return madrasha::find($user->madrasha_id);
    }

    private function getExamSetup()
    {
        return ExamSetup::select('id', 'exam_name')->latest()->first();
    }

    private function createMarkazAgreement($request, $user, $madrashaData, $examSetup)
    {
        $markazAgreement = new MarkazAgreement();

        // User and Exam related data
        $markazAgreement->user_id = $user->id;
        $markazAgreement->madrasha_Name = $user->madrasha_name;
        $markazAgreement->madrasha_code = $user->custom_code;
        $markazAgreement->exam_id = $examSetup?->id;
        $markazAgreement->exam_name = $examSetup?->exam_name;

        // Madrasha related data
        $this->fillMadrashaInfo($markazAgreement, $madrashaData);

        // Student counts and markaz type
        $this->fillStudentCounts($markazAgreement, $request);

        // File handling
        $this->handleMainFiles($markazAgreement, $request);

        // Requirements
        $markazAgreement->requirements = $request->requirements;

        // Consent files
        $this->handleConsentFiles($markazAgreement, $request);

        $markazAgreement->save();

        return $markazAgreement;
    }

    private function fillMadrashaInfo($markazAgreement, $madrashaData)
    {
        if ($madrashaData) {
            $division = Division::find($madrashaData->DID);
            $district = District::where('DesID', $madrashaData->DISID)->first();
            $thana = Thana::where('Thana_ID', $madrashaData->TID)->first();

            $markazAgreement->division = $division?->Division ?? '';
            $markazAgreement->division_id = $madrashaData->DID ?? 0;

            $markazAgreement->district = $district?->District ?? '';
            $markazAgreement->district_id = $madrashaData->DISID ?? 0;

            $markazAgreement->thana_uni = $thana?->Thana ?? '';
            $markazAgreement->tid = $madrashaData->TID ?? 0;

            $markazAgreement->mtype = $madrashaData->MType ?? 0;
            $markazAgreement->Stage = $madrashaData->Stage ?? 0;
            $markazAgreement->Elhaq_no = $madrashaData->ElhaqNo ?? '';
        } else {
            $markazAgreement->division = '';
            $markazAgreement->division_id = 0;
            $markazAgreement->district = '';
            $markazAgreement->district_id = 0;
            $markazAgreement->thana_uni = '';
            $markazAgreement->tid = 0;
            $markazAgreement->mtype = 0;
            $markazAgreement->Stage = 0;
            $markazAgreement->Elhaq_no = '';
        }
    }

    private function fillStudentCounts($markazAgreement, $request)
    {
        $fields = [
            'markaz_type', 'fazilat', 'sanabiya_ulya', 'sanabiya',
            'mutawassita', 'ibtedaiyyah', 'hifzul_quran', 'qirat'
        ];
        foreach ($fields as $field) {
            $markazAgreement->$field = $request->$field;
        }
    }

    private function handleMainFiles($markazAgreement, $request)
    {
        $fileFields = [
            'noc_file'         => 'markaz/noc',
            'resolution_file'  => 'markaz/resolution',
        ];

        foreach ($fileFields as $input => $path) {
            if ($request->hasFile($input) && $request->file($input) instanceof \Illuminate\Http\UploadedFile) {
                $markazAgreement->$input = $request->file($input)->store($path);
            }
        }
    }

    private function handleConsentFiles($markazAgreement, $request)
    {
        $consentFields = [
            'muhtamim_consent', 'president_consent', 'committee_resolution'
        ];
        foreach ($consentFields as $input) {
            if ($request->hasFile($input) && $request->file($input) instanceof \Illuminate\Http\UploadedFile) {
                $markazAgreement->$input = $request->file($input)->store('markaz/consent');
            }
        }
    }

    private function handleAssociatedMadrasas($request, $markazAgreement)
    {
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

        $fields = [
            'fazilat', 'sanabiya_ulya', 'sanabiya',
            'mutawassita', 'ibtedaiyyah', 'hifzul_quran', 'qirat'
        ];
        foreach ($fields as $field) {
            $associated->$field = $madrasaData[$field] ?? 0;
        }

        // File handling
        $fileFields = [
            'noc_file' => 'markaz/associated/noc',
            'resolution_file' => 'markaz/associated/resolution',
        ];
        foreach ($fileFields as $input => $path) {
            if (isset($madrasaData[$input]) && $madrasaData[$input] instanceof \Illuminate\Http\UploadedFile) {
                $associated->$input = $madrasaData[$input]->store($path);
            }
        }

        $associated->save();
    }
}
