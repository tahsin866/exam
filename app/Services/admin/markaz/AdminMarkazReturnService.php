<?php

namespace App\Services\admin\markaz;

use App\Models\User;
use App\Models\MarkazAgreement;
use App\Repositories\admin\markaz\AdminMarkazRepositoryInterface;
use App\Services\MarkazAgreement\MarkazAgreementStatusService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminMarkazReturnService
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

    public function execute(int $agreementId, User $admin, string $message, ?UploadedFile $image = null): array
    {
        try {
            // Find the agreement
            $agreement = $this->repository->findById($agreementId);

            if (!$agreement) {
                return [
                    'success' => false,
                    'message' => 'আবেদন পাওয়া যায়নি!',
                    'status_code' => 404
                ];
            }

            // Handle feedback image upload
            $imagePath = null;
            if ($image) {
                $imagePath = $image->store('feedback_images', 'public');
            }

            // Update MarkazAgreement with feedback
            $updateData = [
                'admin_feedback' => $message,
                'feedback_image' => $imagePath
            ];

            $this->repository->updateAgreement($agreement, $updateData);

            // Use the status service to return the agreement to board
            $success = $this->statusService->returnToBoard($agreement, $message);

            if ($success) {
                return [
                    'success' => true,
                    'message' => 'আবেদন সফলভাবে ফেরত পাঠানো হয়েছে!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'ফেরত পাঠাতে সমস্যা হয়েছে!',
                    'status_code' => 500
                ];
            }

        } catch (Exception $e) {
            Log::error('Markaz Return Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'ফেরত পাঠাতে সমস্যা হয়েছে! Error: ' . $e->getMessage(),
                'status_code' => 500
            ];
        }
    }
}
