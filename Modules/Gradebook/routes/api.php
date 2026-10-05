<?php

use Illuminate\Support\Facades\Route;
use Modules\Gradebook\Http\Controllers\GradebookController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('gradebooks', GradebookController::class)->names('gradebook');
});
