<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Social\Entities\Social;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the students.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_course_id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $students = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
                ->select('student_intake_courses.*')->where('students.status', 1)
                ->where('intake_course_id', $id)->whereIn('student_intake_courses.status', [1, 3])
                ->where('student_intake_courses.is_enrolled', 1)
                ->orderBy('student_intake_courses.id', 'desc')->get();
            foreach ($students as $key => $value) {
                $students[$key]['socials'] = Social::where('user_type', 'Student')->where('user_id', $value->student_id)->where('status', 1)->get();
            }
            activityLog('Trainer', 'Opened Student list from courses');
            return view('trainer::trainer.student.index', compact('students', 'trainerIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* List of student units */
    public function unit($id, $intake_course_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_course_id', $intake_course_id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $students = StudentIntakeCourse::where('intake_course_id', $intake_course_id)->where('student_id', $id)->orderBy('id', 'desc')->first();
            $units = $students->studentIntakeUnit->where('status', 1);
            activityLog('Trainer', 'Opened ' . userName('Student', $id) . ' Details from web');
            return view('trainer::trainer.student.unit.index', compact('id', 'trainerIntake', 'units'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* List of student assignments */
    public function assignment($id, $intake_unit_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_unit_id', $intake_unit_id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $submissions = AssignmentSubmission::where('student_id', $id)
                ->join('assignments', 'assignments.id', '=', 'assignment_submissions.assignment_id')
                ->join('intake_units', 'intake_units.id', '=', 'assignments.intake_unit_id')
                ->select('assignment_submissions.*', 'assignments.name', 'assignments.intake_unit_id', 'assignments.path as image', 'assignments.due_date')
                ->where('intake_units.id', $intake_unit_id)
                ->where('assignment_submissions.status', 1)
                ->get();
            $studentIntakeUnit = StudentIntakeUnit::join('student_intake_courses', 'student_intake_courses.id', '=', 'student_intake_units.student_intake_course_id')
                ->where('student_intake_units.intake_unit_id', $intake_unit_id)
                ->where('student_intake_courses.student_id', $id)->select('student_intake_units.is_complete')->first();
            activityLog('Trainer', 'Opened ' . userName('Student', $id) . ' Assignments from web');
            return view('trainer::trainer.student.unit.assignment.index', compact('id', 'submissions', 'trainerIntake', 'studentIntakeUnit'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* Updating student unit status */
    public function completeUnit($id, $intake_unit_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_unit_id', $intake_unit_id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            StudentIntakeUnit::join('student_intake_courses', 'student_intake_courses.id', '=', 'student_intake_units.student_intake_course_id')
                ->where('student_intake_units.intake_unit_id', $intake_unit_id)
                ->where('student_intake_courses.student_id', $id)
                ->update(['student_intake_units.is_complete' => 1]);
            return redirect()->back()->with('success', 'Student Unit completed succesfully');
        } else {
            return abort(404);
        }
    }
}
