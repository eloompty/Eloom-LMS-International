<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Notification\Entities\Notification;
use Modules\OnlineClass\Entities\OnlineClass;
use Modules\OnlineClass\Entities\OnlineClassTeam;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeUnitOnlineClassTeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeUnit = IntakeUnit::findorfail($id);
        $check = checkCourseDeliverySite($intakeUnit->intakeCourse->course_id);
        if (checkRole('intake_course', 'view') == true && $intakeUnit && $check == true) {
            $teams = OnlineClassTeam::where('intake_unit_id', $id)->get();
            activityLog('Admin', $intakeUnit->unit->name . ' team class opened');
            return view('intake::course.unit.teams.index', compact('intakeUnit', 'teams'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $accessToken = session('microsoft_access_token');
        $id = session('id');
        $type = session('type');

        $data = $request->all();

        $start_date_time = $data['start_date'] . 'T' . $data['start_time'];
        $end_date_time = $data['end_date'] . 'T' . $data['end_time'];

        $timezone = getSettingValue('microsoft_teams_timezone');

        $studentIntakeUnits = StudentIntakeUnit::where('intake_unit_id', $id)->where('status', 1)->get();
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

        $trainerIntake = TrainerIntake::where('intake_unit_id', $id)->where('status', 1)->first();
        if ($trainerIntake) {
            $attendees[] = [
                'emailAddress' => [
                    'address' => $trainerIntake->trainer->email,
                    'name' => userName('Trainer', $trainerIntake->trainer_id)
                ],
                'type' => 'required'
            ];
        }

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
                'created_user_id' => Auth::guard('user')->user()->id,
                'created_user_type' => 'Admin',
                'intake_unit_id' => $id,
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
                    'sender_type' => 'Admin',
                    'sender_id' =>  Auth::guard('user')->user()->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $response['onlineMeeting']['joinUrl'],
                ]);
            }
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
                    'sender_type' => 'Admin',
                    'sender_id' =>  Auth::guard('user')->user()->id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'OnlineClass',
                    'link' => $response['onlineMeeting']['joinUrl'],
                ]);
            }
            activityLog('Admin', 'Teams class created');
            return redirect()->route('admin.intake.unit.team.index', $id)->with('success', 'Teams has been created');
        } else {
            // Handle error response
            // dd($response->body());
            return redirect()->back()->with('failure', 'Teams cannot be created');
        }
    }
}
