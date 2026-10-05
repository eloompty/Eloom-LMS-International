<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeCourseTime;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Intake\Entities\IntakeUnitTime;
use Modules\Resource\Entities\Resource;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the course.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Trainer', 'Opened Course menu from web');
        $id = Auth::guard('trainer')->user()->id;
        $courses = TrainerIntake::where('trainer_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_course_id']);
        foreach ($courses as $key => $value) {
            $courses[$key]['intakeCourseTime'] = IntakeCourseTime::where('intake_course_id', $value->intake_course_id)->where('status', 1)->get();
            $courses[$key]['enrolled_student'] = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
                ->select('student_intake_courses.*')->where('students.status', 1)
                ->where('intake_course_id', $value->intake_course_id)->whereIn('student_intake_courses.status', [1, 3])
                ->orderBy('student_intake_courses.id', 'desc')->count();
        }
        return view('trainer::trainer.course.index', compact('courses'))->with('no', 1);
    }


    /* Trainer Semester List */
    public function semester($id)
    {
        activityLog('Trainer', 'Opened semester list from web');
        $trainer_id = Auth::guard('trainer')->user()->id;
        $semesters = TrainerIntake::where('trainer_id', $trainer_id)->where('intake_course_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_semester_id']);
        return view('trainer::trainer.semester.index', compact('semesters'))->with('no', 1);
    }

    /* Trainer Subject List */
    public function subject($id)
    {
        activityLog('Trainer', 'Opened subject list from web');
        $trainer_id = Auth::guard('trainer')->user()->id;
        $subjects = TrainerIntake::where('trainer_id', $trainer_id)->where('intake_semester_id', $id)->whereIn('status', [1, 3])->distinct()->get(['intake_subject_id']);
        return view('trainer::trainer.subject.index', compact('subjects', 'id'))->with('no', 1);
    }

    /* Edit Trainer Subject */
    public function editSubject($id)
    {
        $subject = TrainerIntake::where('intake_subject_id', $id)->first();
        if ($subject) {
            activityLog('Trainer', 'Opened ' . $subject->intakeSubject->subject->name . ' edit from web');
            $trainer_id = Auth::guard('trainer')->user()->id;
            return view('trainer::trainer.subject.edit', compact('subject'));
        } else {
            return abort(404);
        }
    }

    /* Update Trainer Subject */
    public function updateSubject(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $subject = TrainerIntake::find($id);
        $subject->update($data);
        $intakeSubject = IntakeSubject::where('id', $subject->intake_subject_id)->first();
        $intakeSubject->update($data);
        $studentIntakeSubjects = StudentIntakeSubject::where('intake_subject_id', $intakeSubject->id)->get();
        foreach ($studentIntakeSubjects as $key => $value) {
            $value->update($data);
        }
        activityLog('Trainer', 'Updated ' . $intakeSubject->subject->name . ' subject');
        $trainer_id = Auth::guard('trainer')->user()->id;
        return redirect()->route('trainer.subject.index', $subject->intake_semester_id)->with('success', 'Subject has been updated successfully');
    }

    /* Trainer Unit List */
    public function unit($id)
    {
        activityLog('Trainer', 'Opened units list from web');
        $tainer_id = Auth::guard('trainer')->user()->id;
        $units = TrainerIntake::where('trainer_id', $tainer_id)->where('intake_subject_id', $id)->where('intake_unit_id', '<>', 0)->whereIn('status', [1, 3])->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
        foreach ($units as $key => $value) {
            $units[$key]['intakeUnitTime'] = IntakeUnitTime::where('intake_unit_id', $value->intake_unit_id)->where('status', 1)->get();
        }
        $intakeSubject = IntakeSubject::where('id', $id)->first();
        return view('trainer::trainer.unit.index', compact('units', 'intakeSubject'))->with('no', 1);
    }

    /* Edit Trainer Unit */
    public function editUnit($id)
    {
        $unit = TrainerIntake::findorfail($id);
        activityLog('Trainer', 'Opened ' . $unit->intakeUnit->unit->name . ' edit from web');
        $trainer_id = Auth::guard('trainer')->user()->id;
        return view('trainer::trainer.unit.edit', compact('unit'));
    }

    /* Update Trainer Unit */
    public function updateUnit(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $unit = TrainerIntake::find($id);
        $unit->update($data);
        $intakeUnit = IntakeUnit::where('id', $unit->intake_unit_id)->first();
        $intakeUnit->update($data);
        $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $intakeUnit->id)->get();
        foreach ($studentIntakeUnits as $key => $value) {
            $value->update($data);
        }
        activityLog('Trainer', 'Updated ' . $intakeUnit->unit->name . ' unit');
        $trainer_id = Auth::guard('trainer')->user()->id;
        return redirect()->route('trainer.unit.index', $unit->intake_subject_id)->with('success', 'Unit has been updated successfully');
    }
}
