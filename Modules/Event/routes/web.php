<?php

use Illuminate\Support\Facades\Route;
use Modules\Event\Http\Controllers\EventController;
use Modules\Event\Http\Controllers\Student\StudentEventController;
use Modules\Event\Http\Controllers\Trainer\TrainerEventController;

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::group(['prefix' => 'admin/event'], function () {
    Route::get('/', [EventController::class, 'index'])->name('admin.event.index');
    Route::get('create', [EventController::class, 'create'])->name('admin.event.create');
    Route::post('store', [EventController::class, 'store'])->name('admin.event.store');
    Route::get('show/{id}', [EventController::class, 'show'])->name('admin.event.show');
    Route::get('edit/{id}', [EventController::class, 'edit'])->name('admin.event.edit');
    Route::post('update/{id}', [EventController::class, 'update'])->name('admin.event.update');
    Route::get('delete/{id}', [EventController::class, 'destroy'])->name('admin.event.delete');
    Route::post('{id}/attendance', [EventController::class, 'markAttendance'])->name('admin.event.attendance');
    Route::get('{id}/promote-waitlist', [EventController::class, 'promoteWaitlist'])->name('admin.event.promote');
});

// ── Trainer portal routes ─────────────────────────────────────────────────────
Route::group(['prefix' => 'trainer/event'], function () {
    Route::get('/', [TrainerEventController::class, 'index'])->name('trainer.event.index');
});

// ── Student portal routes ─────────────────────────────────────────────────────
Route::group(['prefix' => 'student/event'], function () {
    Route::get('/', [StudentEventController::class, 'index'])->name('student.event.index');
    Route::post('register/{eventId}', [StudentEventController::class, 'register'])->name('student.event.register');
    Route::get('cancel/{eventId}', [StudentEventController::class, 'cancel'])->name('student.event.cancel');
});
