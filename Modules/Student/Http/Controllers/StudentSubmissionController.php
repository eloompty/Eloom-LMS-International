<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Assignment\Entities\AssignmentAnswer;
use Modules\Assignment\Entities\AssignmentGrade;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeUnit;

class StudentSubmissionController extends Controller
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
        $studentIntakeUnit = StudentIntakeUnit::find($id);
        if (checkRole('student_intake_unit', 'view') == true && $studentIntakeUnit) {
            $submissions = AssignmentSubmission::join('assignments', 'assignments.id', '=', 'assignment_submissions.assignment_id')
                ->where('intake_unit_id', $studentIntakeUnit->intake_unit_id)
                ->where('student_id', $studentIntakeUnit->studentIntakeCourse->student_id)
                ->select('assignment_submissions.*')
                ->get();
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeUnit->studentIntakeCourse->student_id) . ' Assignment Submission');
            return view('student::intake.unit.submission.index', compact('studentIntakeUnit', 'submissions'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $submission = AssignmentSubmission::find($id);
        if (checkRole('assignment_submission', 'edit') == true && $submission) {
            $studentIntakeUnit = StudentIntakeUnit::join('student_intake_courses', 'student_intake_units.student_intake_course_id', '=', 'student_intake_courses.id')
                ->select('student_intake_units.*')
                ->where('student_intake_courses.student_id', $submission->student_id)
                ->where('student_intake_units.intake_unit_id', $submission->assignment->intake_unit_id)->first();
            if ($studentIntakeUnit->is_complete == 1) {
                return redirect()->back()->with('failure', 'Student unit is already closed, submission cannot be edited.');
            } else {
                $grades = AssignmentGrade::where('status', 1)->pluck('name', 'id');
                activityLog('Admin', $submission->assignment->name . ' assignment submission opened');
                return view('student::intake.unit.submission.edit', compact('submission', 'grades', 'studentIntakeUnit'));
            }
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
        $data = $request->all();
        $submission = AssignmentSubmission::where('id', $id)->first();
        $submission->update($data);
        $intakeCourseId = $submission->assignment->intakeUnit->intake_course_id;
        $studnentIntakeCourse = StudentIntakeCourse::where('student_id', $submission->student_id)->where('intake_course_id', $intakeCourseId)->first();
        $studentIntakeUnit = StudentIntakeUnit::where('student_intake_course_id', $studnentIntakeCourse->id)->where('intake_unit_id', $submission->assignment->intake_unit_id)->first();
        activityLog('Admin', $submission->assignment->name . ' assignment submission graded');
        return redirect()->route('admin.student.intake.unit.submission.index', $studentIntakeUnit->id)->with('success', 'Assignment graded successfully');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function mcq($id)
    {
        $submission = AssignmentSubmission::find($id);
        if (checkRole('student_intake_unit', 'view') == true && $submission) {
            $answers = AssignmentAnswer::where('assignment_submission_id', $id)->get();
            $studentIntakeUnit = StudentIntakeUnit::where('intake_unit_id', $submission->assignment->intake_unit_id)->first();
            activityLog('Admin', $submission->assignment->name . ' assignment mcq submission opened');
            return view('student::intake.unit.submission.mcq.index', compact('submission', 'answers', 'studentIntakeUnit'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function question($id)
    {
        $submission = AssignmentSubmission::find($id);
        if (checkRole('student_intake_unit', 'view') == true && $submission) {
            $answers = AssignmentAnswer::where('assignment_submission_id', $id)->get();
            $studentIntakeUnit = StudentIntakeUnit::where('intake_unit_id', $submission->assignment->intake_unit_id)->first();
            activityLog('Admin', $submission->assignment->name . ' assignment question submission opened');
            return view('student::intake.unit.submission.question.index', compact('submission', 'answers', 'studentIntakeUnit'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
