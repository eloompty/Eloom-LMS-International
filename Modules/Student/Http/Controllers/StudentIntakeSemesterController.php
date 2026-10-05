<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentIntakeSemester;

class StudentIntakeSemesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student intake semester.
     * @return Renderable
     */
    public function index($id)
    {
        $studentIntakeSemester = StudentIntakeSemester::where('student_intake_course_id', $id)->first();
        if (checkRole('student_intake_course', 'view') == true && $studentIntakeSemester) {
            $studentIntakeSemesters = StudentIntakeSemester::where('student_intake_course_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeSemester->studentIntakeCourse->student_id) . ' Intake Semester List');
            return view('student::intake.semester.index', compact('studentIntakeSemesters', 'studentIntakeSemester'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified student intake semester.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $studentIntakeSemester = StudentIntakeSemester::findorfail($id);
        if (checkRole('student_intake_course', 'edit') == true) {
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeSemester->studentIntakeCourse->student_id) . ' Intake Semester Edit Page');
            return view('student::intake.semester.edit', compact('studentIntakeSemester'));
        } else {
            return redirect()->route('admin.student.intake.semester.index', $studentIntakeSemester->studentIntakeCourse->student_id)->with('failure', 'This user does not have permission to edit student intake');
        }
    }

    /**
     * Update the specified student intake semester in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $studentIntakeSemester = StudentIntakeSemester::findorfail($id);
        if (checkRole('student_intake_course', 'edit') == true) {
            $data = $request->all();
            unset($data['_token']);
            $studentIntakeSemester->update($data);
            activityLog('Admin', $studentIntakeSemester->intakeSemester->semester->name. ' updated successfully˝');
            return redirect()->route('admin.student.intake.semester.index', $studentIntakeSemester->student_intake_course_id)->with('success', 'Intake Semester updated successfully');
        } else {
            return abort(404);
        }
    }

    /**
     * Remove the specified student intake semester from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $studentIntakeSemester = StudentIntakeSemester::findorfail($id);
        if (checkRole('student_intake_course', 'delete') == true) {
            $studentIntakeSemester->update(['status' => 2]);
            return redirect()->back()->with('success', 'Student Intake Semester has been deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete student intake');
        }
    }
}
