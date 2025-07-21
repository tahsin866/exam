<?php

use App\Http\Controllers\MarhalaController;
use App\Http\Controllers\ExamRegistrationController;
use App\Http\Controllers\MarkazAgreementController;
use App\Http\Controllers\markaz\markazApllyController;
use App\Http\Controllers\markaz\markazApplicationController;
use App\Http\Controllers\markazChangeController;
use App\Http\Controllers\StudentRegistrationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * --------------------------------------------------------------------------
 * Markaz Routes
 * --------------------------------------------------------------------------
 */

// Markaz Apply & Change
Route::get('/makaj-apply', fn() => Inertia::render('Markaz/makaj-apply'))->name('Markaz.makaj-apply');
Route::get('/makaj-change', fn() => Inertia::render('Markaz/Makaj_change'))->name('Markaz.Makaj_change');
Route::get('/markaz-change-table', fn() => Inertia::render('Markaz/markaz_change_table'))->name('Markaz.markaz_change_table');

// Markaz Agreement
Route::get('/markaz-agreement-form', fn() => Inertia::render('Markaz/markaz_agreement_form'))->name('Markaz.markaz_agreement_form');
Route::resource('markaz-agreements', MarkazAgreementController::class); // resourceful MarkazAgreement routes

// Markaz Marhala & Subject
Route::get('/marhala-change', fn() => Inertia::render('Markaz/marhala_change'))->name('Markaz.marhala_change');
Route::get('/marhala-change-table', fn() => Inertia::render('Markaz/marhala_change_table'))->name('Markaz.marhala_change_table');
Route::get('/subjectSettings-for-madrasha', fn() => Inertia::render('Markaz/subjectSettings_for_madrasha'))->name('Markaz.subjectSettings_for_madrasha');
Route::get('/subjectSetting-list', fn() => Inertia::render('Markaz/subjectSetting_list'))->name('Markaz.subjectSetting_list');

// Markaz Application
Route::get('/markaz/makaj-apply', [markazApplicationController::class, 'getTableData'])->name('markaz.makaj_apply.index');
Route::resource('markaz-agreements', markazApplicationController::class); // resourceful markazApplication routes (may conflict with above!)
Route::get('/marjaz-detailes-view/{id}', [markazApplicationController::class, 'viewDetails'])->name('Markaz.view');
Route::get('/markaz/application/{id}', [markazApplicationController::class, 'Edit'])->name('Markaz.edit');
Route::post('/markaz-apply-update/{id}', [markazApplicationController::class, 'update'])->name('markaz-agreements.update');
Route::post('/markaz/submit/{id}', [markazApplicationController::class, 'submitApplication'])->name('markaz.submit');
Route::delete('/markaz/delete/{id}', [markazApplicationController::class, 'deleteAgreement'])->name('markaz.delete');

/**
 * --------------------------------------------------------------------------
 * Student Registration Routes
 * --------------------------------------------------------------------------
 */

Route::get('/student-registration', fn() => Inertia::render('students_registration/student_registration'))->name('students_registration.student_registration');
Route::get('/student-reg-table', fn() => Inertia::render('students_registration/student_reg_table'))->name('students_registration.student_reg_table');
Route::get('/old-stu-reg-Form', fn() => Inertia::render('students_registration/old_stu_reg_Form'))->name('students_registration.old_stu_reg_Form');
Route::get('/new-stu-reg-Form', fn() => Inertia::render('students_registration/new_stu_reg_Form'))->name('students_registration.new_stu_reg_Form');
Route::get('/stuedent-reg-list', fn() => Inertia::render('students_registration/stuedent_reg_list'))->name('students_registration.stuedent_reg_list');
Route::get('/student-reg-card', fn() => Inertia::render('students_registration/student_reg_card'))->name('students_registration.student_reg_card');
Route::get('/stu-reg-view', fn() => Inertia::render('students_registration/stu_reg_view'))->name('students_registration.stu_reg_view');
Route::get('/stu-reg-payment', fn() => Inertia::render('students_registration/stu_reg_payment'))->name('students_registration.stu_reg_payment');
Route::get('/stue-reg-draft-soft-delete', fn() => Inertia::render('students_registration/stue_reg_draft_soft_delete'))->name('students_registration.stue_reg_draft_soft_delete');

// Student Registration Edit & View
Route::get('students_registration/old_stu_reg_edit', fn() => Inertia::render('students_registration/old_stu_reg_edit'))->name('students_registration.old_stu_reg_edit');
Route::get('students_registration/student_registration_edit', fn() => Inertia::render('students_registration/student_registration_edit'))->name('students_registration.student_registration_edit');
Route::get('students_registration/student_registraion_view', fn() => Inertia::render('students_registration/student_registraion_view'))->name('students_registration.student_registraion_view');

// Student Registration Controller Actions
Route::get('/students/registration/edit', [ExamRegistrationController::class, 'editStudentRegistration'])->name('students_registration.old_stu_reg_edit');
Route::get('/get-student-for-edit', [ExamRegistrationController::class, 'getStudentForEdit']);
Route::get('/students-registration/{id}/edit', [StudentRegistrationController::class, 'editStudentRegistration'])->name('students_registration.student_registration_edit');
Route::put('/students-registration/{id}', [StudentRegistrationController::class, 'updateStudentRegistration'])->name('students_registration.student_registration_update');
Route::get('/students/registration/{id}', [StudentRegistrationController::class, 'studentRegistrationView'])->name('students_registration.student_registraion_view');
Route::post('/student_reg/submit/{id}', [StudentRegistrationController::class, 'reg_submitApplication'])->name('student_reg.submit');
Route::delete('/students/delete/{id}', [StudentRegistrationController::class, 'studentRegistrationDelete'])->name('students.delete');

/**
 * --------------------------------------------------------------------------
 * OntorVukti (অন্তর্ভুক্তি) Routes
 * --------------------------------------------------------------------------
 */

Route::get('OntorVukti/ontorvukti_table', fn() => Inertia::render('OntorVukti/ontorvukti_table'))->name('OntorVukti.ontorvukti_table');
Route::get('OntorVukti/softDelete_draft_list', fn() => Inertia::render('OntorVukti/softDelete_draft_list'))->name('OntorVukti.softDelete_draft_list');
Route::get('OntorVukti/stu_admit_card', fn() => Inertia::render('OntorVukti/stu_admit_card'))->name('OntorVukti.stu_admit_card');
Route::get('OntorVukti/stu_payment', fn() => Inertia::render('OntorVukti/stu_payment'))->name('OntorVukti.stu_payment');

/**
 * --------------------------------------------------------------------------
 * Negran/Mumtahin Routes
 * --------------------------------------------------------------------------
 */

Route::get('/negran-mumtahin', fn() => Inertia::render('Negran_Mumtahin/negran_mumtahin'))->name('Negran_Mumtahin.negran_mumtahin');
Route::get('/negran-mumtahin-apply', fn() => Inertia::render('Negran_Mumtahin/negran_mumtahin_apply'))->name('Negran_Mumtahin.negran_mumtahin_apply');
Route::get('/mumtahin_list', fn() => Inertia::render('Negran_Mumtahin/mumtahin_list'))->name('Negran_Mumtahin.mumtahin_list');
Route::get('/negran_apoint_list', fn() => Inertia::render('Negran_Mumtahin/negran_apoint_list'))->name('Negran_Mumtahin.negran_apoint_list');
Route::get('/negran-view-detailes', fn() => Inertia::render('Negran_Mumtahin/negran-view-detailes'))->name('Negran_Mumtahin.negran-view-detailes');
Route::get('/neg-mumtahin-eidt', fn() => Inertia::render('Negran_Mumtahin/neg-mumtahin-eidt'))->name('Negran_Mumtahin.neg-mumtahin-eidt');

/**
 * --------------------------------------------------------------------------
 * Others
 * --------------------------------------------------------------------------
 */

Route::get('others/notice', fn() => Inertia::render('others/notice'))->name('others.notice');
Route::get('others/massaging', fn() => Inertia::render('others/massaging'))->name('others.massaging');

/**
 * --------------------------------------------------------------------------
 * Auth & About Markaz
 * --------------------------------------------------------------------------
 */

Route::get('Auth/madrasha_check_for_user', fn() => Inertia::render('Auth/madrasha_check_for_user'))->name('Auth.madrasha_check_for_user');
// Route::get('About_markaj/madrasha_list', fn() => Inertia::render('About_markaj/madrasha_list'))->name('About_markaj.madrasha_list');

/**
 * --------------------------------------------------------------------------
 * Marhala Routes
 * --------------------------------------------------------------------------
 */

Route::get('/marhalas', [MarhalaController::class, 'index'])->name('marhalas.index');
Route::post('/marhalas', [MarhalaController::class, 'store'])->name('marhalas.store');
Route::get('/marhalas/{id}/edit', [MarhalaController::class, 'edit'])->name('marhalas.edit');
Route::put('/marhalas/{id}', [MarhalaController::class, 'update'])->name('marhalas.update');
// Route::get('/Mrahala_for_Admin/marhala_details_list', [MarhalaListController::class, 'marhalaList'])->name('Mrahala_for_Admin.marhala_details_list');

/**
 * --------------------------------------------------------------------------
 * End of File
 * --------------------------------------------------------------------------
 */
