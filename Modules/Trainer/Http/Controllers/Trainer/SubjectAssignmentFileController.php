<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Trainer\Entities\TrainerIntake;

class SubjectAssignmentFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Show the form for creating a new assignment file.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $assignment = Assignment::find($id);
        if ($assignment && $assignment->trainer_id == $trainer_id && $assignment->type == 'file') {
            return view('trainer::trainer.subject.assignment.file.create', compact('assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created assignment file in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $assignment = Assignment::where('id', $id)->first();
        $imageName = time() . '.' . request()->path->getClientOriginalExtension();
        request()->path->move(public_path('/images/assignments'), $imageName);
        $data['path'] = 'images/assignments/' . $imageName;
        $assignment->update($data);
        $studentIntakeSubjects = StudentIntakeSubject::where('intake_subject_id', $assignment->intake_subject_id)->where('status', 1)->get();
        foreach ($studentIntakeSubjects as $key => $value) {
            $students = StudentDevice::where('student_id', $value->studentIntakeCourse->student_id)->where('status', 1)->get();
            $title = 'New Assignment';
            $body = 'New Assignment of ' . $assignment->intakeSubject->subject->name;
            if ($students->count() > 0) {
                foreach ($students as $key => $student) {
                    $fcmData = [
                        'registration_ids' => [$student->device_token],
                        "notification" => [
                            "title" => $title,
                            "body" => $body,
                            "click_action" => route('student.assignment.index', $assignment->intake_subject_id),
                        ],
                        "data" => [
                            "is_admin" => false,
                        ],
                    ];
                }
                fcm($fcmData);
            }
            Notification::create([
                'user_type' => 'Student',
                'user_id' => $value->studentIntakeCourse->student_id,
                'sender_type' => 'Trainer',
                'sender_id' => $trainer_id,
                'title' => $title,
                'body'=> $body,
                'type' => 'Assignment',
                'link' => $assignment->intake_subject_id,
            ]);
        }
        activityLog('Trainer', $assignment->name . ' assignment created');
        $trainerIntake = TrainerIntake::where('intake_subject_id', $assignment->intake_subject_id)->first();
        return redirect()->route('trainer.subject.assignment.index', $trainerIntake->id)->with('success', 'Unit assignment created successfully');
    }
}
