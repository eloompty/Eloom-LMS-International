<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Notification\Entities\Notification;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\OnlineClass\Entities\OnlineClassRecording;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeUnitOnlineClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the online class.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'view') == true && $intakeUnit && $check == true) {
            $zooms = OnlineClass::where('intake_unit_id', $id)->get();
            activityLog('Admin', $intakeUnit->unit->name . ' zoom class opened');
            return view('intake::course.unit.onlineclass.index', compact('intakeUnit', 'zooms'))->with('no', 1);
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
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'add') == true && $intakeUnit && $check == true) {
            activityLog('Admin', $intakeUnit->unit->name . ' zoom class create page opened');
            return view('intake::course.unit.onlineclass.create', compact('intakeUnit'));
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
        $data = $request->all();
        $trainerIntake = TrainerIntake::where('intake_unit_id', $id)->first();
        if ($trainerIntake) {
            $data['co-host'] = $trainerIntake->trainer->email;
        }
        $data['start_time'] = $data['date'] . 'T' . $data['time'];
        unset($data['_token'], $data['date'], $data['time']);

        $result = zoom($data);
        // dd($result);
        if ($result == NULL) {
            return redirect()->back()->with('failure', 'Error while creating zoom');
        } else {
            OnlineClass::create([
                'meeting_id' => $result['data']['id'],
                'topic' => $result['data']['topic'],
                'agenda' => $result['data']['agenda'],
                'join_url' => $result['data']['join_url'],
                'created_user_id' => Auth::guard('user')->user()->id,
                'created_user_type' => 'Admin',
                'intake_unit_id' => $id,
            ]);
            $title = 'New Zoom Class';
            $body = 'New Zoom Class of ' . $trainerIntake->intakeUnit->unit->name;

            if ($trainerIntake) {
                $trainerDevices = $trainerIntake->trainer->devices->where('status', 1);
                if ($trainerDevices->count() > 0) {
                    foreach ($trainerDevices as $key => $device) {
                        $fcmData = [
                            'registration_ids' => [$device->device_token],
                            "notification" => [
                                "title" => $title,
                                "body" => $body,
                                "click_action" => route('trainer.onlineclass.index', $trainerIntake->id),
                            ],
                            "data" => [
                                "is_admin" => false,
                            ],
                        ];
                    }
                    fcm($fcmData);
                }
                Notification::create([
                    'user_type' => 'Trainer',
                    'user_id' => $trainerIntake->trainer_id,
                    'sender_type' => 'Admin',
                    'sender_id' =>  Auth::guard('user')->user()->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $result['data']['id'],
                ]);
            }

            $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $id)->where('status', 1)->get();
            foreach ($studentIntakeUnits as $key => $value) {
                $students = $value->studentIntakeCourse->student->devices->where('status', 1);
                if ($students->count() > 0) {
                    foreach ($students as $key => $student) {
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
                }
                Notification::create([
                    'user_type' => 'Student',
                    'user_id' => $value->studentIntakeCourse->student_id,
                    'sender_type' => 'Admin',
                    'sender_id' =>  Auth::guard('user')->user()->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $result['data']['id'],
                ]);
            }
            activityLog('Admin', 'Zoom class created');
            return redirect()->route('admin.intake.unit.onlineclass.index', $id)->with('success', 'Zoom class created successfully');
        }
    }

    /**
     * Show the specified online class.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $online_class = OnlineClass::findorfail($id);
        $check = checkCourseDeliverySite($online_class->intakeUnit->intakeCourse->course_id);
        if ($check == true) {
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
            return view('intake::course.unit.onlineclass.recording.index', compact('recording'));
        } else {
            return abort(404);
        }
    }
}
