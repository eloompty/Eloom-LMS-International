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
use Modules\Report\Http\Controllers\AgentController;
use Modules\Report\Http\Controllers\CommissionController;
use Modules\Report\Http\Controllers\CourseCompletionController;
use Modules\Report\Http\Controllers\DuePaysController;
use Modules\Report\Http\Controllers\FeeReceivedController;
use Modules\Report\Http\Controllers\IntakeController;
use Modules\Report\Http\Controllers\MarkController;
use Modules\Report\Http\Controllers\ReportController;
use Modules\Report\Http\Controllers\AnalyticsController;
use Modules\Report\Http\Controllers\RiskController;
use Modules\Report\Http\Controllers\StudentController;
use Modules\Report\Http\Controllers\TemplateController;

Route::group([
    'prefix' => 'admin/report'
], function () {
    Route::get('menu', [ReportController::class, 'menu'])->name('admin.report.menu');
    Route::group([
        'prefix' => 'student'
    ], function () {
        Route::get('/', [StudentController::class, 'index'])->name('admin.report.student.index');
        Route::get('show', [StudentController::class, 'show'])->name('admin.report.student.show');
        Route::get('print', [StudentController::class, 'print'])->name('admin.report.student.print');
    });
    Route::group([
        'prefix' => 'intake'
    ], function () {
        Route::get('/', [IntakeController::class, 'index'])->name('admin.report.intake.index');
        Route::get('show', [IntakeController::class, 'show'])->name('admin.report.intake.show');
        Route::get('print', [IntakeController::class, 'print'])->name('admin.report.intake.print');
    });
    Route::group([
        'prefix' => 'agent'
    ], function () {
        Route::get('/', [AgentController::class, 'index'])->name('admin.report.agent.index');
        Route::get('show', [AgentController::class, 'show'])->name('admin.report.agent.show');
        Route::get('print', [AgentController::class, 'print'])->name('admin.report.agent.print');
    });
    Route::group([
        'prefix' => 'due'
    ], function () {
        Route::get('/', [DuePaysController::class, 'index'])->name('admin.report.due.index');
        Route::get('show', [DuePaysController::class, 'show'])->name('admin.report.due.show');
        Route::get('print', [DuePaysController::class, 'print'])->name('admin.report.due.print');
    });
    Route::group([
        'prefix' => 'fee'
    ], function () {
        Route::get('/', [FeeReceivedController::class, 'index'])->name('admin.report.fee.index');
        Route::get('show', [FeeReceivedController::class, 'show'])->name('admin.report.fee.show');
        Route::get('print', [FeeReceivedController::class, 'print'])->name('admin.report.fee.print');
    });
    Route::group([
        'prefix' => 'commission'
    ], function () {
        Route::get('/', [CommissionController::class, 'index'])->name('admin.report.commission.index');
        Route::get('show', [CommissionController::class, 'show'])->name('admin.report.commission.show');
        Route::get('print', [CommissionController::class, 'print'])->name('admin.report.commission.print');
    });
    Route::group([
        'prefix' => 'course-completion'
    ], function () {
        Route::get('/', [CourseCompletionController::class, 'index'])->name('admin.report.course-completion.index');
        Route::get('show', [CourseCompletionController::class, 'show'])->name('admin.report.course-completion.show');
        Route::get('print', [CourseCompletionController::class, 'print'])->name('admin.report.course-completion.print');
    });
    Route::group([
        'prefix' => 'mark'
    ], function () {
        Route::get('/', [MarkController::class, 'index'])->name('admin.report.mark.index');
        Route::get('show', [MarkController::class, 'show'])->name('admin.report.mark.show');
        Route::get('print', [MarkController::class, 'print'])->name('admin.report.mark.print');
    });
    Route::group([
        'prefix' => 'analytics'
    ], function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('admin.report.analytics.index');
        Route::get('export-pdf', [AnalyticsController::class, 'exportPdf'])->name('admin.report.analytics.export-pdf');
        Route::get('export-csv', [AnalyticsController::class, 'exportCsv'])->name('admin.report.analytics.export-csv');
    });
    Route::group([
        'prefix' => 'risk'
    ], function () {
        Route::get('/', [RiskController::class, 'index'])->name('admin.report.risk.index');
        Route::get('student/{id}', [RiskController::class, 'show'])->name('admin.report.risk.show');
        Route::post('analyze', [RiskController::class, 'analyze'])->name('admin.report.risk.analyze');
        Route::get('export', [RiskController::class, 'export'])->name('admin.report.risk.export');
    });
    Route::group([
        'prefix' => 'template'
    ], function () {
        Route::get('/', [TemplateController::class, 'index'])->name('admin.report.template.index');
        Route::get('create', [TemplateController::class, 'create'])->name('admin.report.template.create');
        Route::post('store', [TemplateController::class, 'store'])->name('admin.report.template.store');
        Route::get('show/{id}', [TemplateController::class, 'show'])->name('admin.report.template.show');
        Route::get('edit/{id}', [TemplateController::class, 'edit'])->name('admin.report.template.edit');
        Route::post('update/{id}', [TemplateController::class, 'update'])->name('admin.report.template.update');
    });
});
