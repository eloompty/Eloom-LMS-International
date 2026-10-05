<?php

use Illuminate\Support\Facades\Route;
use Modules\Survey\Http\Controllers\SurveyController;
use Modules\Survey\Http\Controllers\Student\StudentSurveyController;
use Modules\Survey\Http\Controllers\Trainer\TrainerSurveyController;

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::group(['prefix' => 'admin/survey'], function () {
    Route::get('template', [SurveyController::class, 'templateIndex'])->name('admin.survey.template.index');
    Route::get('template/create', [SurveyController::class, 'templateCreate'])->name('admin.survey.template.create');
    Route::post('template/store', [SurveyController::class, 'templateStore'])->name('admin.survey.template.store');
    Route::get('template/delete/{id}', [SurveyController::class, 'templateDestroy'])->name('admin.survey.template.delete');

    Route::get('instance', [SurveyController::class, 'instanceIndex'])->name('admin.survey.instance.index');
    Route::get('instance/create', [SurveyController::class, 'instanceCreate'])->name('admin.survey.instance.create');
    Route::post('instance/store', [SurveyController::class, 'instanceStore'])->name('admin.survey.instance.store');
    Route::get('instance/{id}/results', [SurveyController::class, 'results'])->name('admin.survey.results');
});

// ── Trainer routes ────────────────────────────────────────────────────────────
Route::group(['prefix' => 'trainer/survey'], function () {
    Route::get('template', [TrainerSurveyController::class, 'templateIndex'])->name('trainer.survey.template.index');
    Route::get('template/create', [TrainerSurveyController::class, 'templateCreate'])->name('trainer.survey.template.create');
    Route::post('template/store', [TrainerSurveyController::class, 'templateStore'])->name('trainer.survey.template.store');
    Route::post('dispatch', [TrainerSurveyController::class, 'dispatch'])->name('trainer.survey.dispatch');
});

// ── Student routes ────────────────────────────────────────────────────────────
Route::group(['prefix' => 'student/survey'], function () {
    Route::get('/', [StudentSurveyController::class, 'index'])->name('student.survey.index');
    Route::get('take/{instanceId}', [StudentSurveyController::class, 'take'])->name('student.survey.take');
    Route::post('submit/{instanceId}', [StudentSurveyController::class, 'submit'])->name('student.survey.submit');
});
