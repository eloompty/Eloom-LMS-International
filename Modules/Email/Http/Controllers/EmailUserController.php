<?php

namespace Modules\Email\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Agent\Entities\Agent;
use Modules\Company\Entities\Company;
use Modules\Email\Entities\Email;
use Modules\Email\Entities\EmailTemplate;
use Modules\Email\Entities\EmailUser;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\Trainer;
use Modules\User\Entities\User;

class EmailUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('email_user', 'view') == true) {
            $emails = EmailUser::orderBy('id', 'desc')->get();
            $company = Company::first();
            activityLog('Admin', 'Opened Sent Email List');
            return view('email::user.index', compact('emails', 'company'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('email_user', 'add') == true) {
            $emails = Email::where('status', 1)->get();
            $templates = EmailTemplate::where('status', 1)->get();
            $company = Company::first();
            $intakes = Intake::where('status', 1)->pluck('name', 'id');
            $trainers = Trainer::where('status', 1)->get();
            $agents = Agent::where('status', 1)->get();
            $staffs = User::where('status', 1)->get();
            activityLog('Admin', 'Opened Send Email Create Page');
            return view('email::user.create', compact('emails', 'templates', 'company', 'intakes', 'trainers', 'agents', 'staffs'));
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
        $data = $request->all();
        $company = Company::first();
        if ($data['email_regards_name'] == NULL) {
            $regards_name = $company->company_ceo;
        } else {
            $regards_name = $data['email_regards_name'];
        }
        if ($data['email_regards_position'] == NULL) {
            $regards_position = fullAddress('company', $company->id);
        } else {
            $regards_position = $data['email_regards_position'];
        }
        $sender_id = Auth::guard('user')->user()->id;
        $sender = 'Admin';
        if (in_array('student', $data['user'])) {
            if (isset($data['all_student'])) {
                $students = Student::where('status', 1)->get();
            } else {
                if (isset($data['intake_id']) && isset($data['intake_course_id'])) {
                    $ids = StudentIntakeCourse::where('intake_course_id', $data['intake_course_id'])->select('student_id')->get();
                    $students = Student::where('status', 1)->whereIn('id', $ids)->get();
                } elseif (isset($data['intake_id']) && !isset($data['intake_course_id'])) {
                    $intakes = IntakeCourse::where('intake_id', $data['intake_id'])->select('id')->get();
                    $ids = StudentIntakeCourse::whereIn('intake_course_id', $intakes)->select('student_id')->get();
                    $students = Student::where('status', 1)->whereIn('id', $ids)->get();
                } else {
                    $students = Student::where('status', 1)->get();
                }
            }
            foreach ($students as $student) {
                $data['user_id'] = $student->id;
                $data['user_type'] = 'Student';
                $data['sender_id'] = $sender_id;
                $data['sender_type'] = $sender;
                $email = EmailUser::create($data);
                $studentEmail = EmailUser::find($email->id);
                $email_data = [
                    'name' =>  userName('Student', $student->id),
                    'address' => fullAddress('Student', $student->id),
                    'subject' => $data['email_subject'],
                    'content' => $data['email_content'],
                    'company_ceo' => $regards_name,
                    'company_address' => $regards_position,
                    'company_phone' => $company->phone
                ];
                sendEmail($studentEmail->email_id, userName('Student', $student->id), $student->email, $email_data, 'Student');
            }
        }
        if (in_array('trainer', $data['user'])) {
            if (isset($data['all_trainer'])) {
                $trainers = Trainer::where('status', 1)->get();
            } elseif (isset($data['trainer_id'])) {
                $trainers = Trainer::where('status', 1)->whereIn('id', $data['trainer_id'])->get();
            } else {
                $trainers = Trainer::where('status', 1)->get();
            }
            foreach ($trainers as $trainer) {
                $data['user_id'] = $trainer->id;
                $data['user_type'] = 'Trainer';
                $data['sender_id'] = $sender_id;
                $data['sender_type'] = $sender;
                $email = EmailUser::create($data);
                $trainerEmail = EmailUser::find($email->id);
                $email_data = [
                    'name' =>  userName('Trainer', $trainer->id),
                    'address' => fullAddress('Trainer', $trainer->id),
                    'subject' => $data['email_subject'],
                    'content' => $data['email_content'],
                    'company_ceo' => $regards_name,
                    'company_address' => $regards_position,
                    'company_phone' => $company->phone
                ];
                sendEmail($trainerEmail->email_id, userName('Trainer', $trainer->id), $trainer->email, $email_data, 'Trainer');
            }
        }
        if (in_array('agent', $data['user'])) {
            if (isset($data['all_agent'])) {
                $agents = Agent::where('status', 1)->get();
            } elseif (isset($data['agent_id'])) {
                $agents = Agent::where('status', 1)->whereIn('id', $data['agent_id'])->get();
            } else {
                $agents = Agent::where('status', 1)->get();
            }
            foreach ($agents as $agent) {
                $data['user_id'] = $agent->id;
                $data['user_type'] = 'Agent';
                $data['sender_id'] = $sender_id;
                $data['sender_type'] = $sender;
                $email = EmailUser::create($data);
                $agentEmail = EmailUser::find($email->id);
                $email_data = [
                    'name' =>  $agent->company_name,
                    'address' => $agent->address,
                    'subject' => $data['email_subject'],
                    'content' => $data['email_content'],
                    'company_ceo' => $regards_name,
                    'company_address' => $regards_position,
                    'company_phone' => $company->phone
                ];
                sendEmail($agentEmail->email_id, $agent->company_name, $agent->email, $email_data, 'Agent');
            }
        }
        if (in_array('staff', $data['user'])) {
           if (isset($data['all_staff'])) {
                $staffs = User::where('status', 1)->get();
            } elseif (isset($data['staff_id'])) {
                $staffs = User::where('status', 1)->whereIn('id', $data['staff_id'])->get();
            } else {
                $staffs = User::where('status', 1)->get();
            }
            foreach ($staffs as $staff) {
                $data['user_id'] = $staff->id;
                $data['user_type'] = 'User';
                $data['sender_id'] = $sender_id;
                $data['sender_type'] = $sender;
                $email = EmailUser::create($data);
                $staffEmail = EmailUser::find($email->id);
                $email_data = [
                    'name' =>  userName('User', $staff->id),
                    'address' => fullAddress('company', $company->id),
                    'subject' => $staffEmail->emailTemplate->subject,
                    'content' => $staffEmail->emailTemplate->content,
                    'company_ceo' => $regards_name,
                    'company_address' => $regards_position,
                    'company_phone' => $company->phone
                ];
                sendEmail($staffEmail->email_id, userName('User', $staff->id), $staff->email, $email_data, 'User');
            }
        }
        return redirect()->route('admin.email.user.index')->with('success', 'Email has been sent');
    }
}
