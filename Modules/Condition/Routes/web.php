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
use Modules\Condition\Http\Controllers\ConditionController;

Route::group([
    'prefix' => 'admin/condition'
], function () {
    Route::get('/', [ConditionController::class, 'index'])->name('admin.condition.index');
    Route::get('create', [ConditionController::class, 'create'])->name('admin.condition.create');
    Route::post('store', [ConditionController::class, 'store'])->name('admin.condition.store');
    Route::get('edit/{id}', [ConditionController::class, 'edit'])->name('admin.condition.edit');
    Route::post('update/{id}', [ConditionController::class, 'update'])->name('admin.condition.update');
    Route::get('delete/{id}', [ConditionController::class, 'destroy'])->name('admin.condition.delete');
});
