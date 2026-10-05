<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeSubjectMark;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeSubjectMark;

class StudentIntakeSubjectMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student intake subject mark.
     * @return Renderable
     */
    public function index($id)
    {
        $studentIntakeSubject = StudentIntakeSubject::findorfail($id);
        $marking_type = $studentIntakeSubject->studentIntakeCourse->intakeCourse->marking_type;
        if (checkRole('student_intake_course', 'view') == true && $marking_type == 'Subject') {
            $this->syncMissingSubjectMarks($studentIntakeSubject);
            $marks = StudentIntakeSubjectMark::where('student_intake_subject_id', $id)->where('status', 1)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeSubject->studentIntakeCourse->student_id) . ' Intake Subject Mark List');
            return view('student::intake.subject.marking.index', compact('studentIntakeSubject', 'marks'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified student intake subject mark in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $subjectIntakeSubject = StudentIntakeSubject::findorfail($id);
        $data = $request->all();
        $student_id = $subjectIntakeSubject->studentIntakeCourse->student_id;
        foreach ($data['obtain_marks'] as $key => $value) {
            StudentIntakeSubjectMark::where('id', $key)->where('student_intake_subject_id', $id)->update(['obtain_marks' => $value]);
        }
        activityLog('Admin', 'Updated ' . userName('Student', $student_id) . ' Subject Marking from web');
        return redirect()->back()->with('success', 'Marks has been updated');
    }

    private function syncMissingSubjectMarks(StudentIntakeSubject $studentIntakeSubject)
    {
        $intakeSubjectMarks = IntakeSubjectMark::where('intake_subject_id', $studentIntakeSubject->intake_subject_id)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($intakeSubjectMarks as $intakeSubjectMark) {
            $exists = StudentIntakeSubjectMark::where('student_intake_subject_id', $studentIntakeSubject->id)
                ->where('name', $intakeSubjectMark->name)
                ->exists();

            if ($exists) {
                continue;
            }

            StudentIntakeSubjectMark::create([
                'student_intake_subject_id' => $studentIntakeSubject->id,
                'name' => $intakeSubjectMark->name,
                'full_marks' => $intakeSubjectMark->full_marks,
                'pass_marks' => $intakeSubjectMark->pass_marks,
                'user_type' => $intakeSubjectMark->user_type,
                'user_id' => $intakeSubjectMark->user_id,
                'status' => $intakeSubjectMark->status
            ]);
        }
    }
}
