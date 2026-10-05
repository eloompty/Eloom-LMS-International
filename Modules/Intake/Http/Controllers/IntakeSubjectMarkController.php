<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeSubjectMark;
use Modules\Marking\Entities\MarkingType;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeSubjectMark;

class IntakeSubjectMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake subject mark.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $check = checkCourseDeliverySite($intakeSubject->intakeCourse->course_id);
        $marking_type = $intakeSubject->intakeCourse->marking_type;
        if (checkRole('intake_course', 'view') == true && $check == true && $marking_type == 'Subject') {
            $marks = IntakeSubjectMark::where('intake_subject_id', $id)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Marks of ' . $intakeSubject->subject->name);
            return view('intake::course.subject.marking.index', compact('intakeSubject', 'marks'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new intake subject mark.
     * @return Renderable
     */
    public function create($id)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $check = checkCourseDeliverySite($intakeSubject->intakeCourse->course_id);
        $marking_type = $intakeSubject->intakeCourse->marking_type;
        if (checkRole('intake_course', 'add') == true && $check == true && $marking_type == 'Subject') {
            $types = MarkingType::where('status', 1)->orderBy('id', 'asc')->get();
            activityLog('Admin', 'Opened Create Intake Marking');
            return view('intake::course.subject.marking.create', compact('intakeSubject', 'types'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created intake subject mark in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        foreach ($data['name'] as $key => $value) {
            $intakeSubjectMark = IntakeSubjectMark::create([
                'intake_subject_id' => $id,
                'name' => $value,
                'full_marks' => $data['full_marks'][$key],
                'pass_marks' => $data['pass_marks'][$key],
                'user_type' => 'Admin',
                'user_id' => Auth::guard('user')->user()->id,
                'status' => $data['status']
            ]);
            $studentIntakeSubjects = StudentIntakeSubject::where('intake_subject_id', $id)->get();
            foreach ($studentIntakeSubjects as $subject) {
                StudentIntakeSubjectMark::create([
                    'student_intake_subject_id' => $subject->id,
                    'name' => $intakeSubjectMark->name,
                    'full_marks' => $intakeSubjectMark->full_marks,
                    'pass_marks' => $intakeSubjectMark->pass_marks,
                    'user_type' => $intakeSubjectMark->user_type,
                    'user_id' => $intakeSubjectMark->user_id,
                    'status' =>  $intakeSubjectMark->status
                ]);
            }
        }
        return redirect()->route('admin.intake.subject.marking.index', $id)->with('success', 'Marking created successfully');
    }

    /**
     * Show the form for editing the specified intake subject mark.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeSubjectMark = IntakeSubjectMark::findorfail($id);
        $marking_type = $intakeSubjectMark->intakeSubject->intakeCourse->marking_type;
        $check = checkCourseDeliverySite($intakeSubjectMark->intakeSubject->intakeCourse->course_id && $marking_type == 'Subject');
        if (checkRole('intake_course', 'edit') == true && $check == true) {
            activityLog('Admin', 'Opened Edit Intake Subject Mark');
            return view('intake::course.subject.marking.edit', compact('intakeSubjectMark'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake subject mark in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $intakeSubject = IntakeSubjectMark::findorfail($id);
        $data = $request->all();
        unset($data['_token']);
        $intakeSubject->update($data);
        activityLog('Admin', 'Intake Marking Updated');
        return redirect()->route('admin.intake.subject.marking.index', $id)->with('success', 'Intake marking updated successfully');
    }

    /**
     * Remove the specified intake subject mark from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('intake_course', 'delete') == true) {
            $intakeSubjectMark = IntakeSubjectMark::findorfail($id);
            $intakeSubjectMark->update(['status' => 2]);
            activityLog('Admin', $intakeSubjectMark->id . ' Updated status to deleted');
            return redirect()->back()->with('success', 'Intake Course deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to add intake subject mark');
        }
    }
    
    public function markStudent($id)
    {
        if (checkRole('intake_course', 'view') == true) {
            $intakeSubjectMark = IntakeSubjectMark::findorfail($id);
            $studentIntakeSubjects = StudentIntakeSubject::where('intake_subject_id', $intakeSubjectMark->intake_subject_id)->get();
            foreach ($studentIntakeSubjects as $studentIntakeSubject) {
                $studentIntakeSubjectIds[] = $studentIntakeSubject->id;
            }
            $studentIntakeSubjectMarks = StudentIntakeSubjectMark::whereIn('student_intake_subject_id', $studentIntakeSubjectIds)->where('name', $intakeSubjectMark->name)->get();
            activityLog('Admin', $intakeSubjectMark->name . ' opened Student Marking');
            return view('intake::course.subject.marking.student.index', compact('studentIntakeSubjectMarks', 'intakeSubjectMark'));
        } else {
            return abort(404);
        }
    }

    public function markStudentUpdate(Request $request)
    {
        $data = $request->all();
        foreach ($data['obtain_marks'] as $key => $value) {
            StudentIntakeSubjectMark::where('id', $key)->update(['obtain_marks' => $value]);
        }
        return redirect()->back()->with('success', 'Marks has been updated successfully');
    }
}
