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
use Modules\Fee\Http\Controllers\FeeTypeController;

Route::group([
    'prefix' => 'admin/fee-type'
], function () {
    Route::get('/', [FeeTypeController::class, 'index'])->name('admin.fee-type.index');
    Route::get('create', [FeeTypeController::class, 'create'])->name('admin.fee-type.create');
    Route::post('store', [FeeTypeController::class, 'store'])->name('admin.fee-type.store');
    Route::get('edit/{id}', [FeeTypeController::class, 'edit'])->name('admin.fee-type.edit');
    Route::post('update/{id}', [FeeTypeController::class, 'update'])->name('admin.fee-type.update');
    Route::get('delete/{id}', [FeeTypeController::class, 'destroy'])->name('admin.fee-type.delete');
});
