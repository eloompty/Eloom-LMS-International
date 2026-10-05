<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\V1\Error\IntakeController;
use Modules\Api\Http\Controllers\V1\Student\LoginController;
use Modules\Api\Http\Controllers\V1\Student\ProfileController;
use Modules\Api\Http\Controllers\V1\Student\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/api', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1'
], function () {
    Route::group([
        'prefix' => 'student'
    ], function () {
        Route::post('login', [LoginController::class, 'studentLogin']);
        Route::post('device/register', [LoginController::class, 'studentDeviceRegister']);
        Route::post('logout', [LoginController::class, 'studentLogout']);
        Route::get('profile', [ProfileController::class, 'studentProfile']);
        Route::post('update/password', [ProfileController::class, 'studentUpdatePassword']);
        Route::get('dashboard', [StudentController::class, 'studentDashboard']);
        Route::get('course/{id}', [StudentController::class, 'studentCourseUnit']);
        Route::get('unit/time/{id}', [StudentController::class, 'studentUnitTimeTable']);
        Route::get('trainer/{id}', [StudentController::class, 'studentAssignedTrainer']);
        Route::get('resource/{id}', [StudentController::class, 'studentResources']);
        Route::group([
            'prefix' => 'assignment'
        ], function () {
            Route::get('/{id}', [StudentController::class, 'studentAssignment']);
            Route::get('detail/{id}', [StudentController::class, 'studentAssignmentDetail']);
            Route::get('submission/{id}', [StudentController::class, 'studentAssignmentSubmission']);
            Route::get('submission/detail/{id}', [StudentController::class, 'studentAssignmentSubmissionDetail']);
            Route::get('due/submission', [StudentController::class, 'studentAssignmentDue']);
        });
        Route::get('notification', [StudentController::class, 'studentNotification']);
        Route::group([
            'prefix' => 'onlineclass'
        ], function () {
            Route::get('/{id}', [StudentController::class, 'studentOnlineClass']);
            Route::get('group/all', [StudentController::class, 'studentGroupOnlineClass']);
            Route::get('group/class/{id}', [StudentController::class, 'studentGroupOnlineClassById']);
            Route::get('recording/{id}', [StudentController::class, 'studentOnlineClassRecording']);
        });
        Route::get('attendance/{id}', [StudentController::class, 'studentAttendance']);
        Route::get('fee/{id}', [StudentController::class, 'studentFeeDetails']);
    });
    Route::post('intake/course', [IntakeController::class, 'intakeCourse']);
});