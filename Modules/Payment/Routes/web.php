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
use Modules\Payment\Http\Controllers\PaymentController;

Route::group([
    'prefix' => 'admin/payment'
], function () {
    Route::get('/', [PaymentController::class, 'index'])->name('admin.payment.index');
    Route::get('create', [PaymentController::class, 'create'])->name('admin.payment.create');
    Route::post('store', [PaymentController::class, 'store'])->name('admin.payment.store');
    Route::get('edit/{id}', [PaymentController::class, 'edit'])->name('admin.payment.edit');
    Route::post('update/{id}', [PaymentController::class, 'update'])->name('admin.payment.update');
    Route::get('destroy/{id}', [PaymentController::class, 'destroy'])->name('admin.payment.delete');
});