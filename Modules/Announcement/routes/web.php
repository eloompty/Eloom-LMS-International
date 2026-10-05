<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcement\Http\Controllers\AnnouncementController;
use Modules\Announcement\Http\Controllers\Student\StudentAnnouncementController;
use Modules\Announcement\Http\Controllers\Trainer\TrainerAnnouncementController;

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::group(['prefix' => 'admin/announcement'], function () {
    Route::get('/', [AnnouncementController::class, 'index'])->name('admin.announcement.index');
    Route::get('create', [AnnouncementController::class, 'create'])->name('admin.announcement.create');
    Route::post('store', [AnnouncementController::class, 'store'])->name('admin.announcement.store');
    Route::get('edit/{id}', [AnnouncementController::class, 'edit'])->name('admin.announcement.edit');
    Route::post('update/{id}', [AnnouncementController::class, 'update'])->name('admin.announcement.update');
    Route::get('delete/{id}', [AnnouncementController::class, 'destroy'])->name('admin.announcement.delete');
});

// ── Student portal routes ─────────────────────────────────────────────────────
Route::group(['prefix' => 'student/announcement'], function () {
    Route::get('/', [StudentAnnouncementController::class, 'index'])->name('student.announcement.index');
    Route::post('acknowledge/{id}', [StudentAnnouncementController::class, 'acknowledge'])->name('student.announcement.acknowledge');
});

// ── Trainer portal routes ─────────────────────────────────────────────────────
Route::group(['prefix' => 'trainer/announcement'], function () {
    Route::get('/', [TrainerAnnouncementController::class, 'index'])->name('trainer.announcement.index');
    Route::get('create', [TrainerAnnouncementController::class, 'create'])->name('trainer.announcement.create');
    Route::post('store', [TrainerAnnouncementController::class, 'store'])->name('trainer.announcement.store');
    Route::post('acknowledge/{id}', [TrainerAnnouncementController::class, 'acknowledge'])->name('trainer.announcement.acknowledge');
});
