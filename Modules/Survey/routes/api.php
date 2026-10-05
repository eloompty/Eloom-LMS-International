<?php

use Illuminate\Support\Facades\Route;
use Modules\Survey\Http\Controllers\SurveyController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('surveys', SurveyController::class)->names('survey');
});
