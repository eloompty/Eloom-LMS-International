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
use Modules\CRM\Http\Controllers\CRMController;
use Modules\CRM\Http\Controllers\ZohoController;

Route::group([
    'prefix' => 'admin'
], function () {
    Route::group([
        'prefix' => 'lead'
    ], function () {
        Route::get('/', [CRMController::class, 'index'])->name('admin.lead.index');
        Route::get('create', [CRMController::class, 'create'])->name('admin.lead.create');
        Route::post('store', [CRMController::class, 'store'])->name('admin.lead.store');
        Route::get('edit/{id}', [CRMController::class, 'edit'])->name('admin.lead.edit');
        Route::post('update/{id}', [CRMController::class, 'update'])->name('admin.lead.update');
        Route::get('delete/{id}', [CRMController::class, 'destroy'])->name('admin.lead.delete');
    });
    Route::group([
        'prefix' => 'zoho'
    ], function () {
        Route::get('/', [ZohoController::class, 'index'])->name('admin.zoho.index');
        Route::get('create', [ZohoController::class, 'create'])->name('admin.zoho.create');
        Route::post('store', [ZohoController::class, 'store'])->name('admin.zoho.store');
        Route::get('edit/{id}', [ZohoController::class, 'edit'])->name('admin.zoho.edit');
        Route::post('update/{id}', [ZohoController::class, 'update'])->name('admin.zoho.update');
        Route::get('delete/{id}', [ZohoController::class, 'destroy'])->name('admin.zoho.delete');
        Route::get('auth', [ZohoController::class, 'redirectToZoho'])->name('admin.zoho.auth');
    });
});
Route::get('zoho/callback', [ZohoController::class, 'handleZohoCallback'])->name('admin.zoho.callback');
