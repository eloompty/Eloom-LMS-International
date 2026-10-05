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
use Modules\Credit\Http\Controllers\CreditController;

Route::group([
    'prefix' => 'admin/credit'
], function () {
    Route::get('/', [CreditController::class, 'index'])->name('admin.credit.index');
    Route::get('create', [CreditController::class, 'create'])->name('admin.credit.create');
    Route::post('store', [CreditController::class, 'store'])->name('admin.credit.store');
    Route::get('edit/{id}', [CreditController::class, 'edit'])->name('admin.credit.edit');
    Route::post('update/{id}', [CreditController::class, 'update'])->name('admin.credit.update');
    Route::get('delete/{id}', [CreditController::class, 'destroy'])->name('admin.credit.delete');
});
