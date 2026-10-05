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
use Modules\Marking\Http\Controllers\MarkingTypeController;

Route::group([
    'prefix' => 'admin/marking-type'
], function () {
    Route::get('/', [MarkingTypeController::class, 'index'])->name('admin.marking-type.index');
    Route::get('create', [MarkingTypeController::class, 'create'])->name('admin.marking-type.create');
    Route::post('store', [MarkingTypeController::class, 'store'])->name('admin.marking-type.store');
    Route::get('edit/{id}', [MarkingTypeController::class, 'edit'])->name('admin.marking-type.edit');
    Route::post('update/{id}', [MarkingTypeController::class, 'update'])->name('admin.marking-type.update');
    Route::get('delete/{id}', [MarkingTypeController::class, 'destroy'])->name('admin.marking-type.delete');
});

