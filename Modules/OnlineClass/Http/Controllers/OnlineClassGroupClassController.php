<?php

namespace Modules\OnlineClass\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\Entities\Notification;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\OnlineClass\Entities\OnlineClassGroupStudent;
use Modules\OnlineClass\Entities\OnlineClassGroupTrainer;
use Modules\OnlineClass\Entities\OnlineClassRecording;
use Modules\Student\Entities\StudentDevice;

class OnlineClassGroupClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the zoom class.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('online_class_group', 'view') == true) {
            $zooms = OnlineClass::where('intake_unit_id', 0)->where('online_class_group_id', $id)->get();
            activityLog('Admin', 'Group Online Class opened from web');
            return view('onlineclass::group.onlineclass.index', compact('id', 'zooms'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new zoom class.
     * @return Renderable
     */
    public function create($id)
    {
        if (checkRole('online_class_group', 'add') == true) {
            activityLog('Admin', 'Group Online Class create page opened from web');
            return view('onlineclass::group.onlineclass.create', compact('id'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created zoom class in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $trainerGroup = OnlineClassGroupTrainer::where('online_class_group_id', $id)->first();
        $data['co-host'] = $trainerGroup->trainer->email;
        $data['start_time'] = $data['date'] . 'T' . $data['time'];
        unset($data['_token'], $data['date'], $data['time']);
        $result = zoom($data);
        if ($result == NULL) {
            return redirect()->route('admin.onlineclass.group.class.index', $id)->with('failure', 'Error while creating zoom');
        } elseif ($result['success'] == false) {
            $message = $result['data']['message'];
            return redirect()->route('admin.onlineclass.group.class.index', $id)->with('failure', $message);
        } else {
            OnlineClass::create([
                'meeting_id' => $result['data']['id'],
                'topic' => $result['data']['topic'],
                'agenda' => $result['data']['agenda'],
                'join_url' => $result['data']['join_url'],
                'created_user_id' => Auth::guard('user')->user()->id,
                'created_user_type' => 'Admin',
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
                    'sender_id' => Auth::guard('user')->user()->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $result['data']['id'],
                ]);
            }
            activityLog('Admin', 'Online Group Class created from web');
            return redirect()->route('admin.onlineclass.group.class.index', $id)->with('success', 'Zoom group class has been created');
        }
    }

    /**
     * Show the recording of zoom class.
     * @param int $id
     * @return Renderable
     */
    public function recording($id)
    {
        if (checkRole('online_class_group', 'view') == true) {
            activityLog('Admin', 'Opened Online Class Group Recording Menu');
            $online_class = OnlineClass::find($id);
            $recording = OnlineClassRecording::where('online_class_id', $online_class->id)->first();
            if (!$recording) {
                $zoom = getZoomRecording($online_class->meeting_id);
                if ($zoom == false) {
                    return redirect()->back()->with('failure', 'Recording Not Found');
                } else {
                    $new_recording = OnlineClassRecording::create([
                        'online_class_id' => $id,
                        'share_url' => $zoom->share_url,
                        'file_type' => $zoom->recording_files[0]->file_type,
                        'file_size' => $zoom->recording_files[0]->file_size,
                        'play_url' => $zoom->recording_files[0]->play_url,
                        'download_url' => $zoom->recording_files[0]->download_url,
                        'password' => $zoom->password,
                    ]);
                    $recording = OnlineClassRecording::find($new_recording->id);
                }
            }
            return view('onlineclass::group.recording.index', compact('recording'));
        } else {
            return abort(404);
        }
    }
}
