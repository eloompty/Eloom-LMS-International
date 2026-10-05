<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\Entities\Notification;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\OnlineClass\Entities\OnlineClassGroupStudent;
use Modules\OnlineClass\Entities\OnlineClassGroupTrainer;
use Modules\Student\Entities\StudentDevice;

class OnlineClassGroupClassController extends Controller
{
    /**
     * Display a listing of the online group class.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerGroup = OnlineClassGroupTrainer::where('trainer_id', $trainer_id)->where('online_class_group_id', $id)->first();
        if ($trainerGroup) {
            $zooms = OnlineClass::where('online_class_group_id', $id)->where('intake_unit_id', 0)->get();
            activityLog('Trainer', 'Opened Online Group Classes from web');
            return view('trainer::trainer.onlinegroup.onlineclass.index', compact('id', 'zooms'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for creating a new online group class.
     * @return Renderable
     */
    public function create($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerGroup = OnlineClassGroupTrainer::where('trainer_id', $trainer_id)->where('online_class_group_id', $id)->first();
        if ($trainerGroup) {
            activityLog('Trainer', 'Opened Create Online Group Classes from web');
            return view('trainer::trainer.onlinegroup.onlineclass.create', compact('id'));
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created online group class in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $trainer = Auth::guard('trainer')->user();
        $data = $request->all();
        $data['co-host'] = $trainer->email;
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
                'created_user_id' => $trainer->id,
                'created_user_type' => 'Trainer',
                'intake_unit_id' => 0,
                'online_class_group_id' => $id
            ]);
            $studentGroup = OnlineClassGroupStudent::where('online_class_group_id', $id)->where('status', 1)->get();
            foreach ($studentGroup as $key => $value) {
                $title = 'New Zoom Class';
                $body = 'New Zoom Class of ' . $data['topic'];
                $students = StudentDevice::where('student_id', $value->student_id)->where('status', 1)->get();
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
                    'user_id' => $value->id,
                    'sender_type' => 'Trainer',
                    'sender_id' =>  $trainer->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $result['data']['id'],
                ]);
            }
            activityLog('Trainer', 'Online Group Class created from web');
            return redirect()->route('trainer.onlineclass.group.class.index', $id)->with('success', 'Zoom group class has been created');
        }
    }
}
