<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentQuestion;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class AssignmentQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Show the form for creating a new assignment question.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $assignment = Assignment::find($id);
        if ($assignment && $assignment->trainer_id == $trainer_id && $assignment->type == 'question') {
            return view('trainer::trainer.unit.assignment.question.create', compact('assignment'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created assignment question in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $assignment = Assignment::where('id', $id)->first();
        $questions = $request->question;
        foreach ($questions as $key => $value) {
            if ($value != NULL) {
                AssignmentQuestion::create([
                    'assignment_id' => $assignment->id,
                    'question' => $value,
                ]);
            }
        }
        $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $assignment->intake_unit_id)->where('status', 1)->get();
        foreach ($studentIntakeUnits as $key => $value) {
            $students = StudentDevice::where('student_id', $value->studentIntakeCourse->student_id)->where('status', 1)->get();
            foreach ($students as $key => $student) {
                $title = 'New Assignment';
                $body = 'New Assignment of ' . $assignment->intakeUnit->unit->name;
                $fcmData = [
                    'registration_ids' => [$student->device_token],
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                        "click_action" => route('student.assignment.index', $assignment->intake_unit_id),
                    ],
                    "data" => [
                        "is_admin" => false,
                    ],
                ];
            }
            fcm($fcmData);
            Notification::create([
                'user_type' => 'Student',
                'user_id' => $value->studentIntakeCourse->student_id,
                'sender_type' => 'Trainer',
                'sender_id' => $trainer_id,
                'title' => $title,
                'body'=> $body,
                'type' => 'Assignment',
                'link' => $assignment->intake_unit_id,
            ]);
        }
        activityLog('Trainer', $assignment->name . ' assignment created');
        return redirect()->route('trainer.assignment.question.show', $assignment->id)->with('success', 'Unit assignment created successfully');
    }

    /**
     * Show the specified assignment question.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $assignment = Assignment::find($id);
        if ($assignment && $assignment->trainer_id == $trainer_id && $assignment->type == 'question') {
            $questions = AssignmentQuestion::where('assignment_id', $id)->orderBy('id', 'asc')->get();
            $trainerIntake = TrainerIntake::where('trainer_id', $trainer_id)->where('intake_unit_id', $assignment->intake_unit_id)->first();
            return view('trainer::trainer.unit.assignment..question.show', compact('assignment', 'questions', 'trainerIntake'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified assignment question in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $assignmentQuestion = AssignmentQuestion::where('id', $id)->first();
        $assignmentQuestion->update($data);
        activityLog('Trainer', '"' . $assignmentQuestion->assignment->name . '"' . ' assignment with question of ' . '"' . $assignmentQuestion->question . '"' . ' updated');
        return redirect()->back()->with('success', 'Question updated successfully');
    }
}
