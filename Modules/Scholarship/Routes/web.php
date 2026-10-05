<?php

use Illuminate\Support\Facades\Route;
use Modules\Scholarship\Http\Controllers\ScholarshipController;
use Modules\Scholarship\Http\Controllers\ScholarshipApplicationController;
use Modules\Scholarship\Http\Controllers\FeeDiscountController;

// Scholarship Programs
Route::group(['prefix' => 'admin/scholarship'], function () {
    Route::get('/', [ScholarshipController::class, 'index'])->name('admin.scholarship.index');
    Route::get('create', [ScholarshipController::class, 'create'])->name('admin.scholarship.create');
    Route::post('store', [ScholarshipController::class, 'store'])->name('admin.scholarship.store');
    Route::get('edit/{id}', [ScholarshipController::class, 'edit'])->name('admin.scholarship.edit');
    Route::post('update/{id}', [ScholarshipController::class, 'update'])->name('admin.scholarship.update');
    Route::get('delete/{id}', [ScholarshipController::class, 'destroy'])->name('admin.scholarship.delete');
});

// Scholarship Applications
Route::group(['prefix' => 'admin/scholarship/application'], function () {
    Route::get('/', [ScholarshipApplicationController::class, 'index'])->name('admin.scholarship.application.index');
    Route::get('create/{student_id}/{fee_id}', [ScholarshipApplicationController::class, 'create'])->name('admin.scholarship.application.create');
    Route::post('store', [ScholarshipApplicationController::class, 'store'])->name('admin.scholarship.application.store');
    Route::get('show/{id}', [ScholarshipApplicationController::class, 'show'])->name('admin.scholarship.application.show');
    Route::get('review/{id}', [ScholarshipApplicationController::class, 'review'])->name('admin.scholarship.application.review');
    Route::post('approve/{id}', [ScholarshipApplicationController::class, 'approve'])->name('admin.scholarship.application.approve');
    Route::post('reject/{id}', [ScholarshipApplicationController::class, 'reject'])->name('admin.scholarship.application.reject');
    Route::post('revoke/{id}', [ScholarshipApplicationController::class, 'revoke'])->name('admin.scholarship.application.revoke');
});

// Fee Discounts — Revenue Impact Report
Route::group(['prefix' => 'admin/scholarship/discount'], function () {
    Route::get('/', [FeeDiscountController::class, 'index'])->name('admin.scholarship.discount.index');
    Route::get('delete/{id}', [FeeDiscountController::class, 'destroy'])->name('admin.scholarship.discount.delete');
});
