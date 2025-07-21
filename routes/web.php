<?php
use App\Http\Controllers\MarhalaController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\markazChangeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\SubjectSettingsController;
use Illuminate\Foundation\Application;
use App\Http\Controllers\teacherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarhalaListController;
use Inertia\Inertia;
use App\Models\admin\marhala_for_admin\Marhala;
use App\Http\Controllers\ExamSetupController;
use App\Http\Controllers\Auth\madrasha_check_for_userController;
use App\Http\Controllers\Auth\userRegisteredUserController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// মারহালা
Route::prefix('api')->group(function () {
    Route::get('/get-marhala-list', [MarhalaController::class, 'fetchMarhalaWithCounts']);
    Route::get('/subject-marhala-counts', [MarhalaController::class, 'getSubjectMarhalaStats']);
});


    Route::post('/subject-settings', [SubjectSettingsController::class, 'store'])
    ->name('subject-settings.store');


    Route::post('subject-settings/store', [SubjectSettingsController::class, 'store'])
    ->name('subject-settings.store');


    Route::get('/central-exam-setup/nibondon-setup/{id}', [ExamSetupController::class, 'nibondonSetup'])
    ->name('central_Exam_setup.nibondon_setup');





// সাবজেক্ট সেটিংস








    Route::get('/students-registration', [StudentRegistrationController::class, 'getStudentdata'])->name('students_registration.index');
    Route::get('/students-registration/{id}', [StudentRegistrationController::class, 'view'])->name('students_registration.stu_reg_view');




    Route::get('/madrasha/students/{madrasha_id}', [StudentRegistrationController::class, 'getMadrashaStudents'])
    ->name('nibondon_for_admin.madrashaWari_stu_nibond_list');

    Route::get('/nibondon/abandon-stu-list/{markaz_id}', [StudentRegistrationController::class, 'abandonStuList'])
    ->name('nibondon_for_admin.abandon_stu_list');


    Route::get('/api/admins', [ProfileController::class, 'getAdmins'])->name('api.admins');

    Route::post('/teacher-store', [teacherController::class, 'techerStore'])->name('teacher.store');
    Route::post('/old-teacher-store', [teacherController::class, 'OldtecherStore'])->name('Oldteacher.store');
Route::delete('/negran-mumtahin/delete/{id}', [teacherController::class, 'destroy'])->name('Negran_Mumtahin.delete');

Route::post('/mumtahin/submit-to-board/{id}', [teacherController::class, 'submitedToBoard'])->name('mumtahin.submit-to-board');






    Route::post('/markaz-exchange', [markazChangeController::class, 'MarkazStore'])->name('markaz.exchange.store');


    Route::post('madrasha_check_for_user', [madrasha_check_for_userController::class, 'check'])->name('madrasha.check');


    Route::get('madrasha_check_for_user', function () {
        return inertia::render('Auth/madrasha_check_for_user');
    })->name('madrasha_check_for_user');


    Route::get('register', [userRegisteredUserController::class, 'create'])->middleware('check.madrasha.access')
    ->name('register');



require __DIR__ . '/auth.php';
require __DIR__ . '/admin_auth.php';


require __DIR__ . '/Madrasha.php';

require __DIR__ . '/Admin.php';
require __DIR__ . '/api.php';
