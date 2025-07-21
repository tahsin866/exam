<?php

use App\Http\Controllers\MarhalaController;
use App\Http\Controllers\SubjectSettingsController;
use App\Http\Controllers\markazChangeController;
use App\Http\Controllers\ExamSetupController;
use App\Http\Controllers\ScheduleSetupController;
use App\Http\Controllers\ExamRegistrationController;
use App\Http\Controllers\MarkazAgreementController;
use App\Http\Controllers\MadrashaController;
use App\Http\Controllers\MarhalaListController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\teacherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\markaz\markazApplicationController;
use App\Http\Controllers\BoardApplicationController;
use App\Http\Controllers\markaz\getMadrashaController;

// Marhala Controller Routes
Route::get('/marhalas/{id}/edit', [MarhalaController::class, 'edit']);
Route::put('/marhalas/{id}', [MarhalaController::class, 'update']);

Route::prefix('api')->group(function () {
    // MarhalaController Routes

    // SubjectSettingsController Routes
    Route::get('/marhala/{marhala}/subjects', [SubjectSettingsController::class, 'getData']);
    Route::get('/subjects', [SubjectSettingsController::class, 'index']);
    Route::get('/subjects', [SubjectSettingsController::class, 'search']);
    Route::post('/subject-settings', [SubjectSettingsController::class, 'subjectStore'])->name('subject-settings.store');
    Route::get('/subject-settings/{id}', [SubjectSettingsController::class, 'showsubjects']);
    Route::get('/subject-settings/{id}', [SubjectSettingsController::class, 'getSubjectSetting']);
    Route::put('/subject-settings/{id}', [SubjectSettingsController::class, 'updateSubjectSetting']);
    Route::get('/marhala/{marhalaId}/subjects', [SubjectSettingsController::class, 'getsubjecData']);
    Route::post('/exam-setups', [SubjectSettingsController::class, 'store_1']);
    Route::get('/exam-setups/latest', [SubjectSettingsController::class, 'getLatest']);
    Route::get('/marhalas', [SubjectSettingsController::class, 'index_1']);
    Route::post('/exam-fees', [SubjectSettingsController::class, 'store_2']);

    // ExamSetupController Routes
    Route::get('/exam-setups/{id}', [ExamSetupController::class, 'getExamSetup']);
    Route::put('/exam-setups/{id}', [ExamSetupController::class, 'updateExamSetup']);
    Route::get('/exam-setups_1', [ExamSetupController::class, 'index_2']);
    Route::put('/central-exam-setup/{id}', [ExamSetupController::class, 'update'])->name('central_Exam_setup.update');
    Route::get('/central-exam-setup', [ExamSetupController::class, 'index'])->name('central_Exam_setup.index');

    // ScheduleSetupController Routes
    Route::get('/schedule-setups/{id}', [ScheduleSetupController::class, 'getScheduleSetup']);
    Route::put('/schedule-setups/{id}', [ScheduleSetupController::class, 'updateScheduleSetup']);
    Route::post('/schedule-setups', [ScheduleSetupController::class, 'store']);


    // MarkazAgreementController Routes
    Route::get('/madrashas/list', [getMadrashaController::class, 'getMadrashas'])->name('madrashas.list');
    Route::get('/exam-setups/latest', [markazApplicationController::class, 'getLatestExam'])->name('exam-setups.latest');
    Route::get('/markaz/get-table-data', [MarkazAgreementController::class, 'getTableData']);
    Route::get('/markaz-agreements', [markazApplicationController::class, 'fatch']);

    Route::get('/markaz-madrashas/list', [MarkazAgreementController::class, 'getMadrashas'])->name('markaz.madrashas.list');



    // MadrashaController Routes
    Route::get('/madrasha-list', [MadrashaController::class, 'getMadrashaList']);
    Route::get('/markaz-list', [MadrashaController::class, 'getMarkazList']);
    Route::get('/madrashas/filter', [MadrashaController::class, 'filterMadrashas']);
    Route::get('/filter-markaz-students', [MadrashaController::class, 'filterMarkazStudents']);
    Route::get('/madrasa-list', [MadrashaController::class, 'index']);
    Route::get('/divisions', [MadrashaController::class, 'getDivisions']);
    Route::get('/districts/{division}', [MadrashaController::class, 'getDistricts']);
    Route::get('/thanas/{district}', [MadrashaController::class, 'getThanas']);
    Route::get('/export-madrasa', [MadrashaController::class, 'export']);




    // ExamRegistrationController Routes
    Route::get('/exam-fees', [ExamRegistrationController::class, 'examFeeList']);
    Route::get('/student-registration/{marhalaId}', [ExamRegistrationController::class, 'getRegistrationData']);
    Route::get('/student-registration_old/{marhalaId}', [ExamRegistrationController::class, 'getRegistrationDataforold']);
    Route::get('/student-years', [ExamRegistrationController::class, 'getStudentYears']);
    Route::get('/search-students', [ExamRegistrationController::class, 'searchStudents']);
    Route::get('/get-student-for-edit', [ExamRegistrationController::class, 'getStudentForEdit']);
    Route::post('/save-student-info/{marhalaId}', [ExamRegistrationController::class, 'saveStudentInfo']);
    Route::post('/save-student-info_1/{marhalaId}', [ExamRegistrationController::class, 'NewSaveStudentInfo']);

    // MarhalaListController Routes
    Route::get('/subjects/{marhalaId}', [MarhalaListController::class, 'getSubjects'])->name('api.subjects');
    Route::post('/save-subject-selection', [MarhalaListController::class, 'saveSubjectSelection'])->name('api.save-subject-selection');
    Route::get('/user-subject-selection/{marhalaId}', [MarhalaListController::class, 'getUserSubjectSelection']);

    // StudentRegistrationController Routes
    Route::get('/students-registration', [StudentRegistrationController::class, 'getStudents']);
    Route::get('/markaz-students', [StudentRegistrationController::class, 'getMarkazStudents']);
    Route::get('/markaz-madrasa-list/{markaz_id}', [StudentRegistrationController::class, 'getMarkazMadrasaList'])->name('api.markaz_madrasa_list');
    Route::get('/abandon-stu-list/{markaz_id}', [StudentRegistrationController::class, 'abandonStuList'])->name('nibondon_for_admin.abandon_stu_list');

    // DashboardController Routes
    Route::get('/dashboard/student-stats', [dashboardController::class, 'getStudentStats']);
    Route::get('/payment-stats', [dashboardController::class, 'getPaymentStats']);
    Route::post('/store-payment', [dashboardController::class, 'storePayment']);
    Route::get('/check-payment-status', [dashboardController::class, 'checkPaymentStatus']);
    Route::get('/unpaid-students-count', [dashboardController::class, 'getUnpaidStudentsCount']);

    // markazChangeController

    Route::get('/user-centers', [markazChangeController::class, 'showUserCenters'])->name('user.centers');
    Route::get('/markaz-madrashas/list', [markazChangeController::class, 'getMadrashasChange'])->name('markaz-madrashas.list');

    Route::get('/markaz-Center', [markazChangeController::class, 'showMarkaZCenters'])->name('markaz.change');
    Route::get('/markaz-changes', [MarkazChangeController::class, 'getMarkazChange'])->name('markaz-changes.index');



    Route::get('/search-teacher', [teacherController::class, 'searchTeacher']);




    Route::get('/books', [teacherController::class, 'getbooks'])->name('books.getbooks');
Route::get('/get-book-categories', [teacherController::class, 'getBookCategories'])->name('books.categories');
// Route::post('/neg-mumtahin-application', [teacherController::class, 'techerStore'])->name('neg-mumtahin-application.store');

Route::get('/neg-mumtahin-applications', [teacherController::class, 'getTeachers']);


Route::get('/negran/edit/{id}', [teacherController::class, 'negedit'])->name('negran.edit');

Route::put('/negran/update/{id}', [teacherController::class, 'negupdate'])->name('negran.update');



Route::get('/negran-mumtahin/list', [BoardApplicationController::class, 'getNegranData']);

Route::post('/markaz-changes/{id}/approve', [MarkazChangeController::class, 'approveMarkazChange']);



Route::post('/submit-all-applications', [App\Http\Controllers\StudentRegistrationController::class, 'submitAllApplications'])->name('submit.all.applications');


});
