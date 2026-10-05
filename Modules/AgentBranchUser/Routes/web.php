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
use Modules\AgentBranchUser\Http\Controllers\DeviceController;
use Modules\AgentBranchUser\Http\Controllers\LogController;
use Modules\AgentBranchUser\Http\Controllers\User\AgentStudentController;
use Modules\AgentBranchUser\Http\Controllers\User\CommissionController;
use Modules\AgentBranchUser\Http\Controllers\User\LoginController;
use Modules\AgentBranchUser\Http\Controllers\User\StudentController;
use Modules\AgentBranchUser\Http\Controllers\User\StudentIntakeCourseController;
use Modules\AgentBranchUser\Http\Controllers\User\StudentOfferController;
use Modules\AgentBranchUser\Http\Controllers\User\UserController as UserUserController;
use Modules\AgentBranchUser\Http\Controllers\UserController;

Route::group([
    'prefix' => 'admin/agent/branch/user'
], function () {
    Route::get('/{id}', [UserController::class, 'index'])->name('admin.agent.branch.user.index');
    Route::get('create/{id}', [UserController::class, 'create'])->name('admin.agent.branch.user.create');
    Route::post('store/{id}', [UserController::class, 'store'])->name('admin.agent.branch.user.store');
    Route::get('edit/{id}', [UserController::class, 'edit'])->name('admin.agent.branch.user.edit');
    Route::post('update/{id}', [UserController::class, 'update'])->name('admin.agent.branch.user.update');
    Route::get('delete/{id}', [UserController::class, 'destroy'])->name('admin.agent.branch.user.delete');
    Route::get('log/{id}', [LogController::class, 'index'])->name('admin.agent.branch.user.log.index');
    Route::get('device/{id}', [DeviceController::class, 'index'])->name('admin.agent.branch.user.device.index');
});

Route::group([
    'prefix' => 'branch-user'
], function () {
    Route::get('/', [LoginController::class, 'home'])->name('branch-user.login');
    Route::get('login', [LoginController::class, 'home'])->name('branch-user.login');
    Route::post('login', [LoginController::class, 'login'])->name('branch-user.login');
    Route::post('save/device', [LoginController::class, 'saveToken'])->name('branch-user.device.save');
    Route::get('logout', [LoginController::class, 'logout'])->name('branch-user.logout')->middleware('auth:agent_branch_user');
    Route::group([
        'prefix' => 'password'
    ], function () {
        Route::get('forgot', [LoginController::class, 'forgotPassword'])->name('branch-user.password.forgot');
        Route::post('reset', [LoginController::class, 'resetPassword'])->name('branch-user.password.reset');
        Route::get('reset/{email}/{code}', [LoginController::class, 'resettingPassword']);
        Route::post('update', [LoginController::class, 'updatePassword'])->name('branch-user.password.update');
    });
    Route::get('dashboard', [UserUserController::class, 'dashboard'])->name('branch-user.dashboard');
    Route::get('profile', [UserUserController::class, 'profile'])->name('branch-user.profile');
    Route::get('change/password', [UserUserController::class, 'changePassword'])->name('branch-user.change.password');
    Route::post('fill/password', [UserUserController::class, 'fillPassword'])->name('branch-user.fill.password');
    Route::group([
        'prefix' => 'enrolled-student'
    ], function () {
        Route::get('/', [StudentController::class, 'index'])->name('branch-user.student.enrolled.index');
    });
    Route::group([
        'prefix' => 'student'
    ], function () {
        Route::get('/', [AgentStudentController::class, 'index'])->name('branch-user.student.index');
        Route::get('create', [AgentStudentController::class, 'create'])->name('branch-user.student.create');
        Route::post('store', [AgentStudentController::class, 'store'])->name('branch-user.student.store');
        Route::get('edit/{id}', [AgentStudentController::class, 'edit'])->name('branch-user.student.edit');
        Route::post('update/{id}', [AgentStudentController::class, 'update'])->name('branch-user.student.update');
        Route::group([
            'prefix' => 'intake'
        ], function () {
            Route::get('/{id}', [StudentIntakeCourseController::class, 'index'])->name('branch-user.student.intake.index');
            Route::get('create/{id}', [StudentIntakeCourseController::class, 'create'])->name('branch-user.student.intake.create');
            Route::post('store/{id}', [StudentIntakeCourseController::class, 'store'])->name('branch-user.student.intake.store');
            Route::get('edit/{id}', [StudentIntakeCourseController::class, 'edit'])->name('branch-user.student.intake.edit');
            Route::post('update/{id}', [StudentIntakeCourseController::class, 'update'])->name('branch-user.student.intake.update');
            Route::get('course/list', [StudentIntakeCourseController::class, 'getCourseByIntake']);
            Route::get('course/fee/list', [StudentIntakeCourseController::class, 'getIntakeCourseFee']);
        });
        Route::group([
            'prefix' => 'offer'
        ], function () {
            Route::get('/{id}', [StudentOfferController::class, 'index'])->name('branch-user.student.offer.index');
            Route::get('create/{id}', [StudentOfferController::class, 'create'])->name('branch-user.student.offer.create');
            Route::post('store/{id}', [StudentOfferController::class, 'store'])->name('branch-user.student.offer.store');
            Route::get('edit/{id}', [StudentOfferController::class, 'edit'])->name('branch-user.student.offer.edit');
            Route::post('update/{id}', [StudentOfferController::class, 'update'])->name('branch-user.student.offer.update');
            Route::get('print/{id}', [StudentOfferController::class, 'printPDF'])->name('branch-user.student.offer.print');
        });
    });
    Route::group([
        'prefix' => 'commission'
    ], function () {
        Route::get('/', [CommissionController::class, 'index'])->name('branch-user.commission.index');
    });
});