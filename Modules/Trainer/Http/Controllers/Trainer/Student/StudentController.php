<?php

namespace Modules\Trainer\Http\Controllers\Trainer\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Social\Entities\Social;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeSemester;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeSubjectMark;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentIntakeUnitMark;
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
    public function index()
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntakes = TrainerIntake::where('trainer_id', $trainer_id)->whereIn('status', [1, 3])->distinct()->get(['intake_course_id']);
        $intake_course_id = $trainerIntakes->pluck('intake_course_id');
        $students = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
            ->select('student_intake_courses.*')->where('students.status', 1)
            ->whereIn('intake_course_id', $intake_course_id)->whereIn('student_intake_courses.status', [1, 3])
            ->orderBy('student_intake_courses.id', 'desc')->get();
        foreach ($students as $key => $value) {
            $students[$key]['socials'] = Social::where('user_type', 'Student')->where('user_id', $value->student_id)->where('status', 1)->get();
        }
        activityLog('Trainer', 'Opened Student list from student menu');
        return view('trainer::trainer.students.index', compact('students'))->with('no', 1);
    }

    /* List of student semester */
    public function semester($student_id, $id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntakes = TrainerIntake::where('trainer_id', $trainer_id)->where('intake_course_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_semester_id']);
        $intake_semester_id = $trainerIntakes->pluck('intake_semester_id');
        $studentCourse = StudentIntakeCourse::where('intake_course_id', $id)->where('student_id', $student_id)->first();
        $semesters = StudentIntakeSemester::where('student_intake_course_id', $studentCourse->id)->whereIn('intake_semester_id', $intake_semester_id)->whereIn('status', [1, 3])->orderBy('id', 'asc')->get();
        activityLog('Trainer', 'Opened ' . userName('Student', $student_id) . ' semester from student menu');
        return view('trainer::trainer.students.semester.index', compact('student_id', 'semesters'))->with('no', 1);
    }

    /* List of student subject */
    public function subject($student_id, $id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntakes = TrainerIntake::where('trainer_id', $trainer_id)->where('intake_semester_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_subject_id']);
        $intake_subject_id = $trainerIntakes->pluck('intake_subject_id');
        $trainerIntake = TrainerIntake::where('trainer_id', $trainer_id)->where('intake_semester_id', $id)->first();
        $studentCourse = StudentIntakeCourse::where('intake_course_id', $trainerIntake->intake_course_id)->where('student_id', $student_id)->first();
        $subjects = StudentIntakeSubject::where('student_intake_course_id', $studentCourse->id)->whereIn('intake_subject_id', $intake_subject_id)->whereIn('status', [1, 3])->orderBy('id', 'asc')->get();
        $intake_course_id = $subjects[0]->studentIntakeCourse->intake_course_id;
        activityLog('Trainer', 'Opened ' . userName('Student', $student_id) . ' semester from student menu');
        return view('trainer::trainer.students.subject.index', compact('student_id', 'subjects', 'intake_course_id'))->with('no', 1);
    }

    /* List of student units */
    public function unit($student_id, $id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $trainerIntakes = TrainerIntake::where('trainer_id', $trainer_id)->where('intake_subject_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_unit_id']);
            $intake_unit_id = $trainerIntakes->pluck('intake_unit_id');
            $studentCourse = StudentIntakeCourse::where('intake_course_id', $trainerIntake->intake_course_id)->where('student_id', $student_id)->first();
            $units = StudentIntakeUnit::where('student_intake_course_id', $studentCourse->id)->whereIn('intake_unit_id', $intake_unit_id)->whereIn('status', [1, 3])->orderBy('id', 'asc')->get();
            activityLog('Trainer', 'Opened ' . userName('Student', $student_id) . ' unit from student menu');
            return view('trainer::trainer.students.unit.index', compact('student_id', 'trainerIntake', 'units'))->with('no', 1);
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
            return view('trainer::trainer.students.unit.assignment.index', compact('id', 'submissions', 'trainerIntake', 'studentIntakeUnit'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /* List of student subject marks */
    public function subjectMarks($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $subjectIntakeSubject = StudentIntakeSubject::findorfail($id);
        $marking_type = $subjectIntakeSubject->studentIntakeCourse->intakeCourse->marking_type;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $subjectIntakeSubject->intake_subject_id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake && $marking_type == 'Subject') {
            $marks = StudentIntakeSubjectMark::where('student_intake_subject_id', $id)->where('status', 1)->orderBy('id', 'asc')->get();
            $student_id = $subjectIntakeSubject->studentIntakeCourse->student_id;
            activityLog('Trainer', 'Opened ' . userName('Student', $student_id) . ' Subject Marking from web');
            return view('trainer::trainer.students.subject.marking.index', compact('marks', 'trainerIntake', 'student_id', 'subjectIntakeSubject'));
        } else {
            return abort(404);
        }
    }

    /* Update Student Subject Marks */
    public function subjectMarksUpdate(Request $request, $id)
    {
        $subjectIntakeSubject = StudentIntakeSubject::findorfail($id);
        $data = $request->all();
        $student_id = $subjectIntakeSubject->studentIntakeCourse->student_id;
        foreach ($data['obtain_marks'] as $key => $value) {
            StudentIntakeSubjectMark::where('id', $key)->where('student_intake_subject_id', $id)->update(['obtain_marks' => $value]);
        }
        activityLog('Trainer', 'Updated ' . userName('Student', $student_id) . ' Subject Marking from web');
        return redirect()->back()->with('success', 'Marks has been updated');
    }

    /* List of student unit marks */
    public function unitMarks($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $studentIntakeUnit = StudentIntakeUnit::findorfail($id);
        $marking_type = $studentIntakeUnit->studentIntakeCourse->intakeCourse->marking_type;
        $trainerIntake = TrainerIntake::where('intake_unit_id', $studentIntakeUnit->intake_unit_id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake && $marking_type == 'Unit') {
            $marks = StudentIntakeUnitMark::where('student_intake_unit_id', $id)->where('status', 1)->orderBy('id', 'asc')->get();
            $student_id = $studentIntakeUnit->studentIntakeCourse->student_id;
            activityLog('Trainer', 'Opened ' . userName('Student', $student_id) . ' Subject Marking from web');
            return view('trainer::trainer.students.unit.marking.index', compact('marks', 'trainerIntake', 'student_id', 'studentIntakeUnit'));
        } else {
            return abort(404);
        }
    }

    /* Update student unit marks */
    public function unitMarksUpdate(Request $request, $id)
    {
        $studentIntakeUnit = StudentIntakeUnit::findorfail($id);
        $data = $request->all();
        $student_id = $studentIntakeUnit->studentIntakeCourse->student_id;
        foreach ($data['obtain_marks'] as $key => $value) {
            StudentIntakeUnitMark::where('id', $key)->where('student_intake_unit_id', $id)->update(['obtain_marks' => $value]);
        }
        activityLog('Trainer', 'Updated ' . userName('Student', $student_id) . ' Subject Marking from web');
        return redirect()->back()->with('success', 'Marks has been updated');
    }
}
