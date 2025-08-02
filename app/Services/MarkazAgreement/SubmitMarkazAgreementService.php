<?php

namespace App\Services\MarkazAgreement;

use App\Models\MarkazAgreement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\schedule_setups;
use App\Models\MarkazAgreementLog;

class SubmitMarkazAgreementService
{
    public function execute($id, $userId)
    {
        $agreement = MarkazAgreement::find($id);
        if (!$agreement) {
            return ['error' => 'আবেদন পাওয়া যায়নি!'];
        }

        if ($agreement->status === MarkazAgreementLog::STATUS_BOARD_SUBMITTED) {
            return ['error' => 'আবেদন ইতিমধ্যে বোর্ডে দাখিল করা হয়েছে!'];
        }

        $schedule = schedule_setups::where('exam_setup_id', $agreement->exam_id)
            ->where('schedule_type', 'মারকায আবেদন')
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return ['error' => 'সময়সূচী পাওয়া যায়নি!'];
        }

        $currentDate = now();
        $startDate = \Carbon\Carbon::parse($schedule->start_date);
        $endDate = \Carbon\Carbon::parse($schedule->end_date);

        if ($currentDate->lt($startDate)) {
            return ['error' => 'আবেদনের সময় শুরু হয়নি! ' . $startDate->format('d-m-Y') . ' তারিখে আবার চেষ্টা করুন।'];
        }

        if ($currentDate->gt($endDate)) {
            return ['error' => 'আবেদনের সময় শেষ! আগামী বছর আবার চেষ্টা করুন।'];
        }

        try {
            $statusService = new MarkazStatusService();
            $statusService->submitToBoard($agreement->id, $userId);
            return ['success' => 'আবেদন সফলভাবে বোর্ডে দাখিল হয়েছে!'];
        } catch (\Exception $e) {
            Log::error('Markaz Submit Error: ' . $e->getMessage());
            return ['error' => 'আবেদন দাখিলে সমস্যা হয়েছে! আবার চেষ্টা করুন। ' . $e->getMessage()];
        }
    }
}
