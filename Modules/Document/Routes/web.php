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
use Modules\Document\Http\Controllers\DocumentTypeController;
use Modules\Document\Http\Controllers\StudentDocumentTypeController;

Route::group([
    'prefix' => 'admin/document'
], function () {
    Route::group([
        'prefix' => 'type'
    ], function () {
        Route::get('/', [DocumentTypeController::class, 'index'])->name('admin.document.type.index');
        Route::get('create', [DocumentTypeController::class, 'create'])->name('admin.document.type.create');
        Route::post('store', [DocumentTypeController::class, 'store'])->name('admin.document.type.store');
        Route::get('edit/{id}', [DocumentTypeController::class, 'edit'])->name('admin.document.type.edit');
        Route::post('update/{id}', [DocumentTypeController::class, 'update'])->name('admin.document.type.update');
        Route::get('delete/{id}', [DocumentTypeController::class, 'destroy'])->name('admin.document.type.delete');
    });
    Route::group([
        'prefix' => 'student-type'
    ], function () {
        Route::get('/', [StudentDocumentTypeController::class, 'index'])->name('admin.document.type.student.index');
        Route::get('create', [StudentDocumentTypeController::class, 'create'])->name('admin.document.type.student.create');
        Route::post('store', [StudentDocumentTypeController::class, 'store'])->name('admin.document.type.student.store');
        Route::get('edit/{id}', [StudentDocumentTypeController::class, 'edit'])->name('admin.document.type.student.edit');
        Route::post('update/{id}', [StudentDocumentTypeController::class, 'update'])->name('admin.document.type.student.update');
        Route::get('delete/{id}', [StudentDocumentTypeController::class, 'destroy'])->name('admin.document.type.student.delete');
    });
});
