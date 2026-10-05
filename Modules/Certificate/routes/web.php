<?php

use Illuminate\Support\Facades\Route;
use Modules\Certificate\Http\Controllers\CertificateController;
use Modules\Certificate\Http\Controllers\Student\StudentCertificateController;

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::group(['prefix' => 'admin/certificate'], function () {
    // Templates
    Route::get('template', [CertificateController::class, 'templateIndex'])->name('admin.certificate.template.index');
    Route::get('template/create', [CertificateController::class, 'templateCreate'])->name('admin.certificate.template.create');
    Route::post('template/store', [CertificateController::class, 'templateStore'])->name('admin.certificate.template.store');
    Route::get('template/edit/{id}', [CertificateController::class, 'templateEdit'])->name('admin.certificate.template.edit');
    Route::post('template/update/{id}', [CertificateController::class, 'templateUpdate'])->name('admin.certificate.template.update');
    Route::get('template/delete/{id}', [CertificateController::class, 'templateDestroy'])->name('admin.certificate.template.delete');

    // Issued certificates
    Route::get('issued', [CertificateController::class, 'issuedIndex'])->name('admin.certificate.issued.index');
    Route::get('issued/create', [CertificateController::class, 'issueForm'])->name('admin.certificate.issued.create');
    Route::post('issued/store', [CertificateController::class, 'issue'])->name('admin.certificate.issued.store');
    Route::post('issued/bulk', [CertificateController::class, 'bulkIssue'])->name('admin.certificate.issued.bulk');
    Route::get('issued/download/{id}', [CertificateController::class, 'download'])->name('admin.certificate.issued.download');
    Route::post('issued/revoke/{id}', [CertificateController::class, 'revoke'])->name('admin.certificate.issued.revoke');
});

// ── Student portal routes ─────────────────────────────────────────────────────
Route::group(['prefix' => 'student/certificate'], function () {
    Route::get('/', [StudentCertificateController::class, 'index'])->name('student.certificate.index');
    Route::get('download/{uuid}', [StudentCertificateController::class, 'download'])->name('student.certificate.download');
});

// ── Public verification (no auth) ─────────────────────────────────────────────
Route::get('certificate/verify/{uuid}', [CertificateController::class, 'verify'])->name('certificate.verify');
