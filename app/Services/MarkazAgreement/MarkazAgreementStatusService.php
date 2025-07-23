<?php

namespace App\Services\MarkazAgreement;

use App\Models\MarkazAgreement;
use App\Models\MarkazAgreementLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarkazAgreementStatusService
{
    /**
     * Update status with logging
     */
    public function updateStatus(
        MarkazAgreement $agreement,
        string $newStatus,
        string $comment = null,
        array $additionalData = []
    ): bool {
        return DB::transaction(function() use ($agreement, $newStatus, $comment, $additionalData) {
            $previousStatus = $agreement->status;
            $userId = Auth::id();

            // Update agreement status
            $agreement->status = $newStatus;

            // Set approval info if approved
            if ($newStatus === MarkazAgreement::STATUS_APPROVED) {
                $agreement->approved_by = $userId;
                $agreement->approved_at = now();
            }

            // Set status comment
            $agreement->status_comment = $comment;

            $agreement->save();

            // Create log entry
            $this->createStatusLog(
                $agreement->id,
                $userId,
                $previousStatus,
                $newStatus,
                $this->getActionType($newStatus),
                $comment,
                $additionalData
            );

            return true;
        });
    }

    /**
     * Create initial status log when agreement is created
     */
    public function createInitialLog(int $agreementId, int $userId): void
    {
        $this->createStatusLog(
            $agreementId,
            $userId,
            null,
            MarkazAgreement::STATUS_PENDING,
            MarkazAgreementLog::ACTION_CREATED,
            'মারকায চুক্তির আবেদন তৈরি করা হয়েছে'
        );
    }

    /**
     * Approve agreement
     */
    public function approve(MarkazAgreement $agreement, string $comment = null): bool
    {
        return $this->updateStatus(
            $agreement,
            MarkazAgreement::STATUS_APPROVED,
            $comment ?: 'মারকায চুক্তি অনুমোদন করা হয়েছে'
        );
    }

    /**
     * Suspend agreement
     */
    public function suspend(MarkazAgreement $agreement, string $reason): bool
    {
        return $this->updateStatus(
            $agreement,
            MarkazAgreement::STATUS_SUSPENDED,
            $reason
        );
    }

    /**
     * Cancel agreement
     */
    public function cancel(MarkazAgreement $agreement, string $reason): bool
    {
        return $this->updateStatus(
            $agreement,
            MarkazAgreement::STATUS_CANCELLED,
            $reason
        );
    }

    /**
     * Return to board
     */
    public function returnToBoard(MarkazAgreement $agreement, string $reason): bool
    {
        return $this->updateStatus(
            $agreement,
            MarkazAgreement::STATUS_BOARD_RETURNED,
            $reason
        );
    }

    /**
     * Get status statistics
     */
    public function getStatusStatistics(): array
    {
        return [
            'pending' => MarkazAgreement::where('status', MarkazAgreement::STATUS_PENDING)->count(),
            'approved' => MarkazAgreement::where('status', MarkazAgreement::STATUS_APPROVED)->count(),
            'suspended' => MarkazAgreement::where('status', MarkazAgreement::STATUS_SUSPENDED)->count(),
            'cancelled' => MarkazAgreement::where('status', MarkazAgreement::STATUS_CANCELLED)->count(),
            'board_returned' => MarkazAgreement::where('status', MarkazAgreement::STATUS_BOARD_RETURNED)->count(),
            'total' => MarkazAgreement::count()
        ];
    }

    /**
     * Get agreements by status
     */
    public function getAgreementsByStatus(string $status, int $perPage = 15)
    {
        return MarkazAgreement::with(['creator', 'approver', 'associatedMadrasas'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get agreement history
     */
    public function getAgreementHistory(int $agreementId)
    {
        return MarkazAgreementLog::with('user')
            ->where('markaz_agreement_id', $agreementId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create status log entry
     */
    private function createStatusLog(
        int $agreementId,
        int $userId,
        ?string $previousStatus,
        string $newStatus,
        string $actionType,
        ?string $comment = null,
        array $additionalData = []
    ): void {
        MarkazAgreementLog::create([
            'markaz_agreement_id' => $agreementId,
            'user_id' => $userId,
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'action_type' => $actionType,
            'comments' => $comment,
            'additional_data' => $additionalData
        ]);
    }

    /**
     * Get action type from status
     */
    private function getActionType(string $status): string
    {
        return match($status) {
            MarkazAgreement::STATUS_PENDING => MarkazAgreementLog::ACTION_CREATED,
            MarkazAgreement::STATUS_APPROVED => MarkazAgreementLog::ACTION_APPROVED,
            MarkazAgreement::STATUS_SUSPENDED => MarkazAgreementLog::ACTION_SUSPENDED,
            MarkazAgreement::STATUS_CANCELLED => MarkazAgreementLog::ACTION_CANCELLED,
            MarkazAgreement::STATUS_BOARD_RETURNED => MarkazAgreementLog::ACTION_BOARD_RETURNED,
            default => 'unknown'
        };
    }
}
