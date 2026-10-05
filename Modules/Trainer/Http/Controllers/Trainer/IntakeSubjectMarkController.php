<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeSubjectMark;
use Modules\Marking\Entities\MarkingType;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeSubjectMark;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeSubjectMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the intake subject mark.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Subject') {
            $marks = IntakeSubjectMark::where('intake_subject_id', $trainerIntake->intake_subject_id)->orderBy('id', 'asc')->get();
            activityLog('Trainer', 'Opened intake subject mark of ' . $trainerIntake->intakeSubject->subject->name . ' from web');
            return view('trainer::trainer.subject.marking.index', compact('trainerIntake', 'marks'))->with('no', 1);
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
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Subject') {
            $types = MarkingType::where('status', 1)->orderBy('id', 'asc')->get();
            activityLog('Trainer', 'Opened create page of marking of ' . $trainerIntake->intakeSubject->subject->name . ' from web');
            return view('trainer::trainer.subject.marking.create', compact('trainerIntake', 'types'));
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
        $intakeSubject = IntakeSubject::findorfail($id);
        $data = $request->all();
        foreach ($data['name'] as $key => $value) {
            $intakeSubjectMark = IntakeSubjectMark::create([
                'intake_subject_id' => $id,
                'name' => $value,
                'full_marks' => $data['full_marks'][$key],
                'pass_marks' => $data['pass_marks'][$key],
                'user_type' => 'Trainer',
                'user_id' => Auth::guard('trainer')->user()->id,
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
        activityLog('Trainer', 'Marking for ' . $intakeSubject->subject->name . ' created');
        return redirect()->route('trainer.subject.mark.index', $id)->with('success', 'Intake Marking has been added successfully');
    }

    /**
     * Show the form for editing the specified intake subject mark.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeSubjectMark = IntakeSubjectMark::findorfail($id);
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $intakeSubjectMark->intake_subject_id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Subject') {
            activityLog('Admin', 'Opened Edit Intake Subject Mark');
            return view('trainer::trainer.subject.marking.edit', compact('intakeSubjectMark'));
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
        $intakeSubjectMark = IntakeSubjectMark::findorfail($id);
        $data = $request->all();
        unset($data['_token']);
        $intakeSubjectMark->update($data);
        activityLog('Trainer', 'Intake Subject Marks Updated');
        return redirect()->route('trainer.subject.mark.index', $intakeSubjectMark->intake_subject_id)->with('success', 'Intake Subject Mark updated successfully');
    }

    /**
     * Remove the specified intake subject mark from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $intakeSubjectMark = IntakeSubjectMark::findorfail($id);
        $intakeSubjectMark->update(['status' => 2]);
        activityLog('Trainer', 'Status of intake subject mark id:' . $intakeSubjectMark->id . ' Updated to deleted');
        return redirect()->back()->with('success', 'Intake Subject Mark deleted successfully');
    }

    public function markStudent($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->where('trainer_id', $trainer_id)->first();
        $marking_type = $trainerIntake->intakeCourse->marking_type;
        if ($trainerIntake && $marking_type == 'Subject') {
            $intakeSubjectMark = IntakeSubjectMark::where('intake_subject_id', $id)->first();
            $studentIntakeSubjects = StudentIntakeSubject::where('intake_subject_id', $intakeSubjectMark->intake_subject_id)->get();
            foreach ($studentIntakeSubjects as $studentIntakeSubject) {
                $studentIntakeSubjectIds[] = $studentIntakeSubject->id;
            }
            $studentIntakeSubjectMarks = StudentIntakeSubjectMark::whereIn('student_intake_subject_id', $studentIntakeSubjectIds)->where('name', $intakeSubjectMark->name)->get();
            activityLog('Trainer', 'Opened intake subject mark of ' . $trainerIntake->intakeSubject->subject->name . ' from web');
            return view('trainer::trainer.subject.marking.student.index', compact('trainerIntake', 'studentIntakeSubjectMarks', 'intakeSubjectMark'))->with('no', 1);
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
