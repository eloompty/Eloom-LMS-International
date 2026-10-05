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
use Modules\Company\Http\Controllers\CompanyController;
use Modules\Company\Http\Controllers\CompanyDeliverySiteController;

Route::group([
    'prefix' => 'admin/company'
], function () {
    Route::get('/', [CompanyController::class, 'index'])->name('admin.company');
    Route::post('update/{id}', [CompanyController::class, 'update'])->name('admin.compnay.update');
    Route::get('menu', [CompanyController::class, 'menu'])->name('admin.company.menu');
    Route::group([
        'prefix' => 'delivery/site'
    ], function () {
        Route::get('/', [CompanyDeliverySiteController::class, 'index'])->name('admin.company.delivery.index');
        Route::get('create', [CompanyDeliverySiteController::class, 'create'])->name('admin.company.delivery.create');
        Route::post('store', [CompanyDeliverySiteController::class, 'store'])->name('admin.company.delivery.store');
        Route::get('edit/{id}', [CompanyDeliverySiteController::class, 'edit'])->name('admin.company.delivery.edit');
        Route::post('update/{id}', [CompanyDeliverySiteController::class, 'update'])->name('admin.company.delivery.update');
        Route::get('delete/{id}', [CompanyDeliverySiteController::class, 'destroy'])->name('admin.company.delivery.delete');
    });
});