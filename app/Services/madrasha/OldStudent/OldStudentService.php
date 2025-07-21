<?php

namespace App\Services\madrasha\OldStudent;

use App\Repositories\madrasha\OldStudent\StudentRepository;
use App\Services\madrasha\OldStudent\StudentEditService;
use App\Services\madrasha\OldStudent\StudentTypeService;
use App\Helpers\madrasha\OldStudent\BanglaTranslatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OldStudentService
{
    protected $studentRepository;
    protected $translator;
    protected $typeService;

    public function __construct(
        StudentRepository $studentRepository,
        StudentTypeService $typeService,
        BanglaTranslatorHelper $translator
    ) {
        $this->studentRepository = $studentRepository;
        $this->typeService = $typeService;
        $this->translator = $translator;
    }

    public function getRegistrationData($marhalaId)
    {
        return $this->studentRepository->getRegistrationData($marhalaId);
    }

    public function searchStudents(Request $request)
    {
        $students = $this->studentRepository->search($request);
        $user = Auth::user();

        // Validate madrasha gender type
        $this->typeService->validateGenderMismatch($students, $user);

        $marhalaId = $request->header('marhalaId');
        $filtered = $this->typeService->filterAndClassify($students, $marhalaId, $this->translator);

        return $filtered->isEmpty()
            ? response()->json(['error' => 'রেজাল্ট পাওয়া যায়নি'], 404)
            : response()->json($filtered);
    }

    public function editStudentRegistration(Request $request)
    {
        return StudentEditService::handleEditPage($request);
    }

    public function getStudentForEdit(Request $request)
    {
        return StudentEditService::fetchStudentForEdit($request);
    }
}
