<?php

namespace App\Services\MarkazAgreement;
use App\Models\admin\marhala_for_admin\ExamSetup;
use App\Models\{MarkazAgreement, MarkazAgreementMadrasa, MarkazAgreementLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StoreMarkazAgreementService
{
    protected $statusService;

    public function __construct(MarkazStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function execute(Request $request, $user)
    {
        // Wrap in transaction for safety
        return DB::transaction(function () use ($request, $user) {
            // Step 1: Get Exam Setup Data
            $examSetup = $this->getExamSetup();

            // Step 2: Create Main Agreement
            $markazAgreement = $this->createMarkazAgreement($request, $user, $examSetup);

            // Step 3: Handle Associated Madrasas
            $this->handleAssociatedMadrasas($request, $markazAgreement);

            // Step 4: Create initial log entry
            MarkazAgreementLog::create([
                'markaz_agreement_id' => $markazAgreement->id,
                'user_id' => $user->id,
                'previous_status' => null,
                'new_status' => MarkazAgreementLog::STATUS_PENDING,
                'action_type' => MarkazAgreementLog::ACTION_CREATED,
                'comments' => 'মারকায চুক্তির আবেদন তৈরি করা হয়েছে',
            ]);

            return $markazAgreement;
        });
    }

    private function getExamSetup()
    {
        return ExamSetup::select('id')->latest()->first();
    }

    private function createMarkazAgreement($request, $user, $examSetup)
    {
        $markazAgreement = new MarkazAgreement();

        // User and Exam related data
        $markazAgreement->user_id = $user->id;
        $markazAgreement->exam_id = $examSetup?->id;
        $markazAgreement->madrasha_code = $user->custom_code ?? null;
        $markazAgreement->madrasha_id = $user->madrasha_id; // Direct from user table

        // Markaz type and student counts
        $markazAgreement->markaz_type = $request->markaz_type;
        $markazAgreement->fazilat = $request->fazilat ?? 0;
        $markazAgreement->sanabiya_ulya = $request->sanabiya_ulya ?? 0;
        $markazAgreement->sanabiya = $request->sanabiya ?? 0;
        $markazAgreement->mutawassita = $request->mutawassita ?? 0;
        $markazAgreement->ibtedaiyyah = $request->ibtedaiyyah ?? 0;
        $markazAgreement->hifzul_quran = $request->hifzul_quran ?? 0;
        $markazAgreement->qirat = $request->qirat ?? 0;

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

        // Set status and created_by
        $markazAgreement->status = MarkazAgreementLog::STATUS_PENDING;
        $markazAgreement->created_by = $user->id;

        $markazAgreement->save();

        return $markazAgreement;
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
