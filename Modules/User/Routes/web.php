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
use Modules\User\Http\Controllers\AdminController;
use Modules\User\Http\Controllers\BackupController;
use Modules\User\Http\Controllers\DeliverySiteController;
use Modules\User\Http\Controllers\LoginController;
use Modules\User\Http\Controllers\RestoreController;
use Modules\User\Http\Controllers\RoleController;
use Modules\User\Http\Controllers\UserController;

Route::get('/', [LoginController::class, 'home'])->name('home');

Route::group([
    'prefix' => 'admin'
], function () {
    Route::post('register', [LoginController::class, 'register'])->name('register');
    Route::get('/', [LoginController::class, 'home'])->name('admin.login.home');
    Route::get('login', [LoginController::class, 'home'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::post('save/device', [LoginController::class, 'saveToken'])->name('admin.device.save');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:user');
    Route::group([
        'prefix' => 'password'
    ], function () {
        Route::get('forgot', [LoginController::class, 'forgotPassword'])->name('admin.password.forgot');
        Route::post('reset', [LoginController::class, 'resetPassword'])->name('admin.password.reset');
        Route::get('reset/{email}/{code}', [LoginController::class, 'resettingPassword']);
        Route::post('update', [LoginController::class, 'updatePassword'])->name('admin.password.update');
    });
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('theme', [UserController::class, 'toggleTheme'])->name('admin.theme');
    Route::get('profile',[UserController::class, 'index'])->name('admin.user');
    Route::post('profile/update/{id}',[UserController::class, 'update'])->name('admin.user.update');
    Route::group([
        'prefix' => 'role'
    ], function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.role.index');
        Route::get('create', [RoleController::class, 'create'])->name('admin.role.create');
        Route::post('store', [RoleController::class, 'store'])->name('admin.role.store');
        Route::get('edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
        Route::post('update/{id}', [RoleController::class, 'update'])->name('admin.role.update');
    });
    Route::group([
        'prefix' => 'user'
    ], function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.user.index');
        Route::get('create', [AdminController::class, 'create'])->name('admin.user.create');
        Route::post('store', [AdminController::class, 'store'])->name('admin.user.store');
        Route::get('edit/{id}', [AdminController::class, 'edit'])->name('admin.user.edit');
        Route::post('update/{id}', [AdminController::class, 'update'])->name('admin.user.update');
        Route::get('log/{id}', [AdminController::class, 'log'])->name('admin.user.log');
        Route::group([
            'prefix' => 'delivery_site'
        ], function() {
            Route::get('/{id}', [DeliverySiteController::class, 'index'])->name('admin.user.delivery.index');
            Route::get('create/{id}', [DeliverySiteController::class, 'create'])->name('admin.user.delivery.create');
            Route::post('store/{id}', [DeliverySiteController::class, 'store'])->name('admin.user.delivery.store');
            Route::get('edit/{id}', [DeliverySiteController::class, 'edit'])->name('admin.user.delivery.edit');
            Route::post('update/{id}', [DeliverySiteController::class, 'update'])->name('admin.user.delivery.update');
        });
    });
    Route::get('add-role', [RoleController::class, 'addRole']);
    Route::group([
        'prefix' => 'backup'
    ], function () {
        Route::get('/', [BackupController::class, 'index'])->name('admin.backup.index');
        Route::get('download', [BackupController::class, 'download'])->name('admin.backup.download');
        Route::post('restore', [RestoreController::class, 'restore'])->name('admin.backup.restore');
    });
});
