<?php

namespace App\Http\Controllers\markaz\OldStudent;

use App\Http\Controllers\Controller;
use App\Http\Requests\markaz\OldStudent\SearchStudentsRequest;
use App\Services\markaz\OldStudent\StudentSearchService;
use App\DTOs\markaz\OldStudent\StudentSearchDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentSearchController extends Controller
{
    public function __construct(
        private StudentSearchService $studentSearchService
    ) {}

    /**
     * Search students based on provided criteria
     */
    public function searchStudents(SearchStudentsRequest $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'অনুগ্রহ করে লগইন করুন'], 401);
        }

        // Create DTO from request
        $searchDTO = StudentSearchDTO::fromRequest(
            $request->getSearchParams(),
            $user->MType
        );

        // Search students using service
        $result = $this->studentSearchService->searchStudents($searchDTO);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json($result['data']);
    }

    /**
     * Get available student years
     */
    public function getStudentYears(): JsonResponse
    {
        return response()->json(['2024']);
    }
}
