<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\OnlineClass\Http\Controllers\OnlineClassController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'meetings'
], function () {
    // Get list of meetings.
    Route::get('/', [OnlineClassController::class, 'index']);
    // Create meeting room using topic, agenda, start_time.
    Route::post('/', [OnlineClassController::class, 'create']);
    // Get information of the meeting room by ID.
    Route::get('/{id}', [OnlineClassController::class, 'show'])->where('id', '[0-9]+');
    Route::patch('/{id}', [OnlineClassController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/{id}', [OnlineClassController::class, 'destroy'])->where('id', '[0-9]+');
});
