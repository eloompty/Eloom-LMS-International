<?php

namespace Modules\Api\Http\Controllers\V1\Student;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentAnswer;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Attendance\Entities\Attendance;
use Modules\Course\Entities\Unit;
use Modules\Intake\Entities\IntakeUnitTime;
use Modules\Notification\Entities\Notification;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\OnlineClass\Entities\OnlineClassGroupStudent;
use Modules\OnlineClass\Entities\OnlineClassGroupTrainer;
use Modules\Resource\Entities\Resource;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class StudentController extends Controller
{
    /**
     * @OA\Get(
     ** path="/student/dashboard",
     *   tags={"Students"},
     *   summary="Student Dashboard",
     *   description="Student Dashboard",
     *   operationId="studentDashboard",     
     *   security={{"passport":{"*"}}},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentDashboard(Request $request)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            activityLog('Student API', 'Opened App Dashboard');
            $studentIntakes = $student->intake->whereIn('status', [1, 3]);
            $data = [];
            foreach ($studentIntakes as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'intake_course_id' => $value->intake_course_id,
                    'intake' => $value->intakeCourse->intake->name,
                    'course_name' => $value->intakeCourse->course->course_name,
                    'course_code' => $value->intakeCourse->course->course_code,
                    'starting_date' => $value->starting_date,
                    'ending_date' => $value->ending_date,
                    'is_locked' => checkUnitLock($value->status)
                ];
            }
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Student Dashbaord',
                'full_name' => userName('Student', $student->id),
                'initial' => initialName('Student', $student->id),
                'email' => $student->email,
                'image' => asset($student->image),
                'data' => $data,
            ];
            return response($response, 200);
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/course/{id}",
     *   tags={"Students"},
     *   summary="Student Unit List By Course",
     *   description="Student Unit List By Course",
     *   operationId="studentCourseUnit",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Id of course (intake_course_id)",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentCourseUnit(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $studentIntake = $student->intake->where('intake_course_id', $id)->whereIn('status', [1, 3])->first();
            if ($studentIntake) {
                $studentIntakeUnit = StudentIntakeUnit::where('student_intake_course_id', $studentIntake->id)->whereIn('status', [1, 3])->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
                $data = [];
                foreach ($studentIntakeUnit as $key => $value) {
                    $data[] = [
                        'id' => $value->id,
                        'intake_unit_id' => $value->intake_unit_id,
                        'name' => $value->intakeUnit->unit->name,
                        'code' => $value->intakeUnit->unit->code,
                        'duration' => $value->duration,
                        'starting_date' => $value->starting_date,
                        'ending_date' => $value->ending_date,
                        'due_date' => $value->due_date,
                        'is_locked' => checkUnitLock($value->status),
                        'outcome' => getIdentifierValue('OUTCOME IDENTIFIER - NATIONAL', $value->outcome)
                    ];
                }
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Student Unit List',
                    'course_name' => $studentIntake->intakeCourse->course->course_name,
                    'course_code' => $studentIntake->intakeCourse->course->course_code,
                    'data' => $data
                ];
                return response($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'You are not enrolled to this intake'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/unit/time/{id}",
     *   tags={"Students"},
     *   summary="Student Unit Time Table",
     *   description="Student Unit Time Table",
     *   operationId="studentUnitTimeTable",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Intake Unit Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentUnitTimeTable(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $studentIntake = StudentIntakeUnit::where('intake_unit_id', $id)->where('status', 1)->first();
            if ($studentIntake) {
                $intakeUnitTime = IntakeUnitTime::where('intake_unit_id', $id)->where('status', 1)->get();
                $data = [];
                foreach ($intakeUnitTime as $key => $value) {
                    $data[] = [
                        'day' => $value->day,
                        'from' => $value->from,
                        'to' => $value->to
                    ];
                }
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Student Time Table',
                    'data' => $data
                ];
                return response($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'You are not enrolled to this intake'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/trainer/{id}",
     *   tags={"Students"},
     *   summary="Student Assigned Trainer",
     *   description="Student Assigned Trainer",
     *   operationId="studentAssignedTrainer",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Intake Unit Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentAssignedTrainer(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $studentIntake = StudentIntakeUnit::where('intake_unit_id', $id)->where('status', 1)->first();
            if ($studentIntake) {
                $trainerIntake = TrainerIntake::where('intake_unit_id', $id)->where('status', 1)->first();
                if ($trainerIntake) {
                    $data = [
                        'id' => $trainerIntake->trainer_id,
                        'name' => userName('Trainer', $trainerIntake->trainer_id),
                        'email' => $trainerIntake->trainer->email,
                        'phone' => $trainerIntake->trainer->phone,
                        'mobile' => $trainerIntake->trainer->mobile,
                        'image' => asset($trainerIntake->trainer->image),
                    ];
                    $response = [
                        'status' => true,
                        'status_code' => 200,
                        'message' => 'Student Assigned Trainer',
                        'data' => $data
                    ];
                    return response($response, 200);
                } else {
                    $response = [
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Trainer is not assigned to this intake'
                    ];
                    return response($response, 422);
                }
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'You are not enrolled to this intake'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/resource/{id}",
     *   tags={"Students"},
     *   summary="Student Resources",
     *   description="Student Resources",
     *   operationId="studentResources",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Intake Unit Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentResources(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $studentIntake = StudentIntakeUnit::where('intake_unit_id', $id)->where('status', 1)->first();
            if ($studentIntake) {
                $unit = Unit::find($studentIntake->intakeUnit->unit_id);
                if ($unit) {
                    activityLog('Student API', 'Opened Resources of ' . $unit->name . ' from App');
                    $resources = Resource::where('user_type', 'Student')->where('unit_id', $id)->where('status', 1)->get();
                    $data = [];
                    foreach ($resources as $key => $value) {
                        $data[] = [
                            'id' => $value->id,
                            'name' => $value->name,
                            'resource_type' => $value->resource_type,
                            'category' => $value->category->name,
                            'path' => asset($value->path),
                        ];
                    }
                    $response = [
                        'status' => true,
                        'status_code' => 200,
                        'message' => 'Student Resources',
                        'data' => $data
                    ];
                    return response($response, 200);
                } else {
                    $response = [
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Unit Not Found'
                    ];
                    return response($response, 422);
                }
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'You are not enrolled to this intake'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/assignment/{id}",
     *   tags={"Students"},
     *   summary="Student Assignments",
     *   description="Student Assignments",
     *   operationId="studentAssignment",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Intake Unit Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentAssignment(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $studentIntake = StudentIntakeUnit::where('intake_unit_id', $id)->where('status', 1)->first();
            if ($studentIntake) {
                activityLog('Student API', 'Opened Assignment of ' . $studentIntake->intakeUnit->unit->name . ' from App');
                $assignments = Assignment::where('intake_unit_id', $id)->where('status', 1)->get();
                $data = [];
                foreach ($assignments as $key => $value) {
                    $data[] = [
                        'id' => $value->id,
                        'name' => $value->name,
                        'type' => $value->type,
                        'submission_date' => dateFormat($value->due_date),
                    ];
                    if ($value->type == 'file') {
                        $data[$key]['file'] = asset($value->path);
                    }
                }
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Student Assignments',
                    'data' => $data
                ];
                return response($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'You are not enrolled for this intake'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/assignment/detail/{id}",
     *   tags={"Students"},
     *   summary="Student Assignment Detail",
     *   description="Student Assignment Detail",
     *   operationId="studentAssignmentDetail",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Assignment Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentAssignmentDetail(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $assignment = Assignment::find($id);
            if ($assignment) {
                $studentIntake = StudentIntakeUnit::where('intake_unit_id', $assignment->intake_unit_id)->where('status', 1)->first();
                if ($studentIntake) {
                    if ($assignment->type == 'question') {
                        $questions = AssignmentQuestion::where('assignment_id', $id)->where('status', 1)->get();
                        foreach ($questions as $key => $value) {
                            $question[] = [
                                'id' => $value->id,
                                'question' => $value->question,
                            ];
                        }
                    } elseif ($assignment->type == 'mcq') {
                        $questions = AssignmentQuestion::where('assignment_id', $id)->where('status', 1)->get();
                        foreach ($questions as $key => $value) {
                            foreach ($value->choice as $choice) {
                                $option[] = [
                                    'id' => $choice->id,
                                    'choice' => $choice->choice,
                                    'is_correct' => $choice->is_correct
                                ];
                            }
                            $question[] = [
                                'id' => $value->id,
                                'question' => $value->question,
                                'choices' => $option
                            ];
                        }
                    } else {
                        $question = [asset($assignment->path)];
                    }
                    $data = [
                        'id' => $assignment->id,
                        'type' => $assignment->type,
                        'assignment' => $question,
                    ];
                    activityLog('Student API', 'Opened Assignment Details of ' . $studentIntake->intakeUnit->unit->name . ' from App');
                    $response = [
                        'status' => true,
                        'status_code' => 200,
                        'message' => 'Student Assignment Details',
                        'data' => $data
                    ];
                    return response($response, 200);
                } else {
                    $response = [
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'You are not enrolled for this intake'
                    ];
                    return response($response, 422);
                }
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Assignment not found'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/assignment/submission/{id}",
     *   tags={"Students"},
     *   summary="Student Assignment Submissions",
     *   description="Student Assignment Submissions",
     *   operationId="studentAssignmentSubmission",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Assignment Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentAssignmentSubmission(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $assignment = Assignment::find($id);
            if ($assignment) {
                $submissions = AssignmentSubmission::where('assignment_id', $id)->where('status', 1)->get();
                $data = [];
                foreach ($submissions as $key => $value) {
                    if ($value->assignment_grade_id == NULL) {
                        $grade = 'Not Graded';
                    } else {
                        $grade = $value->assignmentGrade->name;
                    }
                    $data[] = [
                        'id' => $value->id,
                        'name' => $value->assignment->name,
                        'type' => $value->assignment->type,
                        'grade' => $grade,
                        'remarks' => $value->remarks,
                        'credits' => $value->credits,
                        'submitted_date' => dateFormat($value->created_at),
                    ];
                }
                activityLog('Student API', 'Opened Assignment Submission from App');
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Student Assignment Submissions',
                    'data' => $data
                ];
                return response($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Assignment not found'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/assignment/submission/detail/{id}",
     *   tags={"Students"},
     *   summary="Student Assignment Submission Detail",
     *   description="Student Assignment Submission Detail",
     *   operationId="studentAssignmentSubmissionDetail",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Submission Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentAssignmentSubmissionDetail(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $submission = AssignmentSubmission::find($id);
            if ($submission->assignment_grade_id == NULL) {
                $grade = 'Not Graded';
            } else {
                $grade = $submission->assignmentGrade->name;
            }
            if ($submission->assignment->type == 'file') {
                $assignment_submission = asset($submission->path);
            } else if ($submission->assignment->type == 'question') {
                $questions = AssignmentQuestion::where('assignment_id', $submission->assignment_id)->where('status', 1)->get();
                foreach ($questions as $key => $value) {
                    $answer = AssignmentAnswer::where('assignment_question_id', $value->id)->first();
                    $assignment_submission[] = [
                        'question' => $value->question,
                        'answer' => $answer->answer,
                    ];
                }
            } else {
                $questions = AssignmentQuestion::where('assignment_id', $submission->assignment_id)->where('status', 1)->get();
                foreach ($questions as $key => $value) {
                    $assignment_anwer = AssignmentAnswer::where('assignment_question_id', $value->id)->first();
                    foreach ($value->choice as $choice) {
                        if ($assignment_anwer->answer == $choice->id) $answer = true;
                        else $answer = false;
                        if ($choice->is_correct == 1) $is_correct = true;
                        else $is_correct = false;
                        $option[] = [
                            'id' => $choice->id,
                            'choice' => $choice->choice,
                            'is_correct' => $is_correct,
                            'answer' => $answer,
                        ];
                    }
                    $assignment_submission[] = [
                        'question' => $value->question,
                        'option' => $option,
                    ];
                }
            }
            $data = [
                'id' => $submission->id,
                'name' => $submission->assignment->name,
                'type' => $submission->assignment->type,
                'grade' => $grade,
                'remarks' => $submission->remarks,
                'credits' => $submission->credits,
                'submitted_date' => dateFormat($submission->created_at),
                'submission' => $assignment_submission
            ];
            activityLog('Student API', 'Opened Assignment Submission Details of ' . $submission->assignment->name . ' from App');
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Student Assignment Submissions',
                'data' => $data
            ];
            return response($response, 200);
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/notification",
     *   tags={"Students"},
     *   summary="Student Notifications",
     *   description="Student Notifications",
     *   operationId="studentNotification",     
     *   security={{"passport":{"*"}}},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentNotification(Request $request)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $notifications = Notification::where('user_type', 'Student')->where('user_id', $student->id)->where('status', 1)->get();
            $data = [];
            foreach ($notifications as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'sender_type' => $value->sender_type,
                    'sender_id' => $value->sender_id,
                    'title' => $value->title,
                    'body' => $value->body,
                    'type' => $value->type,
                    'link' => $value->link,
                    'status' => $value->status,
                ];
            }
            activityLog('Student API', 'Opened Notification from App');
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Student Notifications',
                'data' => $data
            ];
            return response($response, 200);
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/onlineclass/{id}",
     *   tags={"Students"},
     *   summary="Student Online Classes",
     *   description="Student Online Classes",
     *   operationId="studentOnlineClass",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Intake Unit Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentOnlineClass(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $data = [];
            if ($id == 0) {
                $courses = $student->intake->whereIn('status', [1, 3]);
                foreach ($courses as $key => $value) {
                    $intakeUnits = StudentIntakeUnit::where('student_intake_course_id', $value->id)->where('status', 1)->get();
                    foreach ($intakeUnits as $key => $intakeUnit) {
                        $zooms = OnlineClass::where('intake_unit_id', $intakeUnit->intake_unit_id)->where('status', 1)->get();
                        if (count($zooms) > 0) {
                            foreach ($zooms as $key => $value) {
                                $data[] = [
                                    'id' => $value->id,
                                    'meeting_id' => $value->meeting_id,
                                    'topic' => $value->topic,
                                    'agenda' => $value->agenda,
                                    'join_url' => $value->join_url,
                                    'password' => $value->password,
                                ];
                            }
                        }
                    }
                }
            } else {
                $studentIntake = StudentIntakeUnit::where('intake_unit_id', $id)->where('status', 1)->first();
                if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student->id) {
                    $zooms = OnlineClass::where('intake_unit_id', $id)->where('status', 1)->get();
                    foreach ($zooms as $key => $value) {
                        $data[] = [
                            'id' => $value->id,
                            'meeting_id' => $value->meeting_id,
                            'topic' => $value->topic,
                            'agenda' => $value->agenda,
                            'join_url' => $value->join_url,
                            'password' => $value->password,
                        ];
                    }
                } else {
                    $response = [
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'You are not enrolled for this intake'
                    ];
                    return response($response, 422);
                }
            }
            activityLog('Student API', 'Opened Online Class from App');
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Student Online Classes',
                'data' => $data
            ];
            return response($response, 200);
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/onlineclass/group/all",
     *   tags={"Students"},
     *   summary="Student Group Online Classes",
     *   description="Student Group Online Classes",
     *   operationId="studentGroupOnlineClass",     
     *   security={{"passport":{"*"}}},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentGroupOnlineClass(Request $request)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $data = [];
            $groups = OnlineClassGroupStudent::where('student_id', $student->id)->orderBy('id', 'desc')->get();
            foreach ($groups as $key => $value) {
                $trainerGroup = OnlineClassGroupTrainer::where('online_class_group_id', $value->online_class_group_id)->first();
                $data[] = [
                    'id' => $value->id,
                    'group_id' => $value->online_class_group_id,
                    'name' => $value->group->name,
                    'trainer' => userName('Trainer', $trainerGroup->trainer_id),
                ];
            }
            activityLog('Student API', 'Opened Online Class Group from App');
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Student Groups for online classes',
                'data' => $data
            ];
            return response($response, 200);
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/onlineclass/group/class/{id}",
     *   tags={"Students"},
     *   summary="Student Online Class Zoom",
     *   description="Student Online Class Zoom",
     *   operationId="studentGroupOnlineClassById",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Group Class Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentGroupOnlineClassById(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $studentGroup = OnlineClassGroupStudent::where('student_id', $student->id)->where('online_class_group_id', $id)->first();
            if ($studentGroup) {
                $data = [];
                $zooms = OnlineClass::where('intake_unit_id', 0)->where('online_class_group_id', $id)->get();
                foreach ($zooms as $key => $value) {
                    $data[] = [
                        'id' => $value->id,
                        'meeting_id' => $value->meeting_id,
                        'topic' => $value->topic,
                        'agenda' => $value->agenda,
                        'join_url' => $value->join_url,
                        'password' => $value->password,
                    ];
                }
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Student Group Online Classes',
                    'data' => $data
                ];
                return response($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Group Not Found'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/onlineclass/recording/{id}",
     *   tags={"Students"},
     *   summary="Student Online Class Recording",
     *   description="Student Online Class Recording",
     *   operationId="studentOnlineClassRecording",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Online Class Id",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentOnlineClassRecording(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $zoom = OnlineClass::find($id);
            if ($zoom) {
                $recording = $zoom->recording;
                if ($recording) {
                    $data = [
                        'id' => $recording->id,
                        'recording' => $recording->play_url,
                        'password' => $recording->password,
                    ];
                    activityLog('Student API', 'Opened Online Classs Recording of ' . $zoom->meeting_id . ' from App');
                    $response = [
                        'status' => true,
                        'status_code' => 200,
                        'message' => 'Student Online Class Recording',
                        'data' => $data
                    ];
                    return response($response, 200);
                } else {
                    $response = [
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Zoom recording not found',
                        'data' => NULL
                    ];
                    return response($response, 422);
                }
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Zoom class not found'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/assignment/due/submission",
     *   tags={"Students"},
     *   summary="Student Due Assignment",
     *   description="Student Due Assignment",
     *   operationId="studentAssignmentDue",     
     *   security={{"passport":{"*"}}},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentAssignmentDue(Request $request)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $courses = $student->intake->whereIn('status', [1, 3]);
            $intakeUnits = [];
            $assignments = [];
            $submissions = [];
            $data = [];
            foreach ($courses as $key => $value) {
                $intakeUnits = StudentIntakeUnit::where('student_intake_course_id', $value->id)->where('status', 1)->get();
                foreach ($intakeUnits as $key => $intakeUnit) {
                    $assignments = Assignment::where('intake_unit_id', $intakeUnit->intake_unit_id)->where('status', 1)->orderBy('id', 'desc')->get();
                    if ($assignments->count() > 0) {
                        foreach ($assignments as $key => $assignment) {
                            $submissions = $assignment->submission->where('student_id', $student->id)->count();
                            if ($submissions ==  0) {
                                $assignment['student_intake_id'] = $intakeUnit->id;
                                if ($assignment->due_date < date('Y-m-d')) {
                                    $grade = 'Over Due';
                                } else {
                                    $grade = 'Due';
                                }
                                $data[] = [
                                    'id' => $assignment->id,
                                    'name' => $assignment->name,
                                    'intake_unit_id' => $assignment->intake_unit_id,
                                    'unit_name' => $assignment->intakeUnit->unit->name,
                                    'trainer' => userName('Trainer', $assignment->trainer_id),
                                    'due_date' => dateFormat($assignment->due_date),
                                    'grade' => $grade
                                ];
                            }
                        }
                    }
                }
            }
            activityLog('Student API', 'Opened Assignment Due from App');
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Student Notifications',
                'data' => $data
            ];
            return response($response, 200);
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/attendance/{id}",
     *   tags={"Students"},
     *   summary="Student Attendance",
     *   description="Student Attendance",
     *   operationId="studentAttendance",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Id from intake unit",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentAttendance(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $studentIntake = StudentIntakeUnit::find($id);
            if ($studentIntake && $studentIntake->studentIntakeCourse->student_id == $student->id) {
                $period = new DatePeriod(
                    new DateTime($studentIntake->intakeUnit->starting_date),
                    new DateInterval('P1D'),
                    new DateTime($studentIntake->intakeUnit->ending_date)
                );
                $data = [];
                foreach ($period as $key => $value) {
                    $dateArray[] = $value->format('Y-m-d');
                    foreach ($dateArray as $date) {
                        $studentAttendance = Attendance::where('student_id', $student->id)->where('intake_unit_id', $id)->whereNull('intake_subject_id')->where('date', $date)->first();
                        if ($studentAttendance) {
                            $attendance = 'Present';
                        } else {
                            $attendance = 'Absent';
                        }
                        $dateArray[$key] = [
                            'date' => $date,
                            'attendance' => $attendance
                        ];
                    }
                }
                foreach ($dateArray as $key => $value) {
                    $data[] = [
                        'date' => dateFormat($value['date']),
                        'attendance' => $value['attendance']
                    ];
                }
                activityLog('Student API', 'Opened Attendance from App');
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Student Attendance',
                    'data' => $data
                ];
                return response($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Intake Unit Not Found',
                    'data' => NULL
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Get(
     ** path="/student/fee/{id}",
     *   tags={"Students"},
     *   summary="Student Fee Details",
     *   description="Student Fee Details",
     *   operationId="studentFee Details",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Id of course (intake_course_id)",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentFeeDetails(Request $request, $id)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            $studentIntake = $student->intake->where('intake_course_id', $id)->whereIn('status', [1, 3])->first();
            if ($studentIntake) {
                $studentCourseFee = StudentIntakeCourseFee::where('student_id', $student->id)->where('intake_course_id', $id)->first();
                $studentCourseFeeInstallments = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $studentCourseFee->id)->orderBy('due_date')->get();
                $data = [];
                $p_amount = [];
                $r_amount = [];
                foreach ($studentCourseFeeInstallments as $installment) {
                    $paid = $installment->studentIntakeCourseFeePayment;
                    if ($paid) {
                        $p_amount[] = $paid->paid_amount;
                        $r_amount[] = $paid->remaining_amount;
                        if ($paid->paymentRefund && $paid->paymentRefund->reinstate == 1) {
                            $ri_amount[] = $paid->paid_amount;
                        } else {
                            $ri_amount[] = 0;
                        }
                    } else {
                        $p_amount[] = 0;
                        $r_amount[] = 0;
                        $ri_amount[] = 0;
                    }
                    if ($installment->status == 3) {
                        $rf_amount[] = $installment->amount;
                    } else {
                        $rf_amount[] = 0;
                    }
                    if ($installment->due_date == NULL) $due_date = '-';
                    else $due_date = dateFormat($installment->due_date);
                    $data[] = [
                        'id' => $installment->id,
                        'name' => $installment->name,
                        'amount' => $installment->amount,
                        'due_date' => $due_date,
                        'status' => studentPaymentStatus($installment->status)
                    ];
                }
                if ($studentCourseFee->enrollment_fee_wavier == 1) {
                    $enrollment_fee = 0;
                } else {
                    $enrollment_fee = $studentCourseFee->enrollment_fee;
                }
                if ($studentCourseFee->material_fee_wavier == 1) {
                    $material_fee = 0;
                } else {
                    $material_fee = $studentCourseFee->material_fee;
                }
                $fee = $studentCourseFee->fee;
                $total_fee = $enrollment_fee + $material_fee + $fee;
                $total_intsallment = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $studentCourseFee->id)->sum('amount');
                $paid_installment = array_sum($p_amount);
                $remaining = $total_fee - ($total_intsallment - array_sum($r_amount) - array_sum($ri_amount));
                $remaining_installment = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $studentCourseFee->id)->where('status', 1)->sum('amount');
                $refunded_amount = array_sum($rf_amount);
                $student_intake_course = StudentIntakeCourse::where('student_id', $student->id)->where('intake_course_id', $id)->first();
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Student Fee List',
                    'fee' => $studentCourseFee->fee,
                    'initial_fee' => $studentCourseFee->initial_fee,
                    'enrollment_fee' => $studentCourseFee->enrollment_fee,
                    'material_fee' => $studentCourseFee->material_fee,
                    'total_fee' => $total_fee,
                    'paid_amount' => $paid_installment,
                    'remaining_amount' => $remaining_installment,
                    'refunded_amount' => $refunded_amount,
                    'starting_date' => dateFormat($student_intake_course->starting_date),
                    'ending_date' => dateFormat($student_intake_course->ending_date),
                    'data' => $data
                ];
                return response($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'You are not enrolled to this intake'
                ];
                return response($response, 422);
            }
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }
}
