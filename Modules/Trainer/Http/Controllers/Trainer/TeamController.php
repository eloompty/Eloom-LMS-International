<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Notification\Entities\Notification;
use Modules\OnlineClass\Entities\OnlineClassTeam;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the teams.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $teams = OnlineClassTeam::where('intake_unit_id', $trainerIntake->intake_unit_id)->where('status', 1)->get();
            activityLog('Trainer', 'Opened teams of ' . $trainerIntake->intakeUnit->unit->name . ' from web');
            return view('trainer::trainer.unit.teams.index', compact('trainerIntake', 'teams'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created teams in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $trainer = Auth::guard('trainer')->user();
        $accessToken = session('microsoft_access_token');
        $id = session('id');

        $data = $request->all();

        $start_date_time = $data['start_date'] . 'T' . $data['start_time'];
        $end_date_time = $data['end_date'] . 'T' . $data['end_time'];

        $timezone = getSettingValue('microsoft_teams_timezone');

        $trainerIntake = TrainerIntake::find($id);

        $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $trainerIntake->intake_unit_id)->where('status', 1)->get();
        $attendees = [];
        foreach ($studentIntakeUnits as $student_group) {
            $attendees[] = [
                'emailAddress' => [
                    'address' => $student_group->studentIntakeCourse->student->email,
                    'name' => userName('Student', $student_group->studentIntakeCourse->student_id)
                ],
                'type' => 'required'
            ];
        }

        $attendees[] = [
            'emailAddress' => [
                'address' => $trainerIntake->trainer->email,
                'name' => userName('Trainer', $trainerIntake->trainer_id)
            ],
            'type' => 'required'
        ];

        $response = Http::withToken($accessToken)
            ->post('https://graph.microsoft.com/v1.0/me/events', [
                'subject' => $data['subject'],
                'body' => [
                    'contentType' => 'HTML',
                    'content' => $data['content'],
                ],
                'start' => [
                    'dateTime' => $start_date_time,
                    'timeZone' => $timezone
                ],
                'end' => [
                    'dateTime' => $end_date_time,
                    'timeZone' => $timezone
                ],
                'location' => [
                    'displayName' => $data['subject']
                ],
                'attendees' => $attendees,
                'allowNewTimeProposals' => true,
                'isOnlineMeeting' => true,
                'onlineMeetingProvider' => 'teamsForBusiness'
            ]);

        if ($response->successful()) {
            // Handle successful response

            // dd($response->json());

            OnlineClassTeam::create([
                'meeting_id' => $response['id'],
                'subject' => $data['subject'],
                'content' => $data['content'],
                'join_url' => $response['onlineMeeting']['joinUrl'],
                'start_date' => $data['start_date'],
                'start_time' => $data['start_time'],
                'end_date' => $data['end_date'],
                'end_time' => $data['end_time'],
                'created_user_id' => $trainerIntake->trainer_id,
                'created_user_type' => 'Trainer',
                'intake_unit_id' => $trainerIntake->intake_unit_id,
                'online_class_group_id' => 0
            ]);
            $trainerIntake = TrainerIntake::where('intake_unit_id', $id)->first();
            $title = 'New Teams Class';
            $body = 'New Teams Class of ' . $trainerIntake->intakeUnit->unit->name;

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
                    'sender_type' => 'Trainer',
                    'sender_id' =>  $trainerIntake->trainer_id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $response['onlineMeeting']['joinUrl'],
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
                    'sender_type' => 'Trainer',
                    'sender_id' =>  Auth::guard('trainer')->user()->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $response['onlineMeeting']['joinUrl'],
                ]);
            }
            activityLog('Trainer', 'Teams class created');
            return redirect()->route('trainer.team.indexx', $id)->with('success', 'Teams has been created');
        } else {
            // Handle error response
            // dd($response->body());
            return redirect()->back()->with('failure', 'Teams cannot be created');
        }
    }
}
