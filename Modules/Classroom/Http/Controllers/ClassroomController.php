<?php

namespace Modules\Classroom\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Classroom\Entities\Classroom;
use Modules\Classroom\Entities\ClassroomIntakeUnit;
use Modules\Classroom\Entities\ClassroomStudent;
use Modules\Classroom\Entities\ClassroomTrainer;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Trainer\Entities\Trainer;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the classroom.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('classroom', 'view') == true) {
            activityLog('Admin', 'Opened Classroom Menu');
            $classrooms = Classroom::orderBy('id', 'desc')->get();
            return view('classroom::index', compact('classrooms'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new classroom.
     * @return Renderable
     */
    public function create()
    {

        if (checkRole('classroom', 'add') == true) {
            activityLog('Admin', 'Opened Classroom Create Page');
            $trainers = Trainer::where('status', 1)->get();
            $intakes = Intake::where('status', 1)->get();
            $ids = getDeliverySiteIds();
            foreach ($intakes as $key => $value) {
                $intakes[$key]['courses'] = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
                    ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
                    ->where(function ($query) use ($ids) {
                        $query->whereNull('course_delivery_sites.course_id')
                            ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
                    })
                    ->select('intake_courses.*')
                    ->where('courses.status', 1)
                    ->where('intake_courses.intake_id', $value->id)->orderBy('intake_courses.id', 'desc')->get();
            }
            return view('classroom::create', compact('trainers', 'intakes'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created classroom in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $classroom = Classroom::create($data);
        ClassroomTrainer::create([
            'classroom_id' => $classroom->id,
            'trainer_id' => $data['trainer_id'],
        ]);
        foreach ($data['student_id'] as $key => $value) {
            $student = ClassroomStudent::where('classroom_id', $classroom->id)->where('student_id', $value)->first();
            if (!$student) {
                ClassroomStudent::create([
                    'classroom_id' => $classroom->id,
                    'student_id' => $value
                ]);
            }
        }
        foreach ($data['intake_unit_id'] as $intake_unit) {
            $intakeUnit = ClassroomIntakeUnit::where('classroom_id', $classroom->id)->where('intake_unit_id', $intake_unit)->first();
            if (!$intakeUnit) {
                ClassroomIntakeUnit::create([
                    'classroom_id' => $classroom->id,
                    'intake_unit_id' => $intake_unit
                ]);
            }
        }
        activityLog('Admin', 'Classroom has been created');
        return redirect()->route('admin.classroom.index')->with('success', 'Classroom has been created');
    }

    /**
     * Show the form for editing the specified classroom.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $classroom = Classroom::findorfail($id);
        if (checkRole('classroom', 'edit') == true && $classroom) {
            activityLog('Admin', 'Opened Online Class Group Menu');
            $trainers = Trainer::where('status', 1)->get();
            $intakes = Intake::where('status', 1)->get();
            foreach ($intakes as $key => $value) {
                $courses = $value->course;
                foreach ($courses as $num => $course) {
                    $students = $course->studentIntake;
                    foreach ($students as $index => $student) {
                        $studentGroup = ClassroomStudent::where('classroom_id', $id)->where('student_id', $student->student_id)->first();
                        if ($studentGroup) {
                            $intakes[$key]['course'][$num]['studentIntake'][$index]['intake_course_student_id'] = $student->student_id;
                        } else {
                            $intakes[$key]['course'][$num]['studentIntake'][$index]['intake_course_student_id'] = 0;
                        }
                    }

                    $intake_untis = $course->intakeUnit;
                    foreach ($intake_untis as $number => $intake_unit) {
                        $intakeUnitGroup = ClassroomIntakeUnit::where('classroom_id', $id)->where('intake_unit_id', $intake_unit->id)->first();
                        if ($intakeUnitGroup) {
                            $intakes[$key]['course'][$num]['intakeUnit'][$number]['intake_unit_id'] = $intake_unit->id;
                        } else {
                            $intakes[$key]['course'][$num]['intakeUnit'][$number]['intake_unit_id'] = 0;
                        }
                    }
                }
            }
            activityLog('Admin', 'Opened classroom edit page');
            return view('classroom::edit', compact('classroom', 'trainers', 'intakes'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified classroom in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $classroom = Classroom::where('id', $id)->first();
        $classroom->update([
            'name' => $data['name'],
            'status' => $data['status']
        ]);
        ClassroomTrainer::where('classroom_id', $id)->update([
            'trainer_id' => $data['trainer_id'],
            'status' => $data['status']
        ]);
        $studentGroups = ClassroomStudent::where('classroom_id', $id)->get();
        foreach ($studentGroups as $key => $value) {
            $student_group[] = $value->student_id;
        }
        $deleteStudent = array_diff($student_group, $data['student_id']);
        foreach ($deleteStudent as $key => $value) {
            $studentGroup = ClassroomStudent::where('classroom_id', $id)->where('student_id', $value)->delete();
        }
        foreach ($data['student_id'] as $key => $value) {
            $studentGroup = ClassroomStudent::where('classroom_id', $id)->where('student_id', $value)->first();
            if ($studentGroup) {
                $studentGroup->update(['status' => $data['status']]);
            } else {
                ClassroomStudent::create([
                    'classroom_id' => $id,
                    'student_id' => $value,
                    'status' => $data['status']
                ]);
            }
        }
        activityLog('Admin', 'Classroom has been updated');
        return redirect()->route('admin.classroom.index')->with('success', 'Classroom has been updated');
    }
}
