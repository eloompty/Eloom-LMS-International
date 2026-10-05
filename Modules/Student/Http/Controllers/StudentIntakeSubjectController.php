<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentIntakeSubject;

class StudentIntakeSubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student intake subject.
     * @return Renderable
     */
    public function index($id)
    {
        $studentIntakeSubject = StudentIntakeSubject::where('student_intake_semester_id', $id)->first();
        if (checkRole('student_intake_course', 'view') == true && $studentIntakeSubject) {
            $studentIntakeSubjects = StudentIntakeSubject::where('student_intake_semester_id', $id)->orderByRaw('ISNULL(sequence), sequence ASC')->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeSubject->studentIntakeCourse->student_id) . ' Intake Subject List');
            return view('student::intake.subject.index', compact('studentIntakeSubjects', 'studentIntakeSubject'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified student intake subject.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $studentIntakeSubject = StudentIntakeSubject::findorfail($id);
        if (checkRole('student_intake_course', 'edit') == true) {
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeSubject->studentIntakeCourse->student_id) . ' Intake Subject Edit Page');
            return view('student::intake.subject.edit', compact('studentIntakeSubject'));
        } else {
            return redirect()->route('admin.student.intake.subject.index', $studentIntakeSubject->studentIntakeCourse->student_id)->with('failure', 'This user does not have permission to edit student intake');
        }
    }

    /**
     * Update the specified student intake subject in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $studentIntakeSubject = StudentIntakeSubject::findorfail($id);
        if (checkRole('student_intake_course', 'edit') == true) {
            $data = $request->all();
            unset($data['_token']);
            $studentIntakeSubject->update($data);
            activityLog('Admin', $studentIntakeSubject->intakeSubject->subject->name. ' updated successfully˝');
            return redirect()->route('admin.student.intake.subject.index', $studentIntakeSubject->student_intake_semester_id)->with('success', 'Intake Subject updated successfully');
        } else {
            return abort(404);
        }
    }

    /**
     * Remove the specified student intake subject from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $studentIntakeSubject = StudentIntakeSubject::findorfail($id);
        if (checkRole('student_intake_course', 'delete') == true) {
            $studentIntakeSubject->update(['status' => 2]);
            return redirect()->back()->with('success', 'Student Intake Subject has been deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete student intake');
        }
    }
}
