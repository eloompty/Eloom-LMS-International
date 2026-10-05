<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Student\Entities\StudentIntakeCourse;

class IntakeCourseStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake student.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeCourse = IntakeCourse::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourse->course_id);
        if (checkRole('intake', 'view') == true && $intakeCourse && $check == true) {
            $students = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')->where('intake_course_id', $id)->whereIn('students.status', [0,1])->where('students.is_enrolled', 1)->get(['student_id']);
            activityLog('Admin', $intakeCourse->reference_name . ' student lists opened');
            return view('intake::course.student.index', compact('students', 'intakeCourse'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
