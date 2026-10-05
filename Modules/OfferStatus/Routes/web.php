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
use Modules\OfferStatus\Http\Controllers\OfferStatusController;

Route::group([
    'prefix' => 'admin/offer-status'
], function () {
    Route::get('/', [OfferStatusController::class, 'index'])->name('admin.offer.status.index');
    Route::get('create', [OfferStatusController::class, 'create'])->name('admin.offer.status.create');
    Route::post('store', [OfferStatusController::class, 'store'])->name('admin.offer.status.store');
    Route::get('edit/{id}', [OfferStatusController::class, 'edit'])->name('admin.offer.status.edit');
    Route::post('update/{id}', [OfferStatusController::class, 'update'])->name('admin.offer.status.update');
    Route::get('delete/{id}', [OfferStatusController::class, 'destroy'])->name('admin.offer.status.delete');
});
