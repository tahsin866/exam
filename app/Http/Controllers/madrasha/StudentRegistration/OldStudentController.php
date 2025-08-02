<?php

namespace App\Http\Controllers\Madrasha\StudentRegistration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Madrasha\StudentRegistration\SearchStudentRequest;
use App\Http\Requests\Madrasha\StudentRegistration\EditStudentRequest;
use App\Repositories\Madrasha\StudentRepository;
use App\Services\Madrasha\StudentService;
use App\Resources\Madrasha\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OldStudentController extends Controller
{
    protected $studentRepository;
    protected $studentService;

    public function __construct(
        StudentRepository $studentRepository,
        StudentService $studentService
    ) {
        $this->studentRepository = $studentRepository;
        $this->studentService = $studentService;
    }

    /**
     * Get available student years
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentYears()
    {
        // Currently only returning 2024 as the year
        return response()->json(['2024']);
    }

    /**
     * Search for students based on filters
     *
     * @param SearchStudentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchStudents(SearchStudentRequest $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'অনুগ্রহ করে লগইন করুন'], 401);
            }

            $result = $this->studentService->searchStudents(
                $request->validated(),
                $user,
                $request->header('marhalaId')
            );

            if (isset($result['error'])) {
                return response()->json(['error' => $result['error']], $result['status'] ?? 400);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json(['error' => 'একটি ত্রুটি ঘটেছে। পরে আবার চেষ্টা করুন।'], 500);
        }
    }

    /**
     * Render the edit page for student registration
     *
     * @param EditStudentRequest $request
     * @return \Inertia\Response
     */
    public function editStudentRegistration(EditStudentRequest $request)
    {
        $studentData = $this->studentService->getStudentDataForEdit($request);

        return Inertia::render('students_registration/old_stu_reg_edit', $studentData);
    }

    /**
     * Get student data for editing
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentForEdit(Request $request)
    {
        try {
            $data = $this->studentService->getStudentDetailsForEdit(
                $request->query('roll'),
                $request->query('reg_id'),
                $request->query('CID'),
                $request->header('marhalaId')
            );

            if (!$data) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json(['error' => 'একটি ত্রুটি ঘটেছে। পরে আবার চেষ্টা করুন।'], 500);
        }
    }
}
