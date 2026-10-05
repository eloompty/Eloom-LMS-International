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
use Modules\Assignment\Http\Controllers\AssignmentCommentController;
use Modules\Assignment\Http\Controllers\AssignmentController;
use Modules\Assignment\Http\Controllers\AssignmentFileController;
use Modules\Assignment\Http\Controllers\AssignmentGradeController;
use Modules\Assignment\Http\Controllers\AssignmentSubmissionController;
use Modules\Assignment\Http\Controllers\ResubmissionController;
use Modules\Assignment\Http\Controllers\SubmissionController;

Route::group([
    'prefix' => 'admin'
], function () {
    Route::group([
        'prefix' => 'assignment'
    ], function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('admin.assignment.index');
        Route::get('edit/{id}', [AssignmentController::class, 'edit'])->name('admin.assignment.edit');
        Route::post('update/{id}', [AssignmentController::class, 'update'])->name('admin.assignment.update');
        Route::get('menu', [AssignmentController::class, 'menu'])->name('admin.assignment.menu');
        Route::get('question/{id}', [AssignmentController::class, 'question'])->name('admin.assignment.question');
        Route::get('mcq/{id}', [AssignmentController::class, 'mcq'])->name('admin.assignment.mcq');
        Route::get('files/{id}', [AssignmentFileController::class, 'index'])->name('admin.assignment.files');
        Route::group([
            'prefix' => 'grade'
        ], function () {
            Route::get('/', [AssignmentGradeController::class, 'index'])->name('admin.assignment.grade.index');
            Route::get('create', [AssignmentGradeController::class, 'create'])->name('admin.assignment.grade.create');
            Route::post('store', [AssignmentGradeController::class, 'store'])->name('admin.assignment.grade.store');
            Route::get('edit/{id}', [AssignmentGradeController::class, 'edit'])->name('admin.assignment.grade.edit');
            Route::post('update/{id}', [AssignmentGradeController::class, 'update'])->name('admin.assignment.grade.update');
            Route::get('delete/{id}', [AssignmentGradeController::class, 'destroy'])->name('admin.assignment.grade.delete');
        });
        Route::group([
            'prefix' => 'submission'
        ], function () {
            Route::get('/{id}', [AssignmentSubmissionController::class, 'index'])->name('admin.assignment.submission.index');
            Route::get('edit/{id}', [AssignmentSubmissionController::class, 'edit'])->name('admin.assignment.submission.edit');
            Route::post('update/{id}', [AssignmentSubmissionController::class, 'update'])->name('admin.assignment.submission.update');
            Route::get('question/{id}', [AssignmentSubmissionController::class, 'question'])->name('admin.assignment.submission.question');
            Route::get('files/{id}', [AssignmentSubmissionController::class, 'files'])->name('admin.assignment.submission.files');
            Route::get('files/edit/{id}', [AssignmentSubmissionController::class, 'filesEdit'])->name('admin.assignment.submission.files.edit');
            Route::post('files/update/{id}', [AssignmentSubmissionController::class, 'filesUpdate'])->name('admin.assignment.submission.files.upate');
            Route::get('mcq/{id}', [AssignmentSubmissionController::class, 'mcq'])->name('admin.assignment.submission.mcq');
            Route::get('pdf/{id}/{type}', [AssignmentSubmissionController::class, 'pdf'])->name('admin.assignment.submission.pdf');
            Route::post('pdfshow/{id}/{type}/save', [AssignmentSubmissionController::class, 'pdfsave'])->name('admin.assignment.submission.pdfsave');
        });
        Route::group([
            'prefix' => 'comment'
        ], function () {
            Route::get('/{id}', [AssignmentCommentController::class, 'index'])->name('admin.assignment.comment.index');
        });
    });
    Route::group([
        'prefix' => 'submission'
    ], function () {
        Route::get('/', [SubmissionController::class, 'index'])->name('admin.submission.index');
        Route::get('edit/{id}', [SubmissionController::class, 'edit'])->name('admin.submission.edit');
        Route::post('update/{id}', [SubmissionController::class, 'update'])->name('admin.submission.update');
        Route::get('files/{id}', [SubmissionController::class, 'files'])->name('admin.submission.files');
        Route::get('files/edit/{id}', [SubmissionController::class, 'filesEdit'])->name('admin.submission.files.edit');
        Route::post('files/update/{id}', [SubmissionController::class, 'filesUpdate'])->name('admin.submission.files.update');
        Route::get('question/{id}', [SubmissionController::class, 'question'])->name('admin.submission.question');
        Route::post('question/remarks/{id}', [SubmissionController::class, 'questionRemarks'])->name('admin.submission.question.remarks');
        Route::get('mcq/{id}', [SubmissionController::class, 'mcq'])->name('admin.submission.mcq');
        Route::post('mcq/remarks/{id}', [SubmissionController::class, 'mcqRemarks'])->name('admin.submission.mcq.remarks');
        Route::get('search', [SubmissionController::class, 'search'])->name('admin.submission.search');
        Route::get('pdf/{id}/{type}', [SubmissionController::class, 'pdf'])->name('admin.submission.pdf');
        Route::post('pdfshow/{id}/{type}/save', [SubmissionController::class, 'pdfsave'])->name('admin.submission.pdfsave');
    });
    Route::group([
        'prefix' => 'resubmission'
    ], function () {
        Route::get('/', [ResubmissionController::class, 'index'])->name('admin.resubmission.index');
        Route::get('edit/{id}', [ResubmissionController::class, 'edit'])->name('admin.resubmission.edit');
        Route::post('update/{id}', [ResubmissionController::class, 'update'])->name('admin.resubmission.update');
    });
});
