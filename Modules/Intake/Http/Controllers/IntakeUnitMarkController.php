<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Intake\Entities\IntakeUnitMark;
use Modules\Marking\Entities\MarkingType;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentIntakeUnitMark;

class IntakeUnitMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake unit mark.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        $marking_type = $intakeUnit->intakeCourse->marking_type;
        if (checkRole('intake_course', 'view') == true && $check == true && $marking_type == 'Unit') {
            $marks = IntakeUnitMark::where('intake_unit_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Marks of ' . $intakeUnit->unit->name);
            return view('intake::course.unit.marking.index', compact('intakeUnit', 'marks'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new intake unit mark.
     * @return Renderable
     */
    public function create($id)
    {
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        $marking_type = $intakeUnit->intakeCourse->marking_type;
        if (checkRole('intake_course', 'add') == true && $check == true && $marking_type == 'Unit') {
            $types = MarkingType::where('status', 1)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened Create Intake Marking');
            return view('intake::course.unit.marking.create', compact('intakeUnit', 'types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created intake unit mark in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        foreach ($data['name'] as $key => $value) {
            $intakeUnitMark = IntakeUnitMark::create([
                'intake_unit_id' => $id,
                'name' => $value,
                'full_marks' => $data['full_marks'][$key],
                'pass_marks' => $data['pass_marks'][$key],
                'user_type' => 'Admin',
                'user_id' => Auth::guard('user')->user()->id,
                'status' => $data['status']
            ]);
            $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $id)->get();
            foreach ($studentIntakeUnits as $unit) {
                StudentIntakeUnitMark::create([
                    'student_intake_unit_id' => $unit->id,
                    'name' => $intakeUnitMark->name,
                    'full_marks' => $intakeUnitMark->full_marks,
                    'pass_marks' => $intakeUnitMark->pass_marks,
                    'user_type' => $intakeUnitMark->user_type,
                    'user_id' => $intakeUnitMark->user_id,
                    'status' =>  $intakeUnitMark->status
                ]);
            }
        }
        return redirect()->route('admin.intake.unit.marking.index', $id)->with('success', 'Marking created successfully');
    }

    /**
     * Show the form for editing the specified intake unit mark.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeUnitMark = IntakeUnitMark::findorfail($id);
        $marking_type = $intakeUnitMark->intakeUnit->intakeCourse->marking_type;
        $check = checkCourseDeliverySite($intakeUnitMark->intakeUnit->intakeCourse->course_id && $marking_type == 'Unit');
        if (checkRole('intake_course', 'edit') == true && $check == true && $marking_type == 'Unit') {
            activityLog('Admin', 'Opened Edit Intake Unit Mark');
            return view('intake::course.unit.marking.edit', compact('intakeUnitMark'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake unit mark in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $intakeUnitMark = IntakeUnitMark::findorfail($id);
        $data = $request->all();
        unset($data['_token']);
        $intakeUnitMark->update($data);
        activityLog('Admin', 'Intake Marking Updated');
        return redirect()->route('admin.intake.unit.marking.index', $intakeUnitMark->intake_unit_id)->with('success', 'Intake marking updated successfully');
    }

    /**
     * Remove the specified intake unit mark from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course', 'delete') == true) {
            $intakeUnitMark = IntakeUnitMark::findorfail($id);
            $intakeUnitMark->update(['status' => 2]);
            activityLog('Admin', $intakeUnitMark->id . ' Updated status to deleted');
            return redirect()->back()->with('success', 'Intake Course deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to add intake unit mark');
        }
    }

    public function markStudent($id)
    {
        if (checkRole('intake_course', 'view') == true) {
            $intakeUnitMark = IntakeUnitMark::findorfail($id);
            $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $intakeUnitMark->intake_unit_id)->get();
            foreach ($studentIntakeUnits as $studentIntakeUnit) {
                $studentIntakeUnitIds[] = $studentIntakeUnit->id;
            }
            $studentIntakeUnitMarks = StudentIntakeUnitMark::whereIn('student_intake_unit_id', $studentIntakeUnitIds)->where('name', $intakeUnitMark->name)->get();
            activityLog('Admin', $intakeUnitMark->name . ' opened Student Marking');
            return view('intake::course.unit.marking.student.index', compact('studentIntakeUnitMarks', 'intakeUnitMark'));
        } else {
            return abort(404);
        }
    }

    public function markStudentUpdate(Request $request)
    {
        $data = $request->all();
        foreach ($data['obtain_marks'] as $key => $value) {
            StudentIntakeUnitMark::where('id', $key)->update(['obtain_marks' => $value]);
        }
        return redirect()->back()->with('success', 'Marks has been updated successfully');
    }
}
