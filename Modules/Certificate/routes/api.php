<?php

use Illuminate\Support\Facades\Route;
use Modules\Certificate\Http\Controllers\CertificateController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('certificates', CertificateController::class)->names('certificate');
});
