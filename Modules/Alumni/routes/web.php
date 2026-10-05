<?php

use Illuminate\Support\Facades\Route;
use Modules\Alumni\Http\Controllers\AlumniController;
use Modules\Alumni\Http\Controllers\Student\StudentAlumniController;

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::group(['prefix' => 'admin/alumni'], function () {
    Route::get('/', [AlumniController::class, 'index'])->name('admin.alumni.index');
    Route::get('create', [AlumniController::class, 'create'])->name('admin.alumni.create');
    Route::post('store', [AlumniController::class, 'store'])->name('admin.alumni.store');
    Route::get('show/{id}', [AlumniController::class, 'show'])->name('admin.alumni.show');
    Route::get('directory', [AlumniController::class, 'directory'])->name('admin.alumni.directory');
    Route::get('{id}/convert-lead', [AlumniController::class, 'convertToLead'])->name('admin.alumni.convert');
});

// ── Student portal routes ─────────────────────────────────────────────────────
Route::group(['prefix' => 'student/alumni'], function () {
    Route::get('profile', [StudentAlumniController::class, 'profile'])->name('student.alumni.profile');
    Route::post('employment', [StudentAlumniController::class, 'updateEmployment'])->name('student.alumni.employment');
    Route::post('reenrollment', [StudentAlumniController::class, 'expressReenrollmentInterest'])->name('student.alumni.reenrollment');
});
