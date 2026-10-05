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
use Modules\University\Http\Controllers\UniversityController;
use Modules\University\Http\Controllers\UniversityQualificationController;

Route::group([
    'prefix' => 'admin/university'
], function () {
    Route::get('/', [UniversityController::class, 'index'])->name('admin.university.index');
    Route::get('create', [UniversityController::class, 'create'])->name('admin.university.create');
    Route::post('store', [UniversityController::class, 'store'])->name('admin.university.store');
    Route::get('edit/{id}', [UniversityController::class, 'edit'])->name('admin.university.edit');
    Route::post('update/{id}', [UniversityController::class, 'update'])->name('admin.university.update');
    Route::get('destroy/{id}', [UniversityController::class, 'destroy'])->name('admin.university.delete');
    Route::group([
        'prefix' => 'qualification'
    ], function () {
        Route::get('/{id}', [UniversityQualificationController::class, 'index'])->name('admin.university.qualification.index');
        Route::get('create/{id}', [UniversityQualificationController::class, 'create'])->name('admin.university.qualification.create');
        Route::post('store/{id}', [UniversityQualificationController::class, 'store'])->name('admin.university.qualification.store');
        Route::get('edit/{id}', [UniversityQualificationController::class, 'edit'])->name('admin.university.qualification.edit');
        Route::post('update/{id}', [UniversityQualificationController::class, 'update'])->name('admin.university.qualification.update');
        Route::get('destroy/{id}', [UniversityQualificationController::class, 'destroy'])->name('admin.university.qualification.delete');
    });
});