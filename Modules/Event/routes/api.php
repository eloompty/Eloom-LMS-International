<?php

use Illuminate\Support\Facades\Route;
use Modules\Event\Http\Controllers\EventController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('events', EventController::class)->names('event');
});
