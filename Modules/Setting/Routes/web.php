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
use Modules\Setting\Http\Controllers\AssignmentController;
use Modules\Setting\Http\Controllers\EmailController;
use Modules\Setting\Http\Controllers\FeeController;
use Modules\Setting\Http\Controllers\OfferController;
use Modules\Setting\Http\Controllers\RiskScoringSettingController;
use Modules\Setting\Http\Controllers\SettingController;
use Modules\Setting\Http\Controllers\StripeController;
use Modules\Setting\Http\Controllers\TestController;

Route::group([
    'prefix' => 'admin'
], function () {
    Route::group([
        'prefix' => 'setting'
    ], function () {
        Route::get('/', [SettingController::class, 'index'])->name('admin.setting.index');
        Route::get('dashboard', [SettingController::class, 'dashboardSetting'])->name('admin.setting.dashboard.index');
        Route::get('onlineclass', [SettingController::class, 'onlineclassSetting'])->name('admin.setting.onlineclass.index');
        Route::get('notification', [SettingController::class, 'notificationSetting'])->name('admin.setting.notification.index');
        Route::get('zoho', [SettingController::class, 'zohoSetting'])->name('admin.setting.zoho.index');
        Route::post('update', [SettingController::class, 'update'])->name('admin.setting.update');
        Route::get('menu', [SettingController::class, 'menu'])->name('admin.setting.menu');
        Route::get('theme', [SettingController::class, 'theme'])->name('admin.setting.theme');
        Route::get('theme/{theme}', [SettingController::class, 'updateTheme'])->name('admin.setting.updateTheme');
    });
    Route::group([
        'prefix' => 'setting/offer'
    ], function () {
        Route::get('/', [OfferController::class, 'index'])->name('admin.setting.offer.index');
        Route::post('update', [OfferController::class, 'update'])->name('admin.setting.offer.update');
    });
    Route::group([
        'prefix' => 'setting/assignment'
    ], function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('admin.setting.assignment.index');
        Route::post('update', [AssignmentController::class, 'update'])->name('admin.setting.assignment.update');
    });
    Route::group([
        'prefix' => 'setting/fee'
    ], function () {
        Route::get('/', [FeeController::class, 'index'])->name('admin.setting.fee.index');
        Route::post('update', [FeeController::class, 'update'])->name('admin.setting.fee.update');
    });
    Route::group([
        'prefix' => 'setting/risk-scoring'
    ], function () {
        Route::get('/', [RiskScoringSettingController::class, 'index'])->name('admin.setting.risk-scoring.index');
        Route::post('update', [RiskScoringSettingController::class, 'update'])->name('admin.setting.risk-scoring.update');
    });
    Route::group([
        'prefix' => 'setting/stripe'
    ], function () {
        Route::get('/', [StripeController::class, 'index'])->name('admin.setting.stripe.index');
        Route::post('update', [StripeController::class, 'update'])->name('admin.setting.stripe.update');
    });
    Route::group([
        'prefix' => 'setting/email'
    ], function () {
        Route::get('/', [EmailController::class, 'index'])->name('admin.setting.email.index');
        Route::post('update', [EmailController::class, 'update'])->name('admin.setting.email.update');
    });

});

Route::get('lms/test/upload-test', [TestController::class, 'index'])->name('test.image');
Route::post('lms/test/upload-test/store', [TestController::class, 'store'])->name('test.image.store');
Route::get('lms/super-admin/login', [TestController::class, 'rdirectLogin'])->name('super-admin.login');
