<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeTime;
use Modules\Student\Entities\StudentIntakeCourse;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Student', 'Opened Calendar menu from web');
        return view('student::student.calendar.index');
    }

    /* Get Time Table of assigned Intake Course */
    public function getTimeTable()
    {
        $id = Auth::guard('student')->user()->id;
        $courses = StudentIntakeCourse::where('student_id', $id)->whereIn('status', [1, 3])->get();
        $data = [];
        foreach ($courses as $key => $value) {
            $intakeTimes = IntakeTime::where('intake_course_id', $value->intake_course_id)->where('status', 1)->get();
            foreach ($intakeTimes as $time) {
                $date = strtotime($time->date);
                $data[] = [
                    'title' => $time->intakeSubject->subject->name,
                    'start' => date('Y-m-d', $date) . ' ' . $time->from,
                    'end' => date('Y-m-d', $date) . ' ' . $time->to,
                    'allDay' => false,
                    'backgroundColor' => '#0073b7', //Blue
                    'borderColor' => '#0073b7' //Blue
                ];
            }
        }
        return response()->json($data);
    }
}
