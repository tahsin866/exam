<?php
use App\Http\Controllers\MarhalaController;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
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
use App\Http\Controllers\markaz\MarkazAgreementPDFController;

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
})->middleware(['auth', 'madrasa'])->name('dashboard');

Route::middleware(['auth', 'madrasa'])->group(function () {
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

    Route::get('/nibondon/abandon-stu-list/{markaz_id?}', [StudentRegistrationController::class, 'abandonStuList'])
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


Route::get('/markaz-agreements/{id}/pdf', [MarkazAgreementPDFController::class, 'generatePDF'])->name('markaz-agreements.pdf');

// Test Bangla PDF route
Route::get('/test-bangla-pdf', function () {
    $testData = [
        'application_no' => '১২৩৪৫',
        'is_student' => true,
        'is_female_student' => false,
        'muhtamim_name' => 'মুহাম্মদ আবদুল করিম',
        'years_with_befaq' => '৩',
        'ilhok_no' => '১০০১',
        'madrasa_code' => 'বেফাক-০০১',
        'madrasa_name' => 'জামিয়া ইসলামিয়া ঢাকা',
        'address' => 'ঢাকা, বাংলাদেশ',
        'mobile' => '০১৭১১২২৩৩৪৪',
        'email' => 'test@example.com',
        'previous_markaz_info' => 'পূর্বের মারকায তথ্য',
        'necessity_for_markaz' => 'শিক্ষার মান উন্নয়নের জন্য মারকায প্রয়োজন।',
        'total_examinees_applied_markaz' => '১৫০',
        'hall_description_checked' => true,
        'hall_length' => '৫০',
        'hall_width' => '৩০',
        'total_square_feet' => '১৫০০',
        'tripod_count' => '৫০',
        'attachment_resolution' => true,
        'attachment_previous_markaz_no_objection' => true,
        'attachment_befaq_recommendation' => true,
        'attachment_affiliated_madrasas_consent' => true,
        'attachment_affiliated_madrasas_previous_no_objection' => true,
        'muhtamim_mobile' => '০১৭১১২২৩৩৪৪',
        'oath_date' => '২২/০৭/২০২৫',
        'affiliated_madrasas' => []
    ];

    // Test with mPDF
    $htmlContent = view('markazApplication.markaz_application_pdf_mpdf', ['application' => (object)$testData])->render();

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'P',
        'default_font' => 'solaimanlipi',
        'fontDir' => [storage_path('fonts')],
        'fontdata' => [
            'solaimanlipi' => [
                'R' => 'SolaimanLipi.ttf',
                'B' => 'SolaimanLipi.ttf',
                'I' => 'SolaimanLipi.ttf',
                'BI' => 'SolaimanLipi.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ]
        ],
        'autoScriptToLang' => true,
        'autoLangToFont' => true,
    ]);

    // Configure for better Bangla rendering
    $mpdf->useSubstitutions = false;
    $mpdf->simpleTables = true;
    $mpdf->SetDirectionality('ltr');
    $mpdf->SetDefaultFont('solaimanlipi');

    $mpdf->WriteHTML($htmlContent);

    return response($mpdf->Output('test_bangla_markaz.pdf', 'D'))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="test_bangla_markaz.pdf"');
});


// Storage file serving route
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin_auth.php';


require __DIR__ . '/Madrasha.php';

require __DIR__ . '/Admin.php';
require __DIR__ . '/api.php';
Route::get('/getStudentList', [App\Http\Controllers\teacherController::class, 'getStudentList']);
