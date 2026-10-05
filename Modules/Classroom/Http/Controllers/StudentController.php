<?php

namespace Modules\Classroom\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Classroom\Entities\Classroom;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the classroom students.
     * @return Renderable
     */
    public function index($id)
    {
        $classroom = Classroom::findorfail($id);
        if (checkRole('classroom', 'view') == true){
            $students = $classroom->classroomStudents->where('status', 1);
            foreach ($students as $key => $value) {
                $intakes = $value->student->intake->where('status', 1);
                $student_intakes = [];
                foreach ($intakes as $intake) {
                    $student_intakes[] = $intake->intakeCourse->course->course_name . ' (' . $intake->intakeCourse->intake->name . ')';
                }
                $students[$key]['intakes'] = implode(', ', $student_intakes);
            }
            return view('classroom::student.index', compact('classroom', 'students'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
