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
use Modules\Intake\Http\Controllers\IntakeController;
use Modules\Intake\Http\Controllers\IntakeCourseController;
use Modules\Intake\Http\Controllers\IntakeCourseFeeController;
use Modules\Intake\Http\Controllers\IntakeCourseFeeInstallmentController;
use Modules\Intake\Http\Controllers\IntakeCourseStudentController;
use Modules\Intake\Http\Controllers\IntakeCourseTimeController;
use Modules\Intake\Http\Controllers\IntakeSemesterController;
use Modules\Intake\Http\Controllers\IntakeSubjectAssignmentController;
use Modules\Intake\Http\Controllers\IntakeSubjectAssignmentFileController;
use Modules\Intake\Http\Controllers\IntakeSubjectAssignmentFilesController;
use Modules\Intake\Http\Controllers\IntakeSubjectAssignmentMCQController;
use Modules\Intake\Http\Controllers\IntakeSubjectAssignmentQuestionController;
use Modules\Intake\Http\Controllers\IntakeSubjectAssignmentSubmissionController;
use Modules\Intake\Http\Controllers\IntakeSubjectAttendanceController;
use Modules\Intake\Http\Controllers\IntakeSubjectController;
use Modules\Intake\Http\Controllers\IntakeSubjectMarkController;
use Modules\Intake\Http\Controllers\IntakeTimeController;
use Modules\Intake\Http\Controllers\IntakeUnitAssignmentController;
use Modules\Intake\Http\Controllers\IntakeUnitAssignmentFileController;
use Modules\Intake\Http\Controllers\IntakeUnitAssignmentFilesController;
use Modules\Intake\Http\Controllers\IntakeUnitAssignmentMCQController;
use Modules\Intake\Http\Controllers\IntakeUnitAssignmentQuestionController;
use Modules\Intake\Http\Controllers\IntakeUnitAssignmentSubmissionController;
use Modules\Intake\Http\Controllers\IntakeUnitAttendanceController;
use Modules\Intake\Http\Controllers\IntakeUnitController;
use Modules\Intake\Http\Controllers\IntakeUnitMarkController;
use Modules\Intake\Http\Controllers\IntakeUnitOnlineClassController;
use Modules\Intake\Http\Controllers\IntakeUnitOnlineClassTeamController;
use Modules\Intake\Http\Controllers\IntakeUnitTimeController;

Route::group([
    'prefix' => 'admin/intake'
], function () {
    Route::get('/', [IntakeController::class, 'index'])->name('admin.intake.index');
    Route::get('create', [IntakeController::class, 'create'])->name('admin.intake.create');
    Route::post('store', [IntakeController::class, 'store'])->name('admin.intake.store');
    Route::get('edit/{id}', [IntakeController::class, 'edit'])->name('admin.intake.edit');
    Route::post('update/{id}', [IntakeController::class, 'update'])->name('admin.intake.update');
    Route::get('destroy/{id}', [IntakeController::class, 'destroy'])->name('admin.intake.delete');
    Route::get('copy/{id}', [IntakeController::class, 'copy'])->name('admin.intake.copy');
    Route::post('copy/create/{id}', [IntakeController::class, 'copyCreate'])->name('admin.intake.copy.create');
    Route::group([
        'prefix' => 'course'
    ], function () {
        Route::get('/{id}', [IntakeCourseController::class, 'index'])->name('admin.intake.course.index');
        Route::get('create/{id}', [IntakeCourseController::class, 'create'])->name('admin.intake.course.create');
        Route::post('store/{id}', [IntakeCourseController::class, 'store'])->name('admin.intake.course.store');
        Route::get('edit/{id}', [IntakeCourseController::class, 'edit'])->name('admin.intake.course.edit');
        Route::post('update/{id}', [IntakeCourseController::class, 'update'])->name('admin.intake.course.update');
        Route::get('destroy/{id}', [IntakeCourseController::class, 'destroy'])->name('admin.intake.course.delete');
        Route::get('/semester/get', [IntakeCourseController::class, 'getSemester'])->name('admin.intake.course.semester');
        Route::get('/semester/subject/get', [IntakeCourseController::class, 'getSubject'])->name('admin.intake.course.semester');
        Route::get('/semester/subject/unit/get', [IntakeCourseController::class, 'getUnit'])->name('admin.intake.course.unit');
        Route::get('/missing/assignment/add', [IntakeCourseController::class, 'addMissingAssignmentToIntake']);
        Route::group([
            'prefix' => 'time'
        ], function () {
            Route::get('/{id}', [IntakeCourseTimeController::class, 'index'])->name('admin.intake.course.time.index');
            Route::get('create/{id}', [IntakeCourseTimeController::class, 'create'])->name('admin.intake.course.time.create');
            Route::post('store/{id}', [IntakeCourseTimeController::class, 'store'])->name('admin.intake.course.time.store');
            Route::get('edit/{id}', [IntakeCourseTimeController::class, 'edit'])->name('admin.intake.course.time.edit');
            Route::post('update/{id}', [IntakeCourseTimeController::class, 'update'])->name('admin.intake.course.time.update');
            Route::get('destroy/{id}', [IntakeCourseTimeController::class, 'destroy'])->name('admin.intake.course.time.delete');
        });
        Route::group([
            'prefix' => 'semester'
        ], function () {
            Route::get('/{id}', [IntakeSemesterController::class, 'index'])->name('admin.intake.semester.index');
            Route::get('edit/{id}', [IntakeSemesterController::class, 'edit'])->name('admin.intake.semester.edit');
            Route::post('update/{id}', [IntakeSemesterController::class, 'update'])->name('admin.intake.semester.update');
            Route::group([
                'prefix' => 'subject'
            ], function () {
                Route::get('/{id}', [IntakeSubjectController::class, 'index'])->name('admin.intake.subject.index');
                Route::get('edit/{id}', [IntakeSubjectController::class, 'edit'])->name('admin.intake.subject.edit');
                Route::post('update/{id}', [IntakeSubjectController::class, 'update'])->name('admin.intake.subject.update');
                Route::group([
                    'prefix' => 'timetable'
                ], function () {
                    Route::get('/{id}', [IntakeTimeController::class, 'index'])->name('admin.intake.subject.time.index');
                    Route::get('create/{id}', [IntakeTimeController::class, 'create'])->name('admin.intake.subject.time.create');
                    Route::post('store/{id}', [IntakeTimeController::class, 'store'])->name('admin.intake.subject.time.store');
                    Route::get('edit/{id}', [IntakeTimeController::class, 'edit'])->name('admin.intake.subject.time.edit');
                    Route::post('update/{id}', [IntakeTimeController::class, 'update'])->name('admin.intake.subject.time.update');
                    Route::get('destroy/{id}', [IntakeTimeController::class, 'destroy'])->name('admin.intake.subject.time.delete');
                });
                Route::group([
                    'prefix' => 'marking'
                ], function () {
                    Route::get('/{id}', [IntakeSubjectMarkController::class, 'index'])->name('admin.intake.subject.marking.index');
                    Route::get('create/{id}', [IntakeSubjectMarkController::class, 'create'])->name('admin.intake.subject.marking.create');
                    Route::post('store/{id}', [IntakeSubjectMarkController::class, 'store'])->name('admin.intake.subject.marking.store');
                    Route::get('edit/{id}', [IntakeSubjectMarkController::class, 'edit'])->name('admin.intake.subject.marking.edit');
                    Route::post('update/{id}', [IntakeSubjectMarkController::class, 'update'])->name('admin.intake.subject.marking.update');
                    Route::get('destroy/{id}', [IntakeSubjectMarkController::class, 'destroy'])->name('admin.intake.subject.marking.delete');
                    Route::group([
                        'prefix' => 'student'
                    ], function () {
                        Route::get('/{id}', [IntakeSubjectMarkController::class, 'markStudent'])->name('admin.intake.subject.marking.student.index');
                        Route::post('update', [IntakeSubjectMarkController::class, 'markStudentUpdate'])->name('admin.intake.subject.marking.student.update');
                    });
                });
                Route::group([
                    'prefix' => 'attendance'
                ], function () {
                    Route::get('/{id}/{year}/{month}', [IntakeSubjectAttendanceController::class, 'index'])->name('admin.intake.subject.attendance.index');
                    Route::post('mark/{sessionId}', [IntakeSubjectAttendanceController::class, 'mark'])->name('admin.intake.subject.attendance.mark');
                    Route::get('year', [IntakeSubjectAttendanceController::class, 'getMonthByYear']);
                });
                Route::group([
                    'prefix' => 'assignment'
                ], function () {
                    Route::get('/{id}', [IntakeSubjectAssignmentController::class, 'index'])->name('admin.intake.subject.assignment.index');
                    Route::get('create/{id}', [IntakeSubjectAssignmentController::class, 'create'])->name('admin.intake.subject.assignment.create');
                    Route::post('store/{id}', [IntakeSubjectAssignmentController::class, 'store'])->name('admin.intake.subject.assignment.store');
                    Route::get('edit/{id}', [IntakeSubjectAssignmentController::class, 'edit'])->name('admin.intake.subject.assignment.edit');
                    Route::post('update/{id}', [IntakeSubjectAssignmentController::class, 'update'])->name('admin.intake.subject.assignment.update');
                    Route::get('destroy/{id}', [IntakeSubjectAssignmentController::class, 'destroy'])->name('admin.intake.subject.assignment.delete');
                    Route::group([
                        'prefix' => 'file'
                    ], function () {
                        Route::get('create/{id}', [IntakeSubjectAssignmentFileController::class, 'create'])->name('admin.intake.subject.assignment.file.create');
                        Route::post('store/{id}', [IntakeSubjectAssignmentFileController::class, 'store'])->name('admin.intake.subject.assignment.file.store');
                    });
                    Route::group([
                        'prefix' => 'files'
                    ], function () {
                        Route::get('create/{id}', [IntakeSubjectAssignmentFilesController::class, 'create'])->name('admin.intake.subject.assignment.files.create');
                        Route::post('store/{id}', [IntakeSubjectAssignmentFilesController::class, 'store'])->name('admin.intake.subject.assignment.files.store');
                        Route::get('edit/{id}', [IntakeSubjectAssignmentFilesController::class, 'edit'])->name('admin.intake.subject.assignment.files.edit');
                        Route::post('update/{id}', [IntakeSubjectAssignmentFilesController::class, 'update'])->name('admin.intake.subject.assignment.files.update');
                    });
                    Route::group([
                        'prefix' => 'question'
                    ], function () {
                        Route::get('create/{id}', [IntakeSubjectAssignmentQuestionController::class, 'create'])->name('admin.intake.subject.assignment.question.create');
                        Route::post('store/{id}', [IntakeSubjectAssignmentQuestionController::class, 'store'])->name('admin.intake.subject.assignment.question.store');
                        Route::get('show/{id}', [IntakeSubjectAssignmentQuestionController::class, 'show'])->name('admin.intake.subject.assignment.question.show');
                        Route::post('update/{id}', [IntakeSubjectAssignmentQuestionController::class, 'update'])->name('admin.intake.subject.assignment.question.update');
                    });
                    Route::group([
                        'prefix' => 'mcq'
                    ], function () {
                        Route::get('create/{id}', [IntakeSubjectAssignmentMCQController::class, 'create'])->name('admin.intake.subject.assignment.mcq.create');
                        Route::post('store/{id}', [IntakeSubjectAssignmentMCQController::class, 'store'])->name('admin.intake.subject.assignment.mcq.store');
                        Route::post('finish/{id}', [IntakeSubjectAssignmentMCQController::class, 'finish'])->name('admin.intake.subject.assignment.mcq.finish');
                        Route::get('show/{id}', [IntakeSubjectAssignmentMCQController::class, 'show'])->name('admin.intake.subject.assignment.mcq.show');
                        Route::get('edit/{id}', [IntakeSubjectAssignmentMCQController::class, 'edit'])->name('admin.intake.subject.assignment.mcq.edit');
                        Route::post('update/{id}', [IntakeSubjectAssignmentMCQController::class, 'update'])->name('admin.intake.subject.assignment.mcq.update');
                    });
                    Route::group([
                        'prefix' => 'submission'
                    ], function () {
                        Route::get('/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'index'])->name('admin.intake.subject.assignment.submission.index');
                        Route::get('edit/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'edit'])->name('admin.intake.subject.assignment.submission.edit');
                        Route::post('update/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'update'])->name('admin.intake.subject.assignment.submission.update');
                        Route::get('files/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'files'])->name('admin.intake.subject.assignment.submission.files.index');
                        Route::get('files/edit/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'filesEdit'])->name('admin.intake.subject.assignment.submission.files.edit');
                        Route::post('files/update/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'filesUpdate'])->name('admin.intake.subject.assignment.submission.files.update');
                        Route::get('question/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'question'])->name('admin.intake.subject.assignment.submission.question.index');
                        Route::post('question/remarks/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'questionRemarks'])->name('admin.intake.subject.assignment.submission.question.remarks');
                        Route::get('mcq/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'mcq'])->name('admin.intake.subject.assignment.submission.mcq.index');
                        Route::post('mcq/{id}', [IntakeSubjectAssignmentSubmissionController::class, 'mcqRemarks'])->name('admin.intake.subject.assignment.submission.mcq.remarks');
                        Route::get('pdf/{id}/{type}', [IntakeSubjectAssignmentSubmissionController::class, 'pdf'])->name('admin.intake.subject.assignment.submission.pdf');
                        Route::post('pdfshow/{id}/{type}/save', [IntakeSubjectAssignmentSubmissionController::class, 'pdfsave'])->name('admin.intake.subject.assignment.submission.pdfsave');
                    });
                });
            });
            Route::group([
                'prefix' => 'unit'
            ], function () {
                Route::get('/{id}', [IntakeUnitController::class, 'index'])->name('admin.intake.unit.index');
                Route::get('edit/{id}', [IntakeUnitController::class, 'edit'])->name('admin.intake.unit.edit');
                Route::post('update/{id}', [IntakeUnitController::class, 'update'])->name('admin.intake.unit.update');
                Route::get('destroy/{id}', [IntakeUnitController::class, 'destroy'])->name('admin.intake.unit.delete');
                Route::group([
                    'prefix' => 'marking'
                ], function () {
                    Route::get('/{id}', [IntakeUnitMarkController::class, 'index'])->name('admin.intake.unit.marking.index');
                    Route::get('create/{id}', [IntakeUnitMarkController::class, 'create'])->name('admin.intake.unit.marking.create');
                    Route::post('store/{id}', [IntakeUnitMarkController::class, 'store'])->name('admin.intake.unit.marking.store');
                    Route::get('edit/{id}', [IntakeUnitMarkController::class, 'edit'])->name('admin.intake.unit.marking.edit');
                    Route::post('update/{id}', [IntakeUnitMarkController::class, 'update'])->name('admin.intake.unit.marking.update');
                    Route::get('destroy/{id}', [IntakeUnitMarkController::class, 'destroy'])->name('admin.intake.unit.marking.delete');
                    Route::group([
                        'prefix' => 'student'
                    ], function () {
                        Route::get('/{id}', [IntakeUnitMarkController::class, 'markStudent'])->name('admin.intake.unit.marking.student.index');
                        Route::post('update', [IntakeUnitMarkController::class, 'markStudentUpdate'])->name('admin.intake.unit.marking.student.update');
                    });
                });
                Route::group([
                    'prefix' => 'time'
                ], function () {
                    Route::post('update/{id}', [IntakeUnitTimeController::class, 'update'])->name('admin.intake.unit.time.update');
                    Route::get('destroy/{id}', [IntakeUnitTimeController::class, 'destroy'])->name('admin.intake.unit.time.delete');
                });
                Route::group([
                    'prefix' => 'assignment'
                ], function () {
                    Route::get('/{id}', [IntakeUnitAssignmentController::class, 'index'])->name('admin.intake.unit.assignment.index');
                    Route::get('create/{id}', [IntakeUnitAssignmentController::class, 'create'])->name('admin.intake.unit.assignment.create');
                    Route::post('store/{id}', [IntakeUnitAssignmentController::class, 'store'])->name('admin.intake.unit.assignment.store');
                    Route::get('edit/{id}', [IntakeUnitAssignmentController::class, 'edit'])->name('admin.intake.unit.assignment.edit');
                    Route::post('update/{id}', [IntakeUnitAssignmentController::class, 'update'])->name('admin.intake.unit.assignment.update');
                    Route::get('destroy/{id}', [IntakeUnitAssignmentController::class, 'destroy'])->name('admin.intake.unit.assignment.delete');
                    Route::group([
                        'prefix' => 'file'
                    ], function () {
                        Route::get('create/{id}', [IntakeUnitAssignmentFileController::class, 'create'])->name('admin.intake.unit.assignment.file.create');
                        Route::post('store/{id}', [IntakeUnitAssignmentFileController::class, 'store'])->name('admin.intake.unit.assignment.file.store');
                    });
                    Route::group([
                        'prefix' => 'files'
                    ], function () {
                        Route::get('create/{id}', [IntakeUnitAssignmentFilesController::class, 'create'])->name('admin.intake.unit.assignment.files.create');
                        Route::post('store/{id}', [IntakeUnitAssignmentFilesController::class, 'store'])->name('admin.intake.unit.assignment.files.store');
                        Route::get('edit/{id}', [IntakeUnitAssignmentFilesController::class, 'edit'])->name('admin.intake.unit.assignment.files.edit');
                        Route::post('update/{id}', [IntakeUnitAssignmentFilesController::class, 'update'])->name('admin.intake.unit.assignment.files.update');
                    });
                    Route::group([
                        'prefix' => 'question'
                    ], function () {
                        Route::get('create/{id}', [IntakeUnitAssignmentQuestionController::class, 'create'])->name('admin.intake.unit.assignment.question.create');
                        Route::post('store/{id}', [IntakeUnitAssignmentQuestionController::class, 'store'])->name('admin.intake.unit.assignment.question.store');
                        Route::get('show/{id}', [IntakeUnitAssignmentQuestionController::class, 'show'])->name('admin.intake.unit.assignment.question.show');
                        Route::post('update/{id}', [IntakeUnitAssignmentQuestionController::class, 'update'])->name('admin.intake.unit.assignment.question.update');
                    });
                    Route::group([
                        'prefix' => 'mcq'
                    ], function () {
                        Route::get('create/{id}', [IntakeUnitAssignmentMCQController::class, 'create'])->name('admin.intake.unit.assignment.mcq.create');
                        Route::post('store/{id}', [IntakeUnitAssignmentMCQController::class, 'store'])->name('admin.intake.unit.assignment.mcq.store');
                        Route::post('finish/{id}', [IntakeUnitAssignmentMCQController::class, 'finish'])->name('admin.intake.unit.assignment.mcq.finish');
                        Route::get('show/{id}', [IntakeUnitAssignmentMCQController::class, 'show'])->name('admin.intake.unit.assignment.mcq.show');
                        Route::get('edit/{id}', [IntakeUnitAssignmentMCQController::class, 'edit'])->name('admin.intake.unit.assignment.mcq.edit');
                        Route::post('update/{id}', [IntakeUnitAssignmentMCQController::class, 'update'])->name('admin.intake.unit.assignment.mcq.update');
                    });
                    Route::group([
                        'prefix' => 'submission'
                    ], function () {
                        Route::get('/{id}', [IntakeUnitAssignmentSubmissionController::class, 'index'])->name('admin.intake.unit.assignment.submission.index');
                        Route::get('edit/{id}', [IntakeUnitAssignmentSubmissionController::class, 'edit'])->name('admin.intake.unit.assignment.submission.edit');
                        Route::post('update/{id}', [IntakeUnitAssignmentSubmissionController::class, 'update'])->name('admin.intake.unit.assignment.submission.update');
                        Route::get('files/{id}', [IntakeUnitAssignmentSubmissionController::class, 'files'])->name('admin.intake.unit.assignment.submission.files.index');
                        Route::get('files/edit/{id}', [IntakeUnitAssignmentSubmissionController::class, 'filesEdit'])->name('admin.intake.unit.assignment.submission.files.edit');
                        Route::post('files/update/{id}', [IntakeUnitAssignmentSubmissionController::class, 'filesUpdate'])->name('admin.intake.unit.assignment.submission.files.update');
                        Route::get('question/{id}', [IntakeUnitAssignmentSubmissionController::class, 'question'])->name('admin.intake.unit.assignment.submission.question.index');
                        Route::post('question/remarks/{id}', [IntakeUnitAssignmentSubmissionController::class, 'questionRemarks'])->name('admin.intake.unit.assignment.submission.question.remarks');
                        Route::get('mcq/{id}', [IntakeUnitAssignmentSubmissionController::class, 'mcq'])->name('admin.intake.unit.assignment.submission.mcq.index');
                        Route::post('mcq/{id}', [IntakeUnitAssignmentSubmissionController::class, 'mcqRemarks'])->name('admin.intake.unit.assignment.submission.mcq.remarks');
                        Route::get('pdf/{id}/{type}', [IntakeUnitAssignmentSubmissionController::class, 'pdf'])->name('admin.intake.unit.assignment.submission.pdf');
                        Route::post('pdfshow/{id}/{type}/save', [IntakeUnitAssignmentSubmissionController::class, 'pdfsave'])->name('admin.intake.unit.assignment.submission.pdfsave');
                    });
                });
                Route::group([
                    'prefix' => 'onlineclass'
                ], function () {
                    Route::get('/{id}', [IntakeUnitOnlineClassController::class, 'index'])->name('admin.intake.unit.onlineclass.index');
                    Route::get('create/{id}', [IntakeUnitOnlineClassController::class, 'create'])->name('admin.intake.unit.onlineclass.create');
                    Route::post('store/{id}', [IntakeUnitOnlineClassController::class, 'store'])->name('admin.intake.unit.onlineclass.store');
                    Route::get('recording/{id}', [IntakeUnitOnlineClassController::class, 'show'])->name('admin.intake.unit.onlineclass.show');
                });
                Route::group([
                    'prefix' => 'attendance'
                ], function () {
                    Route::get('/{id}/{year}/{month}', [IntakeUnitAttendanceController::class, 'index'])->name('admin.intake.unit.attendance.index');
                    Route::post('mark/{sessionId}', [IntakeUnitAttendanceController::class, 'mark'])->name('admin.intake.unit.attendance.mark');
                    Route::get('year', [IntakeUnitAttendanceController::class, 'getMonthByYear']);
                });
                Route::group([
                    'prefix' => 'team'
                ], function () {
                    Route::get('/{id}', [IntakeUnitOnlineClassTeamController::class, 'index'])->name('admin.intake.unit.team.index');
                    Route::get('create/{id}', [IntakeUnitOnlineClassTeamController::class, 'create'])->name('admin.intake.unit.team.create');
                    Route::post('store', [IntakeUnitOnlineClassTeamController::class, 'store'])->name('admin.intake.unit.team.store');
                });
            });
        });
        Route::group([
            'prefix' => 'student'
        ], function () {
            Route::get('/{id}', [IntakeCourseStudentController::class, 'index'])->name('admin.intake.course.student.index');
        });
        Route::group([
            'prefix' => 'fee'
        ], function () {
            Route::get('/{id}', [IntakeCourseFeeController::class, 'index'])->name('admin.intake.course.fee.index');
            Route::get('edit/{id}', [IntakeCourseFeeController::class, 'edit'])->name('admin.intake.course.fee.edit');
            Route::post('update/{id}', [IntakeCourseFeeController::class, 'update'])->name('admin.intake.course.fee.update');
            Route::group([
                'prefix' => 'installment'
            ], function () {
                Route::get('/{id}', [IntakeCourseFeeInstallmentController::class, 'index'])->name('admin.intake.course.fee.installment.index');
                Route::get('edit/{id}', [IntakeCourseFeeInstallmentController::class, 'edit'])->name('admin.intake.course.fee.installment.edit');
                Route::post('update/{id}', [IntakeCourseFeeInstallmentController::class, 'update'])->name('admin.intake.course.fee.installment.update');
            });
        });
    });
});
