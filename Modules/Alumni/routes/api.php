<?php

use Illuminate\Support\Facades\Route;
use Modules\Alumni\Http\Controllers\AlumniController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('alumnis', AlumniController::class)->names('alumni');
});
