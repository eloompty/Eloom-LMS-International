<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Email\Entities\Email;
use Modules\Email\Entities\EmailTemplate;
use Modules\Email\Entities\EmailUser;
use Modules\Student\Entities\Student;

class StudentEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student email.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student', 'view') == true) {
            $emails = EmailUser::where('user_id', $id)->where('user_type', 'Student')->orderBy('id', 'desc')->get();
            $company = Company::first();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Email List');
            return view('student::email.index', compact('student', 'emails', 'company'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student email.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student', 'add') == true) {
            $emails = Email::where('status', 1)->get();
            $templates = EmailTemplate::where('status', 1)->get();
            $company = Company::first();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Create page');
            return view('student::email.create', compact('student', 'emails', 'templates', 'company'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created student email in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['user_id'] = $id;
        $data['user_type'] = 'Student';
        $data['sender_id'] = Auth::guard('user')->user()->id;
        $data['sender_type'] = 'Admin';
        $email = EmailUser::create($data);
        $studentEmail = EmailUser::find($email->id);
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
        $email_data = [
            'name' =>  userName('Student', $id),
            'address' => fullAddress('Student', $id),
            'subject' => $data['email_subject'],
            'content' => $data['email_content'],
            'company_ceo' => $regards_name,
            'company_address' => $regards_position,
            'company_phone' => $company->phone
        ];
        sendEmail($studentEmail->email_id, userName('Student', $id), $studentEmail->student->email, $email_data, 'Student');
        activityLog('Admin', 'Email with ' . $studentEmail->email->name . ' and template with ' . $studentEmail->emailTemplate->name . ' is sent to ' . userName('Student', $id));
        return redirect()->route('admin.student.email.index', $id)->with('Email has been sent to student');
    }

    /* Get Email Templates */
    public function getEmailTemplates(Request $request)
    {
        $templates = EmailTemplate::where('id', $request->id)->first();
        return response()->json($templates);
    }
}
