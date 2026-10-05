<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\OnlineClass\Http\Controllers\MicrosoftOAuthController;
use Modules\OnlineClass\Http\Controllers\OnlineClassGroupClassController;
use Modules\OnlineClass\Http\Controllers\OnlineClassGroupController;
use Modules\OnlineClass\Http\Controllers\OnlineClassTeamController;

Route::group([
    'prefix' => '/admin/onlineclass/group'
], function () {
    Route::get('/', [OnlineClassGroupController::class, 'index'])->name('admin.onlineclass.group.index');
    Route::get('create', [OnlineClassGroupController::class, 'create'])->name('admin.onlineclass.group.create');
    Route::post('store', [OnlineClassGroupController::class, 'store'])->name('admin.onlineclass.group.store');
    Route::get('edit/{id}', [OnlineClassGroupController::class, 'edit'])->name('admin.onlineclass.group.edit');
    Route::post('update/{id}', [OnlineClassGroupController::class, 'update'])->name('admin.onlineclass.group.update');
    Route::group([
        'prefix' => 'online'
    ], function () {
        Route::get('/{id}', [OnlineClassGroupClassController::class, 'index'])->name('admin.onlineclass.group.class.index');
        Route::get('create/{id}', [OnlineClassGroupClassController::class, 'create'])->name('admin.onlineclass.group.class.create');
        Route::post('store/{id}', [OnlineClassGroupClassController::class, 'store'])->name('admin.onlineclass.group.class.store');
    });
    Route::get('online_recording/{id}', [OnlineClassGroupClassController::class, 'recording'])->name('admin.onlineclass.group.class.recording');
    Route::group([
        'prefix' => 'teams'
    ], function () {
        Route::get('/{id}', [OnlineClassTeamController::class, 'index'])->name('admin.onlineclass.group.teams.index');
        Route::post('store', [OnlineClassTeamController::class, 'store'])->name('admin.onlineclass.group.teams.store');
    });
});

Route::get('login/microsoft/{id}/{type}', [MicrosoftOAuthController::class, 'redirect'])->name('login.microsoft');
Route::get('callback', [MicrosoftOAuthController::class, 'callback'])->name('callback');