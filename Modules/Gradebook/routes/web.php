<?php

use Illuminate\Support\Facades\Route;
use Modules\Gradebook\Http\Controllers\GradebookController;
use Modules\Gradebook\Http\Controllers\Student\StudentGradebookController;
use Modules\Gradebook\Http\Controllers\Trainer\TrainerGradebookController;

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::group(['prefix' => 'admin/gradebook'], function () {
    Route::get('/', [GradebookController::class, 'students'])
        ->name('admin.gradebook.students');
    Route::get('appeals', [GradebookController::class, 'appeals'])
        ->name('admin.gradebook.appeals');
    Route::post('appeals/{id}/resolve', [GradebookController::class, 'resolveAppeal'])
        ->name('admin.gradebook.appeal.resolve');
    Route::get('transcript/{studentId}/{studentIntakeCourseId}', [GradebookController::class, 'transcript'])
        ->name('admin.gradebook.transcript');
    Route::get('{studentId}/{studentIntakeCourseId}', [GradebookController::class, 'index'])
        ->name('admin.gradebook.show');
});

// ── Student portal routes ─────────────────────────────────────────────────────
Route::group(['prefix' => 'student/gradebook'], function () {
    Route::get('/', [StudentGradebookController::class, 'courses'])
        ->name('student.gradebook.courses');
    Route::get('transcript/{studentIntakeCourseId}', [StudentGradebookController::class, 'transcript'])
        ->name('student.gradebook.transcript');
    Route::get('appeals', [StudentGradebookController::class, 'myAppeals'])
        ->name('student.gradebook.appeals');
    Route::post('appeal/{markType}/{markId}', [StudentGradebookController::class, 'submitAppeal'])
        ->name('student.gradebook.appeal.submit');
    Route::get('{studentIntakeCourseId}', [StudentGradebookController::class, 'index'])
        ->name('student.gradebook.show');
});

// ── Trainer portal routes ─────────────────────────────────────────────────────
Route::group(['prefix' => 'trainer/gradebook'], function () {
    Route::get('/', [TrainerGradebookController::class, 'index'])
        ->name('trainer.gradebook.index');
    Route::get('appeals/pending', [TrainerGradebookController::class, 'pendingAppeals'])
        ->name('trainer.gradebook.appeals');
    Route::post('appeals/{appealId}/respond', [TrainerGradebookController::class, 'respondToAppeal'])
        ->name('trainer.gradebook.appeal.respond');
    Route::get('{studentId}/{studentIntakeCourseId}', [TrainerGradebookController::class, 'show'])
        ->name('trainer.gradebook.show');
});
