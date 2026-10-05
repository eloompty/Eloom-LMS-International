<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\UnitFee;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentIntakeUnitFee;

class StudentIntakeUnitFeeController extends Controller
{
    /**
     * Display a listing of the student intake unit fee.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student_intake_course_fee', 'view') == true && $student) {
            $fees = StudentIntakeUnitFee::where('student_id', $id)->get();
            activityLog('Admin', 'Opened ' . userName('Student', $id) . ' Intake Unit Fee Menu');
            return view('student::unit-fee.index', compact('student', 'fees'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student intake unit fee.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student_intake_course_fee', 'add') == true && $student) {
            $studentIntakeCourses = StudentIntakeCourse::where('student_id', $id)->where('status', 1)->get();
            activityLog('Admin', 'Opened ' . userName('Student', $id) . ' Intake Unit Fee Menu');
            return view('student::unit-fee.create', compact('student', 'studentIntakeCourses'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created student intake unit fee in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $student_intake_course = StudentIntakeCourse::findorfail($data['course_id']);
        foreach ($data['unit_fees'] as $key => $value) {
            $fee = UnitFee::find($value);
            $student_intake_unit = StudentIntakeUnit::where('intake_unit_id', $key)->where('student_intake_course_id', $data['course_id'])->first();
            $student_fee = StudentIntakeUnitFee::where('student_id', $student_intake_course->student_id)->where('intake_unit_id', $key)->first();
            if (!$student_fee) {
                StudentIntakeUnitFee::create([
                    'student_id' => $student_intake_course->student_id,
                    'intake_unit_id' => $key,
                    'name' => $fee->name,
                    'fee' => $fee->fee,
                    'due_date' => $student_intake_unit->ending_date,
                ]);
            }
        }
        activityLog('Admin', 'Unit fee has been added');
        return redirect()->route('admin.student.fee.unit.index', $student_intake_course->student_id)->with('success', 'Unit Fees have been added');
    }

    /**
     * Show the form for editing the specified student intake unit fee.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $fee = StudentIntakeUnitFee::findorfail($id);
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            activityLog('Admin', 'Opened Unit Fee Page');
            return view('student::unit-fee.edit', compact('fee'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified student intake unit fee in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $fee = StudentIntakeUnitFee::findorfail($id);
        unset($data['_token']);
        $fee->update($data);
        return redirect()->route('admin.student.fee.unit.index', $fee->student_id)->with('success', 'Unit Fees have been updated');
    }

    /**
     * Remove the specified student intake unit fee from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function getStudentIntakeUnitList(Request $request)
    {
        $course = StudentIntakeCourse::find($request->course_id);
        $intake_units = $course->studentIntakeUnit;
        foreach ($intake_units as $key => $value) {
            $unit_fees = UnitFee::where('unit_id', $value->intakeUnit->unit_id)->get();
            $fees = [];
            if (count($unit_fees) > 0) {
                foreach ($unit_fees as $unit_fee) {
                    $fees[] = [
                        'id' => $unit_fee->id,
                        'unit_id' => $unit_fee->unit_id,
                        'name' => $unit_fee->name,
                        'fee' => $unit_fee->fee
                    ];
                }
                $units[] = [
                    'intake_unit_id' => $value->intake_unit_id,
                    'name' => $value->intakeUnit->unit->name,
                    'fees' => $fees
                ];
            }
        }
        return response()->json($units);
    }
}
