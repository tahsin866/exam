<?php

namespace App\Http\Controllers\admin\markaz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarkazAgreement;
use App\Models\MarkazAgreementMadrasa;
use App\Models\admin\marhala_for_admin\ExamSetup;
use App\Models\madrasha;
use App\Models\Division;
use Illuminate\Support\Facades\DB;
use App\Models\District;
use App\Models\Thana;
use App\Models\schedule_setups;
use App\Models\activity_log;
use App\Services\MarkazAgreement\MarkazAgreementStatusService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

// Clean Architecture Imports
use App\Http\Requests\admin\markaz\AdminMarkazProcessingRequest;
use App\Http\Requests\admin\markaz\AdminMarkazApprovalRequest;
use App\Http\Requests\admin\markaz\AdminMarkazReturnRequest;
use App\Services\admin\markaz\AdminMarkazProcessingService;
use App\Services\admin\markaz\AdminMarkazApprovalService;
use App\Services\admin\markaz\AdminMarkazReturnService;

class AdminMakazController extends Controller
{
    protected AdminMarkazProcessingService $processingService;
    protected AdminMarkazApprovalService $approvalService;
    protected AdminMarkazReturnService $returnService;

    public function __construct(
        AdminMarkazProcessingService $processingService,
        AdminMarkazApprovalService $approvalService,
        AdminMarkazReturnService $returnService
    ) {
        $this->processingService = $processingService;
        $this->approvalService = $approvalService;
        $this->returnService = $returnService;
    }



    /**
     *
     *
     *
     *
     *
     *
     * Display markaz agreement details for admin with status logs
     */
    public function viewDetails($id)
    {
        $markazDetails = app(\App\Services\admin\MarkazAgreement\AdminViewMarkazAgreementDetailsService::class)->execute($id);

        return Inertia::render('admin/markaz/markazApplyDetailsView', [
            'markazDetails' => $markazDetails,
            'application' => [
                'id' => $markazDetails->id,
                'status' => $markazDetails->status
            ]
        ]);
    }





    /**
     * এডমিন আবেদন প্রক্রিয়াকরণ শুরু
     */
    public function in_processing(AdminMarkazProcessingRequest $request)
    {
        $agreementId = $request->validated()['id'];
        $admin = Auth::user();

        $result = $this->processingService->execute($agreementId, $admin);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], $result['status_code'] ?? 500);
    }





    /**
     * এডমিন আবেদন অনুমোদন
     */
    public function approveApplication(AdminMarkazApprovalRequest $request)
    {
        $agreementId = $request->validated()['id'];
        $admin = Auth::user();

        $result = $this->approvalService->execute($agreementId, $admin);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], $result['status_code'] ?? 500);
    }





    /**
     * এডমিন আবেদন ফেরত পাঠানো
     */
    public function return(AdminMarkazReturnRequest $request)
    {
        $validated = $request->validated();
        $agreementId = $validated['id'];
        $message = $validated['message'];
        $image = $request->file('image');
        $admin = Auth::user();

        $result = $this->returnService->execute($agreementId, $admin, $message, $image);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ], $result['status_code'] ?? ($result['success'] ? 200 : 500));
    }
}
