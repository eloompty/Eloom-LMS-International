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
use Modules\Trainer\Http\Controllers\Trainer\AssignmentController;
use Modules\Trainer\Http\Controllers\Trainer\AssignmentFileController;
use Modules\Trainer\Http\Controllers\Trainer\AssignmentFilesController;
use Modules\Trainer\Http\Controllers\Trainer\AssignmentMCQController;
use Modules\Trainer\Http\Controllers\Trainer\AssignmentQuestionController;
use Modules\Trainer\Http\Controllers\Trainer\AttendanceController;
use Modules\Trainer\Http\Controllers\Trainer\CalendarController;
use Modules\Trainer\Http\Controllers\Trainer\CourseController;
use Modules\Trainer\Http\Controllers\Trainer\IntakeSubjectChatController;
use Modules\Trainer\Http\Controllers\Trainer\IntakeSubjectMarkController;
use Modules\Trainer\Http\Controllers\Trainer\IntakeUnitMarkController;
use Modules\Trainer\Http\Controllers\Trainer\LoginController;
use Modules\Trainer\Http\Controllers\Trainer\OnlineClassController;
use Modules\Trainer\Http\Controllers\Trainer\OnlineClassGroupClassController;
use Modules\Trainer\Http\Controllers\Trainer\OnlineClassGroupController;
use Modules\Trainer\Http\Controllers\Trainer\OnlineClassGroupTeamController;
use Modules\Trainer\Http\Controllers\Trainer\ResourceController;
use Modules\Trainer\Http\Controllers\Trainer\ResubmissionController;
use Modules\Trainer\Http\Controllers\Trainer\Student\ChatController;
use Modules\Trainer\Http\Controllers\Trainer\Student\StudentController as StudentStudentController;
use Modules\Trainer\Http\Controllers\Trainer\StudentController;
use Modules\Trainer\Http\Controllers\Trainer\StudentIntakeSubjectMarkController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectAssignmentController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectAssignmentFileController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectAssignmentFilesController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectAssignmentMCQController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectAssignmentQuestionController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectAttendanceController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectResourceController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectResubmissionController;
use Modules\Trainer\Http\Controllers\Trainer\SubjectSubmissionController;
use Modules\Trainer\Http\Controllers\Trainer\SubmissionController;
use Modules\Trainer\Http\Controllers\Trainer\TeamController;
use Modules\Trainer\Http\Controllers\Trainer\TimeTableController;
use Modules\Trainer\Http\Controllers\Trainer\TrainerAssignmentController;
use Modules\Trainer\Http\Controllers\Trainer\TrainerController as TrainerTrainerController;
use Modules\Trainer\Http\Controllers\Trainer\TrainerSubmissionController;
use Modules\Trainer\Http\Controllers\TrainerController;
use Modules\Trainer\Http\Controllers\TrainerDeviceController;
use Modules\Trainer\Http\Controllers\TrainerIntakeController;
use Modules\Trainer\Http\Controllers\TrainerLogController;
use Modules\Trainer\Http\Controllers\TrainerProfessionalDevelopmentController;
use Modules\Trainer\Http\Controllers\TrainerQualificationController;
use Modules\Trainer\Http\Controllers\TrainerWorkPlacementController;

Route::group([
    'prefix' => 'admin/trainer'
], function () {
    Route::get('/', [TrainerController::class, 'index'])->name('admin.trainer.index');
    Route::get('create', [TrainerController::class, 'create'])->name('admin.trainer.create');
    Route::post('store', [TrainerController::class, 'store'])->name('admin.trainer.store');
    Route::get('edit/{id}', [TrainerController::class, 'edit'])->name('admin.trainer.edit');
    Route::post('update/{id}', [TrainerController::class, 'update'])->name('admin.trainer.update');
    Route::get('destroy/{id}', [TrainerController::class, 'destroy'])->name('admin.trainer.delete');
    Route::get('dashboard/{id}', [TrainerController::class, 'dashboard'])->name('admin.trainer.dashboard');
    Route::get('university', [TrainerQualificationController::class, 'university']);
    Route::group([
        'prefix' => 'qualification'
    ], function () {
        Route::get('/{id}', [TrainerQualificationController::class, 'index'])->name('admin.trainer.qualification.index');
        Route::get('create/{id}', [TrainerQualificationController::class, 'create'])->name('admin.trainer.qualification.create');
        Route::post('store/{id}', [TrainerQualificationController::class, 'store'])->name('admin.trainer.qualification.store');
        Route::get('edit/{id}', [TrainerQualificationController::class, 'edit'])->name('admin.trainer.qualification.edit');
        Route::post('update/{id}', [TrainerQualificationController::class, 'update'])->name('admin.trainer.qualification.update');
        Route::get('destroy/{id}', [TrainerQualificationController::class, 'destroy'])->name('admin.trainer.qualification.delete');
    });
    Route::group([
        'prefix' => 'profession'
    ], function () {
        Route::get('/{id}', [TrainerProfessionalDevelopmentController::class, 'index'])->name('admin.trainer.profession.index');
        Route::get('create/{id}', [TrainerProfessionalDevelopmentController::class, 'create'])->name('admin.trainer.profession.create');
        Route::post('store/{id}', [TrainerProfessionalDevelopmentController::class, 'store'])->name('admin.trainer.profession.store');
        Route::get('edit/{id}', [TrainerProfessionalDevelopmentController::class, 'edit'])->name('admin.trainer.profession.edit');
        Route::post('update/{id}', [TrainerProfessionalDevelopmentController::class, 'update'])->name('admin.trainer.profession.update');
        Route::get('destroy/{id}', [TrainerProfessionalDevelopmentController::class, 'destroy'])->name('admin.trainer.profession.delete');
    });
    Route::group([
        'prefix' => 'workplacement'
    ], function () {
        Route::get('/{id}', [TrainerWorkPlacementController::class, 'index'])->name('admin.trainer.workplacement.index');
        Route::get('create/{id}', [TrainerWorkPlacementController::class, 'create'])->name('admin.trainer.workplacement.create');
        Route::post('store/{id}', [TrainerWorkPlacementController::class, 'store'])->name('admin.trainer.workplacement.store');
        Route::get('edit/{id}', [TrainerWorkPlacementController::class, 'edit'])->name('admin.trainer.workplacement.edit');
        Route::post('update/{id}', [TrainerWorkPlacementController::class, 'update'])->name('admin.trainer.workplacement.update');
        Route::get('destroy/{id}', [TrainerWorkPlacementController::class, 'destroy'])->name('admin.trainer.workplacement.delete');
    });
    Route::group([
        'prefix' => 'intake'
    ], function () {
        Route::get('/{id}', [TrainerIntakeController::class, 'index'])->name('admin.trainer.intake.index');
        Route::get('create/{id}', [TrainerIntakeController::class, 'create'])->name('admin.trainer.intake.create');
        Route::post('store/{id}', [TrainerIntakeController::class, 'store'])->name('admin.trainer.intake.store');
        Route::get('edit/{id}', [TrainerIntakeController::class, 'edit'])->name('admin.trainer.intake.edit');
        Route::post('update/{id}', [TrainerIntakeController::class, 'update'])->name('admin.trainer.intake.update');
        Route::get('destroy/{id}', [TrainerIntakeController::class, 'destroy'])->name('admin.trainer.intake.delete');
    });
    Route::get('log/{id}', [TrainerLogController::class, 'index'])->name('admin.trainer.log.index');
    Route::get('device/{id}', [TrainerDeviceController::class, 'index'])->name('admin.trainer.device.index');
});

Route::group([
    'prefix' => 'trainer'
], function () {
    Route::get('/', [LoginController::class, 'home'])->name('trainer.login');
    Route::get('login', [LoginController::class, 'home'])->name('trainer.login');
    Route::post('login', [LoginController::class, 'login'])->name('trainer.login');
    Route::post('save/device', [LoginController::class, 'saveToken'])->name('trainer.device.save');
    Route::get('logout', [LoginController::class, 'logout'])->name('trainer.logout')->middleware('auth:trainer');;
    Route::group([
        'prefix' => 'password'
    ], function () {
        Route::get('forgot', [LoginController::class, 'forgotPassword'])->name('trainer.password.forgot');
        Route::post('reset', [LoginController::class, 'resetPassword'])->name('trainer.password.reset');
        Route::get('reset/{email}/{code}', [LoginController::class, 'resettingPassword']);
        Route::post('update', [LoginController::class, 'updatePassword'])->name('trainer.password.update');
    });
    Route::get('dashboard', [TrainerTrainerController::class, 'dashboard'])->name('trainer.dashboard');
    Route::get('profile', [TrainerTrainerController::class, 'profile'])->name('trainer.profile');
    Route::get('change/password', [TrainerTrainerController::class, 'changePassword'])->name('trainer.change.password');
    Route::post('fill/password', [TrainerTrainerController::class, 'fillPassword'])->name('trainer.fill.password');
    Route::group([
        'prefix' => 'course'
    ], function () {
        Route::get('/', [CourseController::class, 'index'])->name('trainer.course.index');
        Route::group([
            'prefix' => 'semester'
        ], function () {
            Route::get('/{id}', [CourseController::class, 'semester'])->name('trainer.semester.index');
            Route::group([
                'prefix' => 'subject'
            ], function () {
                Route::get('/{id}', [CourseController::class, 'subject'])->name('trainer.subject.index');
                Route::get('edit/{id}', [CourseController::class, 'editSubject'])->name('trainer.subject.edit');
                Route::post('update/{id}', [CourseController::class, 'updateSubject'])->name('trainer.subject.update');
                Route::group([
                    'prefix' => 'attendance'
                ], function () {
                    Route::get('/{id}/{year}/{month}', [SubjectAttendanceController::class, 'index'])->name('trainer.subject.attendance.index');
                    Route::post('mark/{sessionId}', [SubjectAttendanceController::class, 'mark'])->name('trainer.subject.attendance.mark');
                    Route::get('year', [SubjectAttendanceController::class, 'getMonthByYear']);
                });
                Route::group([
                    'prefix' => 'resource'
                ], function () {
                    Route::get('/{id}', [SubjectResourceController::class, 'index'])->name('trainer.subject.resource.index');
                    Route::get('create/{id}/{trainer_intake_id}', [SubjectResourceController::class, 'create'])->name('trainer.subject.resource.create');
                    Route::post('store/{id}/{trainer_intake_id}', [SubjectResourceController::class, 'store'])->name('trainer.subject.resource.store');
                    Route::get('edit/{id}/{trainer_intake_id}', [SubjectResourceController::class, 'edit'])->name('trainer.subject.resource.edit');
                    Route::post('update/{id}', [SubjectResourceController::class, 'update'])->name('trainer.subject.resource.update');
                });
                Route::group([
                    'prefix' => 'assignment'
                ], function () {
                    Route::get('/{id}', [SubjectAssignmentController::class, 'index'])->name('trainer.subject.assignment.index');
                    Route::get('create/{id}', [SubjectAssignmentController::class, 'create'])->name('trainer.subject.assignment.create');
                    Route::post('store/{id}', [SubjectAssignmentController::class, 'store'])->name('trainer.subject.assignment.store');
                    Route::get('edit/{id}/{trainer_intake_id}', [SubjectAssignmentController::class, 'edit'])->name('trainer.subject.assignment.edit');
                    Route::post('update/{id}/{trainer_intake_id}', [SubjectAssignmentController::class, 'update'])->name('trainer.subject.assignment.update');
                    Route::group([
                        'prefix' => 'file'
                    ], function () {
                        Route::get('create/{id}', [SubjectAssignmentFileController::class, 'create'])->name('trainer.subject.assignment.file.create');
                        Route::post('store/{id}', [SubjectAssignmentFileController::class, 'store'])->name('trainer.subject.assignment.file.store');
                    });
                    Route::group([
                        'prefix' => 'files'
                    ], function () {
                        Route::get('create/{id}', [SubjectAssignmentFilesController::class, 'create'])->name('trainer.subject.assignment.files.create');
                        Route::post('store/{id}', [SubjectAssignmentFilesController::class, 'store'])->name('trainer.subject.assignment.files.store');
                        Route::get('edit/{id}', [SubjectAssignmentFilesController::class, 'edit'])->name('trainer.subject.assignment.files.edit');
                        Route::post('update/{id}', [SubjectAssignmentFilesController::class, 'update'])->name('trainer.subject.assignment.files.update');
                    });
                    Route::group([
                        'prefix' => 'question'
                    ], function () {
                        Route::get('create/{id}', [SubjectAssignmentQuestionController::class, 'create'])->name('trainer.subject.assignment.question.create');
                        Route::post('store/{id}', [SubjectAssignmentQuestionController::class, 'store'])->name('trainer.subject.assignment.question.store');
                        Route::get('show/{id}', [SubjectAssignmentQuestionController::class, 'show'])->name('trainer.subject.assignment.question.show');
                        Route::post('update/{id}', [SubjectAssignmentQuestionController::class, 'update'])->name('trainer.subject.assignment.question.update');
                    });
                    Route::group([
                        'prefix' => 'mcq'
                    ], function () {
                        Route::get('create/{id}', [SubjectAssignmentMCQController::class, 'create'])->name('trainer.subject.assignment.mcq.create');
                        Route::post('store/{id}', [SubjectAssignmentMCQController::class, 'store'])->name('trainer.subject.assignment.mcq.store');
                        Route::post('finish/{id}', [SubjectAssignmentMCQController::class, 'finish'])->name('trainer.subject.assignment.mcq.finish');
                        Route::get('show/{id}', [SubjectAssignmentMCQController::class, 'show'])->name('trainer.subject.assignment.mcq.show');
                        Route::get('edit/{id}', [SubjectAssignmentMCQController::class, 'edit'])->name('trainer.subject.assignment.mcq.edit');
                        Route::post('update/{id}', [SubjectAssignmentMCQController::class, 'update'])->name('trainer.subject.assignment.mcq.update');
                    });
                    Route::group([
                        'prefix' => 'submission'
                    ], function () {
                        Route::get('/{id}/{trainer_intake_id}', [SubjectSubmissionController::class, 'index'])->name('trainer.subject.submission.index');
                        Route::get('edit/{id}/{trainer_intake_id}', [SubjectSubmissionController::class, 'edit'])->name('trainer.subject.submission.edit');
                        Route::post('update/{id}/{trainer_intake_id}', [SubjectSubmissionController::class, 'update'])->name('trainer.subject.submission.update');
                        Route::get('files/{id}/{trainer_intake_id}', [SubjectSubmissionController::class, 'files'])->name('trainer.subject.submission.files.index');
                        Route::get('files/edit/{id}/{trainer_intake_id}', [SubjectSubmissionController::class, 'filesEdit'])->name('trainer.subject.submission.files.edit');
                        Route::post('files/update/{id}/{trainer_intake_id}', [SubjectSubmissionController::class, 'filesUpdate'])->name('trainer.subject.submission.files.update');
                        Route::get('question/{id}/{trainer_intake_id}', [SubjectSubmissionController::class, 'question'])->name('trainer.subject.submission.question.index');
                        Route::post('question/remarks/{id}', [SubjectSubmissionController::class, 'questionRemarks'])->name('trainer.subject.submission.question.remarks');
                        Route::get('mcq/{id}/{trainer_intake_id}', [SubjectSubmissionController::class, 'mcq'])->name('trainer.subject.submission.mcq.index');
                        Route::post('mcq/{id}', [SubjectSubmissionController::class, 'mcqRemarks'])->name('trainer.subject.submission.mcq.remarks');
                        Route::get('pdf/{id}/{type}', [SubjectSubmissionController::class, 'pdf'])->name('trainer.subject.submission.pdf');
                        Route::post('pdfshow/{id}/{type}/save', [SubjectSubmissionController::class, 'pdfsave'])->name('trainer.subject.submission.pdfsave');
                        Route::post('pdf/{id}/integrity-check', [SubjectSubmissionController::class, 'pdfIntegrityCheck'])->name('trainer.subject.submission.pdf.integrity-check');
                        Route::post('{id}/question/integrity-check', [SubjectSubmissionController::class, 'questionIntegrityCheck'])->name('trainer.subject.submission.question.integrity-check');
                        Route::post('{id}/integrity-source/export', [SubjectSubmissionController::class, 'exportIntegritySourcePdf'])->name('trainer.subject.submission.pdf.integrity-source.export');
                    });
                    Route::group([
                        'prefix' => 'resubmission'
                    ], function () {
                        Route::get('/{id}/{trainer_intake_id}', [SubjectResubmissionController::class, 'index'])->name('trainer.subject.resubmission.index');
                        Route::get('edit/{id}/{trainer_intake_id}', [SubjectResubmissionController::class, 'edit'])->name('trainer.subject.resubmission.edit');
                        Route::post('update/{id}/{trainer_intake_id}', [SubjectResubmissionController::class, 'update'])->name('trainer.subject.resubmission.update');
                    });
                });
                Route::group([
                    'prefix' => 'chat'
                ], function () {
                    Route::get('/{id}', [IntakeSubjectChatController::class, 'index'])->name('trainer.subject.chat.index');
                    Route::get('create/{id}/{trainer_intake_id}', [IntakeSubjectChatController::class, 'create'])->name('trainer.subject.chat.create');
                    Route::post('store/{id}/{trainer_intake_id}', [IntakeSubjectChatController::class, 'store'])->name('trainer.subject.chat.store');
                    Route::get('show/{id}', [IntakeSubjectChatController::class, 'show'])->name('trainer.subject.chat.show');
                    Route::get('edit/{id}', [IntakeSubjectChatController::class, 'edit'])->name('trainer.subject.chat.edit');
                    Route::post('update/{id}', [IntakeSubjectChatController::class, 'update'])->name('trainer.subject.chat.update');
                    Route::post('sendMessage/{id}', [IntakeSubjectChatController::class, 'sendMessage'])->name('trainer.subject.chat.send.message');
                    Route::post('createMessage', [IntakeSubjectChatController::class, 'createMessage']);
                    Route::get('load/message', [IntakeSubjectChatController::class, 'loadMessages']);
                });
                Route::group([
                    'prefix' => 'unit'
                ], function () {
                    Route::get('/{id}', [CourseController::class, 'unit'])->name('trainer.unit.index');
                    Route::get('edit/{id}', [CourseController::class, 'editUnit'])->name('trainer.unit.edit');
                    Route::post('update/{id}', [CourseController::class, 'updateUnit'])->name('trainer.unit.update');
                    Route::group([
                        'prefix' => 'marking'
                    ], function () {
                        Route::get('/{id}', [IntakeUnitMarkController::class, 'index'])->name('trainer.unit.mark.index');
                        Route::get('create/{id}', [IntakeUnitMarkController::class, 'create'])->name('trainer.unit.mark.create');
                        Route::post('store/{id}', [IntakeUnitMarkController::class, 'store'])->name('trainer.unit.mark.store');
                        Route::get('edit/{id}', [IntakeUnitMarkController::class, 'edit'])->name('trainer.unit.mark.edit');
                        Route::post('update/{id}', [IntakeUnitMarkController::class, 'update'])->name('trainer.unit.mark.update');
                        Route::get('destroy/{id}', [IntakeUnitMarkController::class, 'destroy'])->name('trainer.unit.mark.delete');
                        Route::group([
                            'prefix' => 'student'
                        ], function () {
                            Route::get('/{id}', [IntakeUnitMarkController::class, 'markStudent'])->name('trainer.unit.mark.student.index');
                            Route::post('update', [IntakeUnitMarkController::class, 'markStudentUpdate'])->name('trainer.unit.mark.student.update');
                        });
                    });
                });
                Route::group([
                    'prefix' => 'time'
                ], function () {
                    Route::get('/{id}', [TimeTableController::class, 'index'])->name('trainer.time.index');
                    Route::get('create/{id}', [TimeTableController::class, 'create'])->name('trainer.time.create');
                    Route::post('store/{id}', [TimeTableController::class, 'store'])->name('trainer.time.store');
                    Route::get('edit/{id}', [TimeTableController::class, 'edit'])->name('trainer.time.edit');
                    Route::post('update/{id}', [TimeTableController::class, 'update'])->name('trainer.time.update');
                    Route::get('destroy/{id}', [TimeTableController::class, 'destroy'])->name('trainer.time.delete');
                });
                Route::group([
                    'prefix' => 'subject-marking'
                ], function () {
                    Route::get('/{id}', [IntakeSubjectMarkController::class, 'index'])->name('trainer.subject.mark.index');
                    Route::get('create/{id}', [IntakeSubjectMarkController::class, 'create'])->name('trainer.subject.mark.create');
                    Route::post('store/{id}', [IntakeSubjectMarkController::class, 'store'])->name('trainer.subject.mark.store');
                    Route::get('edit/{id}', [IntakeSubjectMarkController::class, 'edit'])->name('trainer.subject.mark.edit');
                    Route::post('update/{id}', [IntakeSubjectMarkController::class, 'update'])->name('trainer.subject.mark.update');
                    Route::get('destroy/{id}', [IntakeSubjectMarkController::class, 'destroy'])->name('trainer.subject.mark.delete');
                    Route::group([
                        'prefix' => 'student'
                    ], function () {
                        Route::get('/{id}', [IntakeSubjectMarkController::class, 'markStudent'])->name('trainer.subject.mark.student.index');
                        Route::post('update', [IntakeSubjectMarkController::class, 'markStudentUpdate'])->name('trainer.subject.mark.student.update');
                    });
                });
            });
        });
        Route::group([
            'prefix' => 'resource'
        ], function () {
            Route::get('/{id}', [ResourceController::class, 'index'])->name('trainer.resource.index');
            Route::get('create/{id}/{trainer_intake_id}', [ResourceController::class, 'create'])->name('trainer.resource.create');
            Route::post('store/{id}/{trainer_intake_id}', [ResourceController::class, 'store'])->name('trainer.resource.store');
            Route::get('edit/{id}/{trainer_intake_id}', [ResourceController::class, 'edit'])->name('trainer.resource.edit');
            Route::post('update/{id}', [ResourceController::class, 'update'])->name('trainer.resource.update');
        });
        Route::group([
            'prefix' => 'assignment'
        ], function () {
            Route::get('/{id}', [AssignmentController::class, 'index'])->name('trainer.assignment.index');
            Route::get('create/{id}', [AssignmentController::class, 'create'])->name('trainer.assignment.create');
            Route::post('store/{id}', [AssignmentController::class, 'store'])->name('trainer.assignment.store');
            Route::get('edit/{id}/{trainer_intake_id}', [AssignmentController::class, 'edit'])->name('trainer.assignment.edit');
            Route::post('update/{id}/{trainer_intake_id}', [AssignmentController::class, 'update'])->name('trainer.assignment.update');
            Route::group([
                'prefix' => 'file'
            ], function () {
                Route::get('create/{id}', [AssignmentFileController::class, 'create'])->name('trainer.assignment.file.create');
                Route::post('store/{id}', [AssignmentFileController::class, 'store'])->name('trainer.assignment.file.store');
            });
            Route::group([
                'prefix' => 'files'
            ], function () {
                Route::get('create/{id}', [AssignmentFilesController::class, 'create'])->name('trainer.assignment.files.create');
                Route::post('store/{id}', [AssignmentFilesController::class, 'store'])->name('trainer.assignment.files.store');
                Route::get('edit/{id}', [AssignmentFilesController::class, 'edit'])->name('trainer.assignment.files.edit');
                Route::post('update/{id}', [AssignmentFilesController::class, 'update'])->name('trainer.assignment.files.update');
            });
            Route::group([
                'prefix' => 'question'
            ], function () {
                Route::get('create/{id}', [AssignmentQuestionController::class, 'create'])->name('trainer.assignment.question.create');
                Route::post('store/{id}', [AssignmentQuestionController::class, 'store'])->name('trainer.assignment.question.store');
                Route::get('show/{id}', [AssignmentQuestionController::class, 'show'])->name('trainer.assignment.question.show');
                Route::post('update/{id}', [AssignmentQuestionController::class, 'update'])->name('trainer.assignment.question.update');
            });
            Route::group([
                'prefix' => 'mcq'
            ], function () {
                Route::get('create/{id}', [AssignmentMCQController::class, 'create'])->name('trainer.assignment.mcq.create');
                Route::post('store/{id}', [AssignmentMCQController::class, 'store'])->name('trainer.assignment.mcq.store');
                Route::post('finish/{id}', [AssignmentMCQController::class, 'finish'])->name('trainer.assignment.mcq.finish');
                Route::get('show/{id}', [AssignmentMCQController::class, 'show'])->name('trainer.assignment.mcq.show');
                Route::get('edit/{id}', [AssignmentMCQController::class, 'edit'])->name('trainer.assignment.mcq.edit');
                Route::post('update/{id}', [AssignmentMCQController::class, 'update'])->name('trainer.assignment.mcq.update');
            });
            Route::group([
                'prefix' => 'submission'
            ], function () {
                Route::get('/{id}/{trainer_intake_id}', [SubmissionController::class, 'index'])->name('trainer.submission.index');
                Route::get('edit/{id}/{trainer_intake_id}', [SubmissionController::class, 'edit'])->name('trainer.submission.edit');
                Route::post('update/{id}/{trainer_intake_id}', [SubmissionController::class, 'update'])->name('trainer.submission.update');
                Route::get('files/{id}/{trainer_intake_id}', [SubmissionController::class, 'files'])->name('trainer.submission.files.index');
                Route::get('files/edit/{id}/{trainer_intake_id}', [SubmissionController::class, 'filesEdit'])->name('trainer.submission.files.edit');
                Route::post('files/update/{id}/{trainer_intake_id}', [SubmissionController::class, 'filesUpdate'])->name('trainer.submission.files.update');
                Route::get('question/{id}/{trainer_intake_id}', [SubmissionController::class, 'question'])->name('trainer.submission.question.index');
                Route::post('question/remarks/{id}', [SubmissionController::class, 'questionRemarks'])->name('trainer.submission.question.remarks');
                Route::get('mcq/{id}/{trainer_intake_id}', [SubmissionController::class, 'mcq'])->name('trainer.submission.mcq.index');
                Route::post('mcq/{id}', [SubmissionController::class, 'mcqRemarks'])->name('trainer.submission.mcq.remarks');
                Route::get('pdf/{id}/{type}', [SubmissionController::class, 'pdf'])->name('trainer.submission.pdf');
                Route::post('pdfshow/{id}/{type}/save', [SubmissionController::class, 'pdfsave'])->name('trainer.submission.pdfsave');
                Route::post('pdf/{id}/integrity-check', [SubmissionController::class, 'pdfIntegrityCheck'])->name('trainer.submission.pdf.integrity-check');
                Route::post('{id}/question/integrity-check', [SubmissionController::class, 'questionIntegrityCheck'])->name('trainer.submission.question.integrity-check');
                Route::post('{id}/integrity-source/export', [SubmissionController::class, 'exportIntegritySourcePdf'])->name('trainer.submission.pdf.integrity-source.export');
            });
            Route::group([
                'prefix' => 'resubmission'
            ], function () {
                Route::get('/{id}/{trainer_intake_id}', [ResubmissionController::class, 'index'])->name('trainer.resubmission.index');
                Route::get('edit/{id}/{trainer_intake_id}', [ResubmissionController::class, 'edit'])->name('trainer.resubmission.edit');
                Route::post('update/{id}/{trainer_intake_id}', [ResubmissionController::class, 'update'])->name('trainer.resubmission.update');
            });
        });
        Route::group([
            'prefix' => 'onlineclass'
        ], function () {
            Route::get('/{id}', [OnlineClassController::class, 'index'])->name('trainer.onlineclass.index');
            Route::get('create/{id}', [OnlineClassController::class, 'create'])->name('trainer.onlineclass.create');
            Route::post('store/{id}', [OnlineClassController::class, 'store'])->name('trainer.onlineclass.store');
        });
        Route::group([
            'prefix' => 'team'
        ], function () {
            Route::get('/{id}', [TeamController::class, 'index'])->name('trainer.team.index');
            Route::post('store', [TeamController::class, 'store'])->name('trainer.team.store');
        });
        Route::group([
            'prefix' => 'student'
        ], function () {
            Route::get('/{id}', [StudentController::class, 'index'])->name('trainer.student.index');
            Route::get('/unit/{id}/{intake_course_id}', [StudentController::class, 'unit'])->name('trainer.student.unit.index');
            Route::get('assignment/{id}/{intake_unit_id}', [StudentController::class, 'assignment'])->name('trainer.student.assignment.index');
            Route::get('unit/complete/{id}/{intake_unit_id}', [StudentController::class, 'completeUnit'])->name('trainer.student.unit.complete');
        });
        Route::group([
            'prefix' => 'attendance'
        ], function () {
            Route::get('/{id}/{year}/{month}', [AttendanceController::class, 'index'])->name('trainer.attendance.index');
            Route::post('mark/{sessionId}', [AttendanceController::class, 'mark'])->name('trainer.attendance.mark');
            Route::get('year', [AttendanceController::class, 'getMonthByYear']);
        });
    });
    Route::group([
        'prefix' => 'students'
    ], function () {
        Route::get('/', [StudentStudentController::class, 'index'])->name('trainer.students.index');
        Route::get('semester/{student_id}/{id}', [StudentStudentController::class, 'semester'])->name('trainer.students.semester.index');
        Route::get('subject/{student_id}/{id}', [StudentStudentController::class, 'subject'])->name('trainer.students.subject.index');
        Route::get('unit/{student_id}/{id}', [StudentStudentController::class, 'unit'])->name('trainer.students.unit.index');
        Route::get('assignment/{id}/{intake_unit_id}', [StudentStudentController::class, 'assignment'])->name('trainer.students.assignment.index');
        Route::get('unit/complete/{id}/{intake_unit_id}', [StudentStudentController::class, 'completeUnit'])->name('trainer.students.unit.complete');
        Route::group([
            'prefix' => 'subject/students/marking'
        ], function () {
            Route::get('/{id}', [StudentStudentController::class, 'subjectMarks'])->name('trainer.students.subject.mark.index');
            Route::post('update/{id}', [StudentStudentController::class, 'subjectMarksUpdate'])->name('trainer.students.subject.mark.update');
        });
        Route::group([
            'prefix' => 'unit/students/marking'
        ], function () {
            Route::get('/{id}', [StudentStudentController::class, 'unitMarks'])->name('trainer.students.unit.mark.index');
            Route::post('update/{id}', [StudentStudentController::class, 'unitMarksUpdate'])->name('trainer.students.unit.mark.update');
        });
        Route::group([
            'prefix' => 'chat'
        ], function () {
            Route::get('{id}', [ChatController::class, 'index'])->name('trainer.students.chat.index');
            Route::post('sendMessage/{id}', [ChatController::class, 'sendMessage'])->name('trainer.students.chat.send.message');
        });
    });
    Route::group([
        'prefix' => 'onlineclass/group'
    ], function () {
        Route::get('/', [OnlineClassGroupController::class, 'index'])->name('trainer.onlineclass.group.index');
        Route::get('create', [OnlineClassGroupController::class, 'create'])->name('trainer.onlineclass.group.create');
        Route::post('store', [OnlineClassGroupController::class, 'store'])->name('trainer.onlineclass.group.store');
        Route::get('edit/{id}', [OnlineClassGroupController::class, 'edit'])->name('trainer.onlineclass.group.edit');
        Route::post('update/{id}', [OnlineClassGroupController::class, 'update'])->name('trainer.onlineclass.group.update');
        Route::group([
            'prefix' => 'class'
        ], function () {
            Route::get('/{id}', [OnlineClassGroupClassController::class, 'index'])->name('trainer.onlineclass.group.class.index');
            Route::get('create/{id}', [OnlineClassGroupClassController::class, 'create'])->name('trainer.onlineclass.group.class.create');
            Route::post('store/{id}', [OnlineClassGroupClassController::class, 'store'])->name('trainer.onlineclass.group.class.store');
        });
        Route::group([
            'prefix' => 'teams'
        ], function () {
            Route::get('/{id}', [OnlineClassGroupTeamController::class, 'index'])->name('trainer.onlineclass.group.teams.index');
            Route::post('store', [OnlineClassGroupTeamController::class, 'store'])->name('trainer.onlineclass.group.teams.store');
        });
    });
    Route::get('calendar', [CalendarController::class, 'index'])->name('trainer.calendar.index');
    Route::get('calendar/time', [CalendarController::class, 'getTrainerTimeTable']);
    Route::get('assignments', [TrainerAssignmentController::class, 'index'])->name('trainer.assignments.index');
    Route::get('submissions', [TrainerSubmissionController::class, 'index'])->name('trainer.submissions.index');
});
