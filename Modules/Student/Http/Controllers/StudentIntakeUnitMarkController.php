<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentIntakeUnitMark;

class StudentIntakeUnitMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $studentIntakeUnit = StudentIntakeUnit::findorfail($id);
        $marking_type = $studentIntakeUnit->studentIntakeCourse->intakeCourse->marking_type;
        if (checkRole('student_intake_unit', 'view') == true && $marking_type == 'Unit') {
            $marks = StudentIntakeUnitMark::where('student_intake_unit_id', $id)->where('status', 1)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeUnit->studentIntakeCourse->student_id) . ' Intake Unit Mark List');
            return view('student::intake.unit.marking.index', compact('studentIntakeUnit', 'marks'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $subjectIntakeUnit = StudentIntakeUnit::findorfail($id);
        $data = $request->all();
        $student_id = $subjectIntakeUnit->studentIntakeCourse->student_id;
        foreach ($data['obtain_marks'] as $key => $value) {
            StudentIntakeUnitMark::where('id', $key)->where('student_intake_unit_id', $id)->update(['obtain_marks' => $value]);
        }
        activityLog('Admin', 'Updated ' . userName('Student', $student_id) . ' Subject Marking from web');
        return redirect()->back()->with('success', 'Marks has been updated');
    }
}
