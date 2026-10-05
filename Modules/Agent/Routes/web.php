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
use Modules\Agent\Http\Controllers\Agent\AgentController as AgentAgentController;
use Modules\Agent\Http\Controllers\Agent\AgentStudentController;
use Modules\Agent\Http\Controllers\Agent\BranchController as AgentBranchController;
use Modules\Agent\Http\Controllers\Agent\CommissionController;
use Modules\Agent\Http\Controllers\Agent\LoginController;
use Modules\Agent\Http\Controllers\Agent\StudentController;
use Modules\Agent\Http\Controllers\Agent\StudentIntakeCourseController;
use Modules\Agent\Http\Controllers\Agent\StudentOfferController;
use Modules\Agent\Http\Controllers\Agent\UserController;
use Modules\Agent\Http\Controllers\AgentController;
use Modules\Agent\Http\Controllers\AgentStudentController as ControllersAgentStudentController;
use Modules\Agent\Http\Controllers\BranchController;
use Modules\Agent\Http\Controllers\CommissionController as ControllersCommissionController;
use Modules\Agent\Http\Controllers\DeviceController;
use Modules\Agent\Http\Controllers\LogController;
use Modules\Agent\Http\Controllers\StudentController as ControllersStudentController;

Route::group([
    'prefix' => 'admin/agent'
], function () {
    Route::get('/', [AgentController::class, 'index'])->name('admin.agent.index');
    Route::get('create', [AgentController::class, 'create'])->name('admin.agent.create');
    Route::post('store', [AgentController::class, 'store'])->name('admin.agent.store');
    Route::get('edit/{id}', [AgentController::class, 'edit'])->name('admin.agent.edit');
    Route::post('update/{id}', [AgentController::class, 'update'])->name('admin.agent.update');
    Route::get('delete/{id}', [AgentController::class, 'destroy'])->name('admin.agent.delete');
    Route::group([
        'prefix' => 'branch'
    ], function () {
        Route::get('/{id}', [BranchController::class, 'index'])->name('admin.agent.branch.index');
        Route::get('create/{id}', [BranchController::class, 'create'])->name('admin.agent.branch.create');
        Route::post('store/{id}', [BranchController::class, 'store'])->name('admin.agent.branch.store');
        Route::get('edit/{id}', [BranchController::class, 'edit'])->name('admin.agent.branch.edit');
        Route::post('update/{id}', [BranchController::class, 'update'])->name('admin.agent.branch.update');
        Route::get('delete/{id}', [BranchController::class, 'destroy'])->name('admin.agent.branch.delete');
    });
    Route::get('enrolled-student/{id}', [ControllersStudentController::class, 'index'])->name('admin.agent.student.enrolled.index');
    Route::get('student/{id}', [ControllersAgentStudentController::class, 'index'])->name('admin.agent.student.index');
    Route::get('log/{id}', [LogController::class, 'index'])->name('admin.agent.log.index');
    Route::get('device/{id}', [DeviceController::class, 'index'])->name('admin.agent.device.index');
    Route::get('commission/{id}', [ControllersCommissionController::class, 'index'])->name('admin.agent.commission.index');
    Route::get('commission/pay/{id}', [ControllersCommissionController::class, 'payCommission'])->name('admin.agent.commission.pay');
    Route::post('commission/payment/{id}', [ControllersCommissionController::class, 'paymentCommission'])->name('admin.agent.commission.payment');
    Route::get('commission/receipt/{id}', [ControllersCommissionController::class, 'receipt'])->name('admin.agent.commission.receipt');
});

Route::group([
    'prefix' => 'agent'
], function () {
    Route::get('/', [LoginController::class, 'home'])->name('agent.login');
    Route::get('login', [LoginController::class, 'home'])->name('agent.login');
    Route::post('login', [LoginController::class, 'login'])->name('agent.login');
    Route::post('save/device', [LoginController::class, 'saveToken'])->name('agent.device.save');
    Route::get('logout', [LoginController::class, 'logout'])->name('agent.logout')->middleware('auth:agent');
    Route::group([
        'prefix' => 'password'
    ], function () {
        Route::get('forgot', [LoginController::class, 'forgotPassword'])->name('agent.password.forgot');
        Route::post('reset', [LoginController::class, 'resetPassword'])->name('agent.password.reset');
        Route::get('reset/{email}/{code}', [LoginController::class, 'resettingPassword']);
        Route::post('update', [LoginController::class, 'updatePassword'])->name('agent.password.update');
    });
    Route::get('dashboard', [AgentAgentController::class, 'dashboard'])->name('agent.dashboard');
    Route::get('profile', [AgentAgentController::class, 'profile'])->name('agent.profile');
    Route::get('change/password', [AgentAgentController::class, 'changePassword'])->name('agent.change.password');
    Route::post('fill/password', [AgentAgentController::class, 'fillPassword'])->name('agent.fill.password');
    Route::group([
        'prefix' => 'branch'
    ], function () {
        Route::get('/', [AgentBranchController::class, 'index'])->name('agent.branch.index');
        Route::get('create', [AgentBranchController::class, 'create'])->name('agent.branch.create');
        Route::post('store', [AgentBranchController::class, 'store'])->name('agent.branch.store');
        Route::get('edit/{id}', [AgentBranchController::class, 'edit'])->name('agent.branch.edit');
        Route::post('update/{id}', [AgentBranchController::class, 'update'])->name('agent.branch.update');
    });
    Route::group([
        'prefix' => 'user'
    ], function () {
        Route::get('/', [UserController::class, 'index'])->name('agent.user.index');
        Route::get('create', [UserController::class, 'create'])->name('agent.user.create');
        Route::post('store', [UserController::class, 'store'])->name('agent.user.store');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('agent.user.edit');
        Route::post('update/{id}', [UserController::class, 'update'])->name('agent.user.update');
    });
    Route::get('enrolled-student', [StudentController::class, 'index'])->name('agent.student.enrolled.index');
    Route::group([
        'prefix' => 'student'
    ], function () {
        Route::get('/', [AgentStudentController::class, 'index'])->name('agent.student.index');
        Route::get('create', [AgentStudentController::class, 'create'])->name('agent.student.create');
        Route::post('store', [AgentStudentController::class, 'store'])->name('agent.student.store');
        Route::get('edit/{id}', [AgentStudentController::class, 'edit'])->name('agent.student.edit');
        Route::post('update/{id}', [AgentStudentController::class, 'update'])->name('agent.student.update');
        Route::group([
            'prefix' => 'intake'
        ], function () {
            Route::get('/{id}', [StudentIntakeCourseController::class, 'index'])->name('agent.student.intake.index');
            Route::get('create/{id}', [StudentIntakeCourseController::class, 'create'])->name('agent.student.intake.create');
            Route::post('store/{id}', [StudentIntakeCourseController::class, 'store'])->name('agent.student.intake.store');
            Route::get('edit/{id}', [StudentIntakeCourseController::class, 'edit'])->name('agent.student.intake.edit');
            Route::post('update/{id}', [StudentIntakeCourseController::class, 'update'])->name('agent.student.intake.update');
            Route::get('course/list', [StudentIntakeCourseController::class, 'getCourseByIntake']);
            Route::get('course/fee/list', [StudentIntakeCourseController::class, 'getIntakeCourseFee']);
        });
        Route::group([
            'prefix' => 'offer'
        ], function () {
            Route::get('/{id}', [StudentOfferController::class, 'index'])->name('agent.student.offer.index');
            Route::get('create/{id}', [StudentOfferController::class, 'create'])->name('agent.student.offer.create');
            Route::post('store/{id}', [StudentOfferController::class, 'store'])->name('agent.student.offer.store');
            Route::get('edit/{id}', [StudentOfferController::class, 'edit'])->name('agent.student.offer.edit');
            Route::post('update/{id}', [StudentOfferController::class, 'update'])->name('agent.student.offer.update');
            Route::get('print/{id}', [StudentOfferController::class, 'printPDF'])->name('agent.student.offer.print');
        });
    });
    Route::get('commission', [CommissionController::class, 'index'])->name('agent.commission.index');
});
