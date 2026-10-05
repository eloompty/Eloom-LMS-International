<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Notification\Entities\Notification;
use Modules\OnlineClass\Entities\OnlineClassGroupStudent;
use Modules\OnlineClass\Entities\OnlineClassGroupTrainer;
use Modules\OnlineClass\Entities\OnlineClassTeam;
use Modules\Student\Entities\StudentDevice;

class OnlineClassGroupTeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerGroup = OnlineClassGroupTrainer::where('trainer_id', $trainer_id)->where('online_class_group_id', $id)->first();
        if ($trainerGroup) {
            $teams = OnlineClassTeam::where('online_class_group_id', $id)->where('intake_unit_id', 0)->get();
            activityLog('Trainer', 'Opened Online Group Classes from web');
            return view('trainer::trainer.onlinegroup.teams.index', compact('id', 'teams'))->with('no', 1);
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
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

        $studentGroup = OnlineClassGroupStudent::where('online_class_group_id', $id)->where('status', 1)->get();
        $attendees = [];
        foreach ($studentGroup as $student_group) {
            $attendees[] = [
                'emailAddress' => [
                    'address' => $student_group->student->email,
                    'name' => userName('Student', $student_group->student_id)
                ],
                'type' => 'required'
            ];
        }

        $trainerGroup = OnlineClassGroupTrainer::where('online_class_group_id', $id)->first();
        $attendees[] = [
            'emailAddress' => [
                'address' => $trainerGroup->trainer->email,
                'name' => userName('Trainer', $trainerGroup->trainer_id)
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
                'created_user_id' => $trainer->id,
                'created_user_type' => 'Trainer',
                'intake_unit_id' => 0,
                'online_class_group_id' => $id
            ]);

            foreach ($studentGroup as $key => $value) {
                $title = 'New Teams Class';
                $body = 'New Teams Class of ' . $data['subject'];
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
                    'sender_id' => $trainer->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'Teams',
                    'link' => $response['onlineMeeting']['joinUrl'],
                ]);
            }
           
            activityLog('Trainer', 'Teams class created');
            return redirect()->route('trainer.onlineclass.group.teams.index', $id)->with('success', 'Teams has been created');
        } else {
            // Handle error response
            // dd($response->body());
            return redirect()->back()->with('failure', 'Teams cannot be created');
        }
    }
}
