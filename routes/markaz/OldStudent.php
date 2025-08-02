<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\markaz\OldStudent\StudentSearchController;

Route::prefix('markaz/old-student')->group(function () {
    Route::get('/years', [StudentSearchController::class, 'getStudentYears']);
    Route::post('/search', [StudentSearchController::class, 'searchStudents']);
});
