<?php

namespace App\Services\MarkazAgreement;

use App\Models\MarkazAgreement;
use App\Models\MarkazAgreementLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MarkazStatusService
{
    /**
     * Update markaz agreement status and create log
     */
    public function updateStatus(
        int $agreementId,
        string $newStatus,
        int $userId,
        string $actionType,
        ?string $comments = null
    ): bool {
        return DB::transaction(function () use ($agreementId, $newStatus, $userId, $actionType, $comments) {
            $agreement = MarkazAgreement::findOrFail($agreementId);
            $previousStatus = $agreement->status;

            // Update the agreement status
            $agreement->status = $newStatus;

            // Set approval details if being approved
            if ($newStatus === MarkazAgreementLog::STATUS_APPROVED) {
                $agreement->approved_by = $userId;
                $agreement->approved_at = now();
            }

            $agreement->save();

            // Create log entry
            MarkazAgreementLog::create([
                'markaz_agreement_id' => $agreementId,
                'user_id' => $userId,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'action_type' => $actionType,
                'comments' => $comments,
            ]);

            return true;
        });
    }

    /**
     * Submit application to board
     */
    public function submitToBoard(int $agreementId, int $userId): bool
    {
        return $this->updateStatus(
            $agreementId,
            MarkazAgreementLog::STATUS_BOARD_SUBMITTED,
            $userId,
            MarkazAgreementLog::ACTION_SUBMITTED,
            'আবেদন বোর্ডে দাখিল করা হয়েছে'
        );
    }

    /**
     * Approve application
     */
    public function approveApplication(int $agreementId, int $adminId, ?string $comments = null): bool
    {
        return $this->updateStatus(
            $agreementId,
            MarkazAgreementLog::STATUS_APPROVED,
            $adminId,
            MarkazAgreementLog::ACTION_APPROVED,
            $comments ?? 'আবেদন অনুমোদন করা হয়েছে'
        );
    }

    /**
     * Suspend application
     */
    public function suspendApplication(int $agreementId, int $adminId, string $comments): bool
    {
        return $this->updateStatus(
            $agreementId,
            MarkazAgreementLog::STATUS_SUSPENDED,
            $adminId,
            MarkazAgreementLog::ACTION_SUSPENDED,
            $comments
        );
    }

    /**
     * Cancel application
     */
    public function cancelApplication(int $agreementId, int $adminId, string $comments): bool
    {
        return $this->updateStatus(
            $agreementId,
            MarkazAgreementLog::STATUS_CANCELLED,
            $adminId,
            MarkazAgreementLog::ACTION_CANCELLED,
            $comments
        );
    }

    /**
     * Return application from board
     */
    public function returnFromBoard(int $agreementId, int $adminId, string $comments): bool
    {
        return $this->updateStatus(
            $agreementId,
            MarkazAgreementLog::STATUS_BOARD_RETURNED,
            $adminId,
            MarkazAgreementLog::ACTION_BOARD_RETURNED,
            $comments
        );
    }

    /**
     * Get status history for an agreement
     */
    public function getStatusHistory(int $agreementId): \Illuminate\Database\Eloquent\Collection
    {
        return MarkazAgreementLog::with('user')
            ->where('markaz_agreement_id', $agreementId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
