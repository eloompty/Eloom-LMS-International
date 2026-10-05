<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeUnit;

class StudentIntakeUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student Intake Unit.
     * @return Renderable
     */
    public function index($id)
    {
        $studentIntakeSubject = StudentIntakeSubject::find($id);
        if (checkRole('student_intake_unit', 'view') == true && $studentIntakeSubject) {
            $studentIntakeUnits = StudentIntakeUnit::where('student_intake_subject_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            $studentIntakeUnit = $studentIntakeUnits->first();
            $studentIntakeContext = $studentIntakeUnit ?: $studentIntakeSubject;
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeSubject->studentIntakeCourse->student_id) . ' Intake Unit List');
            return view('student::intake.unit.index', compact('studentIntakeUnits', 'studentIntakeUnit', 'studentIntakeSubject', 'studentIntakeContext'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified student Intake Unit.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($id);
        if ($studentIntakeUnit) {
            if (checkRole('student_intake_unit', 'edit') == true) {
                activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeUnit->studentIntakeCourse->student_id) . ' Intake Assign Edit Page Opened');
                return view('student::intake.unit.edit', compact('studentIntakeUnit'));
            } else {
                return redirect()->route('admin.student.intake.unit.index', $studentIntakeUnit->student_intake_subject_id)->with('failure', 'This user does not have permission to edit student intake');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified student Intake Unit in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $studentInakeUnit = StudentIntakeUnit::find($id);
        $studentInakeUnit->update($data);
        activityLog('Admin', ' Student Intake Unit Updated');
        return redirect()->route('admin.student.intake.unit.index', $studentInakeUnit->student_intake_subject_id)->with('success', 'Student Intake Unit updated successfully');
    }

    public function bulkupdate(Request $request, $id)
    {
        $studentIntakeUnit = StudentIntakeUnit::where('student_intake_course_id', $id)->first();
        if (checkRole('student_intake_unit', 'edit') == true && $studentIntakeUnit) {
            $data = $request->all();
            unset($data['_token']);
            foreach ($data as $key => $value) {
                $unit = StudentIntakeUnit::where('id', $value['id'])->first();
                $unit->update($value);
            }
            return redirect()->back()->with('success', 'Bulk update successful');
        } else {
            return abort(404);
        }
    }

    /**
     * Remove the specified student Intake Unit from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $studentIntakeUnit = StudentIntakeUnit::find($id);
        if (checkRole('student_intake_course', 'delete') == true) {
            $studentIntakeUnit->update(['status' => 2]);
            return redirect()->back()->with('success', 'Student Intake Unit has been deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete student intake');
        }
    }
}
