<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\Entities\Notification;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class OnlineClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the online class.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $zooms = OnlineClass::where('intake_unit_id', $trainerIntake->intake_unit_id)->where('status', 1)->get();
            activityLog('Trainer', 'Opened zooms of ' . $trainerIntake->intakeUnit->unit->name . ' from web');
            return view('trainer::trainer.unit.onlineclass.index', compact('trainerIntake', 'zooms'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new online class.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            activityLog('Trainer', 'Opened create zoom class of ' . $trainerIntake->intakeUnit->unit->name . ' from web');
            return view('trainer::trainer.unit.onlineclass.create', compact('trainerIntake'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created online class in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $trainerIntake = TrainerIntake::find($id);
        $data = $request->all();
        $data['co-host'] = $trainerIntake->trainer->email;
        $data['start_time'] = $data['date'] . 'T' . $data['time'];
        unset($data['_token'], $data['date'], $data['time']);
        $result = zoom($data);
        if ($result == NULL) {
            return redirect()->back()->with('failure', 'Error while creating zoom');
        } else {
            OnlineClass::create([
                'meeting_id' => $result['data']['id'],
                'topic' => $result['data']['topic'],
                'agenda' => $result['data']['agenda'],
                'join_url' => $result['data']['join_url'],
                'created_user_id' => $trainerIntake->trainer_id,
                'created_user_type' => 'Trainer',
                'intake_unit_id' => $trainerIntake->intake_unit_id,
            ]);
            $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $trainerIntake->intake_unit_id)->where('status', 1)->get();
            foreach ($studentIntakeUnits as $key => $value) {
                $students = $value->studentIntakeCourse->student->devices->where('status', 1);
                foreach ($students as $key => $student) {
                    $title = 'New Zoom Class';
                    $body = 'New Zoom Class of ' . $trainerIntake->intakeUnit->unit->name;
                    $fcmData = [
                        'registration_ids' => [$student->device_token],
                        "notification" => [
                            "title" => $title,
                            "body" => $body,
                            "click_action" => route('student.onlineclass.index', $value->id),
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
                    'sender_id' =>  Auth::guard('trainer')->user()->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $result['data']['id'],
                ]);
            }
            activityLog('Trainer', 'Zoom class created');
            return redirect()->route('trainer.onlineclass.index', $id)->with('success', 'Zoom class created');
        }
    }
}
