<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentResubmission;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\StudentDevice;
use Modules\Trainer\Entities\TrainerIntake;

class ResubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id, $trainer_intake_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $trainer_intake_id)->where('trainer_id', $trainer_id)->first();
        $assignment = Assignment::find($id);
        if ($trainerIntake && $assignment) {
            $resubmissions = AssignmentResubmission::where('assignment_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Trainer', 'Opened assignment resubmissions of ' . $assignment->name . ' from web');
            return view('trainer::trainer.unit.assignment.resubmission.index', compact('resubmissions', 'id', 'trainerIntake'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id, $trainer_intake_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $trainer_intake_id)->where('trainer_id', $trainer_id)->first();
        $resubmission = AssignmentResubmission::find($id);
        if ($trainerIntake && $resubmission) {
            activityLog('Trainer', 'Opened edit page of assignment resubmissions of ' . $resubmission->assignment->name . ' from web');
            return view('trainer::trainer.unit.assignment.resubmission.edit', compact('resubmission',  'trainerIntake'))->with('no', 1);
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
    public function update(Request $request, $id, $trainer_intake_id)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['user_id'] = Auth::guard('trainer')->user()->id;
        $data['user_type'] = 'Trainer';
        $resubmission = AssignmentResubmission::where('id', $id)->first();
        if ($data['status'] == 1) {
            $data['approved_date'] = date('Y-m-d');
            $message = 'Resubmission request from ' . userName('Student', $resubmission->student_id) . ' for ' . $resubmission->assignment->name . ' assignment approved';
            $body = 'Resubmission Request has been accepted. Please submit new assignemt';
        } else {
            $message = 'Resubmission request from ' . userName('Student', $resubmission->student_id) . ' for ' . $resubmission->assignment->name . ' assignment rejected';
            $body = 'Resubmission Request has been rejected';
        }
        $resubmission->update($data);
        $title = 'Resubmission Request';
        
        $students = StudentDevice::where('student_id', $resubmission->student_id)->where('status', 1)->get();
        if ($students->count() > 0) {
            foreach ($students as $key => $student) {
                $fcmData = [
                    'registration_ids' => [$student->device_token],
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
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
            'user_id' => $resubmission->student_id,
            'sender_type' => 'Trainer',
            'sender_id' =>  $data['user_id'],
            'title' => $title,
            'body' => $body,
            'type' => 'Resubmission',
            'link' => $resubmission->assignment_id . ',' . $resubmission->assignment->intake_unit_id,
        ]);
        activityLog('Trainer', $message);
        return redirect()->route('trainer.resubmission.index', [$resubmission->assignment_id, $trainer_intake_id])->with('success', $message);
    }
}
