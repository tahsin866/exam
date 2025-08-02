<?php

namespace App\Services\admin\markaz;

use App\Models\User;
use App\Models\MarkazAgreement;
use App\Repositories\admin\markaz\AdminMarkazRepositoryInterface;
use App\Services\MarkazAgreement\MarkazAgreementStatusService;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminMarkazProcessingService
{
    protected AdminMarkazRepositoryInterface $repository;
    protected MarkazAgreementStatusService $statusService;

    public function __construct(
        AdminMarkazRepositoryInterface $repository,
        MarkazAgreementStatusService $statusService
    ) {
        $this->repository = $repository;
        $this->statusService = $statusService;
    }

    public function execute(int $agreementId, User $admin): array
    {
        try {
            // Find the agreement
            $agreement = $this->repository->findById($agreementId);

            if (!$agreement) {
                return [
                    'success' => false,
                    'message' => 'আবেদন পাওয়া যায়নি!'
                ];
            }

            // Use the status service to update status
            $success = $this->statusService->inProcessing($agreement, 'আবেদন প্রক্রিয়াকরণ শুরু হয়েছে');

            if ($success) {
                return [
                    'success' => true,
                    'message' => 'আবেদন প্রক্রিয়াকরণ শুরু হয়েছে!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'প্রক্রিয়াকরণ শুরু করতে সমস্যা হয়েছে!'
                ];
            }

        } catch (Exception $e) {
            Log::error('Markaz Processing Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'প্রক্রিয়াকরণ শুরু করতে সমস্যা হয়েছে! Error: ' . $e->getMessage()
            ];
        }
    }
}
