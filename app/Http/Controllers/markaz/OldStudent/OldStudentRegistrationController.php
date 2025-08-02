<?php

namespace App\Http\Controllers\markaz\OldStudent;

use App\Http\Controllers\Controller;
use App\Http\Requests\markaz\OldStudent\SearchStudentRequest;
use App\Services\markaz\OldStudent\StudentSearchService;
use App\DTOs\markaz\OldStudent\StudentSearchDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OldStudentRegistrationController extends Controller
{
    public function __construct(
        private StudentSearchService $studentSearchService
    ) {}

    /**
     * Search students based on filters
     */
    public function searchStudents(SearchStudentRequest $request)
    {
        try {
            // Get authenticated user
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'অনুগ্রহ করে লগইন করুন'], 401);
            }

            // Get marhalaId from header
            $marhalaId = $request->header('marhalaId');

            // Create DTO from request
            $searchDTO = StudentSearchDTO::fromRequest(
                $request->validated(),
                $marhalaId ? (int) $marhalaId : null,
                $user->MType ?? 1
            );

            // Search students using service
            $result = $this->studentSearchService->searchStudents($searchDTO);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Student search error: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'request_data' => $request->validated(),
                'marhalaId' => $marhalaId ?? null
            ]);

            $statusCode = $e->getCode() ?: 500;
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * Get available years for student search
     */
    public function getStudentYears()
    {
        return response()->json(['2024']);
    }
}
