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
use Modules\Email\Http\Controllers\EmailController;
use Modules\Email\Http\Controllers\EmailTemplateController;
use Modules\Email\Http\Controllers\EmailUserController;

Route::group([
    'prefix' => 'admin/email'
], function () {
    Route::get('/', [EmailController::class, 'index'])->name('admin.email.index');
    Route::get('create', [EmailController::class, 'create'])->name('admin.email.create');
    Route::post('store', [EmailController::class, 'store'])->name('admin.email.store');
    Route::get('edit/{id}', [EmailController::class, 'edit'])->name('admin.email.edit');
    Route::post('update/{id}', [EmailController::class, 'update'])->name('admin.email.update');
    Route::get('delete/{id}', [EmailController::class, 'destroy'])->name('admin.email.delete');
});

Route::group([
    'prefix' => 'admin/temp_email'
], function () {
    Route::get('/', [EmailTemplateController::class, 'index'])->name('admin.email.template.index');
    Route::get('create', [EmailTemplateController::class, 'create'])->name('admin.email.template.create');
    Route::post('store', [EmailTemplateController::class, 'store'])->name('admin.email.template.store');
    Route::get('edit/{id}', [EmailTemplateController::class, 'edit'])->name('admin.email.template.edit');
    Route::post('update/{id}', [EmailTemplateController::class, 'update'])->name('admin.email.template.update');
    Route::get('delete/{id}', [EmailTemplateController::class, 'destroy'])->name('admin.email.template.delete');
});

Route::group([
    'prefix' => 'admin/send_email_user'
], function () {
    Route::get('/', [EmailUserController::class, 'index'])->name('admin.email.user.index');
    Route::get('create', [EmailUserController::class, 'create'])->name('admin.email.user.create');
    Route::post('store', [EmailUserController::class, 'store'])->name('admin.email.user.store');
});