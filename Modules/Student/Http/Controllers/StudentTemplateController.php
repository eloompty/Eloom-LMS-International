<?php

namespace Modules\Student\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Entities\Company;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentTemplate;
use Modules\Student\Entities\StudentTemplateData;
use Modules\Template\Entities\Template;
use Modules\Template\Entities\TemplateData;

class StudentTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student template.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::find($id);
        if (checkRole('student', 'view') == true && $student) {
            $templates = StudentTemplate::where('student_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Template List');
            return view('student::template.index', compact('student', 'templates'));
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to view student template');
        }
    }

    /**
     * Show the form for creating a new student template.
     * @return Renderable
     */
    public function create($type, $id)
    {
        $student = Student::find($id);
        if (checkRole('student', 'view') == true && $student) {
            if ($type == 'progression') {
                $name = 'Progression Letter';
            } else if ($type == 'enrollment') {
                $name = 'Letter of Enrollment';
            } else if ($type == 'completion') {
                $name = 'Completion Letter';
            } else if ($type == 'term_break') {
                $name = 'Term Break Letter';
            } else if ($type == 'vp_request') {
                $name = 'VP Request Letter';
            } else if ($type == 'leave') {
                $name = 'Leave Approval Letter';
            } else if ($type == 'vp') {
                $name = 'VP Letter';
            } else if ($type == 'statement') {
                $name = 'Statement of Receipt';
            }
            $template = Template::where('name', $name)->first();
            $intake_courses = $student->intake;
            return view('student::template.create', compact('student', 'template', 'intake_courses'));
        }
    }

    /**
     * Store a newly created student template in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['student_id'] = $id;
        $data['template_content'] = '';
        if (!isset($data['logo'])) {
            $data['logo'] = NULL;
        }
        if (!isset($data['regards_signature'])) {
            $data['regards_signature'] = NULL;
        }
        $student_template = StudentTemplate::create($data);
        $template = Template::where('name', $data['template_name'])->first();
        $template_name = str_replace(' ', '_', $data['template_name']);
        unset($data['_token'], $data['student_id'], $data['template_content'], $data['intake_course_id'], $data['template_name'], $data['status']);
        foreach ($data as $key => $value) {
            if ($value != NULL) {
                if ($key == 'logo' || $key == 'regards_signature') {
                    $imageName = time() . '.' . request()->$key->getClientOriginalExtension();
                    request()->$key->move(public_path('images/student/template/' . $template_name . '/' . $id . '/'  . $key), $imageName);
                    $value = 'images/student/template/' . $template_name . '/' . $id . '/'  . $key . '/' . $imageName;
                }
            } else {
                if ($key == 'logo') {
                    $template_data = TemplateData::where('template_id', $template->id)->where('key', 'logo')->first();
                    $value = $template_data->value;
                }
                if ($key == 'regards_signature') {
                    $template_data = TemplateData::where('template_id', $template->id)->where('key', 'regards_signature')->first();
                    $value = $template_data->value;
                }
            }
            $data['student_template_id'] = $student_template->id;
            $data['key'] = $key;
            $data['value'] = $value;
            StudentTemplateData::create($data);
        }
        return redirect()->route('admin.student.template.index', $id)->with('success', 'Student Template has been created');
    }

    /**
     * Show the specified student template.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (checkRole('student', 'view') == true) {
            $student_template = StudentTemplate::findorfail($id);
            $company = Company::first();
            $company_name = $company->company_name;
            $logo = asset(getStudentTemplateDataValue($student_template->id, 'logo'));
            $date = dateFormat(getStudentTemplateDataValue($student_template->id, 'date'));
            $student_name = userName('Student', $student_template->student_id);
            $student_id = $student_template->student->id_no;
            $student_dob = dateFormat($student_template->student->date_of_birth);
            $student_intake_course = StudentIntakeCourse::where('intake_course_id', $student_template->intake_course_id)->where('student_id', $student_template->student_id)->first();
            $course_name = $student_template->intakeCourse->course->course_code . ' ' . $student_template->intakeCourse->course->course_name;
            if ($student_template->template_name == 'Progression Letter') {
                $completion_percent = getStudentTemplateDataValue($student_template->id, 'completion_percent');
                $content = getStudentTemplateDataValue($student_template->id, 'content');
                $search_name = '{{ $student_name }}';
                $name_content = str_replace($search_name, $student_name, $content);
                $search_completion_percent = '{{ $completion_percent }}';
                $final_content = str_replace($search_completion_percent, $completion_percent, $name_content);
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                return view('template::progression', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'completion_percent',
                    'final_content',
                    'course_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                ));
            } elseif ($student_template->template_name == 'Letter of Enrollment') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                return view('template::enrollment', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'company_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                ));
            } elseif ($student_template->template_name == 'Completion Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                return view('template::completion', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'company_name',
                    'course_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                ));
            } elseif ($student_template->template_name == 'Term Break Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $term_break_from = dateFormat(getStudentTemplateDataValue($student_template->id, 'term_break_from'));
                $term_break_to = dateFormat(getStudentTemplateDataValue($student_template->id, 'term_break_to'));
                    return view('template::term_break', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'company_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer',
                    'term_break_from',
                    'term_break_to'
                ));
            } elseif ($student_template->template_name == 'VP Request Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $work_hours = getStudentTemplateDataValue($student_template->id, 'work_hours');
                return view('template::vp_request', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'company_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer',
                    'work_hours'
                ));
            } elseif ($student_template->template_name == 'Leave Approval Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $leave_from = dateFormat(getStudentTemplateDataValue($student_template->id, 'leave_from'));
                $leave_to = dateFormat(getStudentTemplateDataValue($student_template->id, 'leave_to'));
                return view('template::leave', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer',
                    'leave_from',
                    'leave_to'
                ));
            } elseif ($student_template->template_name == 'VP Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                return view('template::vp', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'company_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                ));
            } elseif ($student_template->template_name == 'Statement of Receipt') {
                $receipt_no = getStudentTemplateDataValue($student_template->id, 'receipt_no');
                $account_name = getStudentTemplateDataValue($student_template->id, 'account_name');
                $bsb = getStudentTemplateDataValue($student_template->id, 'bsb');
                $account_number = getStudentTemplateDataValue($student_template->id, 'account_number');
                $bank_name = getStudentTemplateDataValue($student_template->id, 'bank_name');
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $intake = $student_template->intakeCourse->intake->name;
                $fee_course_id = $student_template->intakeCourse->course->course_code;
                $fee_course_name = $student_template->intakeCourse->course->course_name;
                $student_intake_course_fee = StudentIntakeCourseFee::where('intake_course_id', $student_template->intake_course_id)->where('student_id', $student_template->student_id)->first();
                $total_fee = $student_intake_course_fee->fee;
                $payments = $student_intake_course_fee->installments;
                $installment_payments = [];
                foreach ($payments as $payment) {
                    if ($payment->studentIntakeCourseFeePayment != NULL) {
                        $installment_payments[] = [
                            'payment_name' => $payment->name,
                            'total_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                            'paid_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        ];
                    } else {
                        $installment_payments[] = [
                            'payment_name' => $payment->name,
                            'total_amount' => $payment->amount,
                            'paid_amount' => 0,
                        ];
                    }
                }
                $total_received = array_sum(array_column($installment_payments, 'paid_amount'));
                $balance = $total_fee - $total_received;

                return view('template::statement_receipt', compact(
                    'receipt_no',
                    'account_name',
                    'bsb',
                    'account_number',
                    'bank_name',
                    'intake',
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'fee_course_id',
                    'fee_course_name',
                    'total_fee',
                    'installment_payments',
                    'total_received',
                    'balance',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                ));
            }
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to view student template');
        }
    }

    /**
     * Show the form for editing the specified student template.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $student_template = StudentTemplate::findorfail($id);
        if (checkRole('student', 'edit') == true) {
            return view('student::template.edit', compact('student_template'));
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to edit student template');
        }
    }

    /**
     * Update the specified student template in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $student_template = StudentTemplate::findorfail($id);
        $student_template->fill($data)->save();
        foreach ($data as $key => $value) {
            if ($value != NULL) {
                if ($key == 'logo' || $key == 'regards_signature') {
                    $imageName = time() . '.' . request()->$key->getClientOriginalExtension();
                    request()->$key->move(public_path('images/student/template/'  . $student_template->student_id . '/'  . $key), $imageName);
                    $value = 'images/student/template/'  . $student_template->student_id . '/'  . $key . '/' . $imageName;
                }
                StudentTemplateData::where('key', $key)->update(['value' => $value]);
            }
        }
        return redirect()->route('admin.student.template.index', $student_template->student_id)->with('success', 'Student Template has been updated');
    }

    /**
     * Remove the specified student template from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('student', 'view') == true) {
            StudentTemplate::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Student template has been deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete student template');
        }
    }

    function print($id)
    {
        if (checkRole('student', 'view') == true) {
            $student_template = StudentTemplate::findorfail($id);

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $company = Company::first();
            $company_name = $company->company_name;

            $logo = asset(getStudentTemplateDataValue($student_template->id, 'logo'));
            $date = dateFormat(getStudentTemplateDataValue($student_template->id, 'date'));
            $student_name = userName('Student', $student_template->student_id);
            $student_id = $student_template->student->id_no;
            $student_dob = dateFormat($student_template->student->date_of_birth);
            $student_intake_course = StudentIntakeCourse::where('intake_course_id', $student_template->intake_course_id)->where('student_id', $student_template->student_id)->first();
            $course_name = $student_template->intakeCourse->course->course_code . ' ' . $student_template->intakeCourse->course->course_name;
            if ($student_template->template_name == 'Progression Letter') {
                $completion_percent = getStudentTemplateDataValue($student_template->id, 'completion_percent');
                $content = getStudentTemplateDataValue($student_template->id, 'content');
                $search_name = '{{ $student_name }}';
                $name_content = str_replace($search_name, $student_name, $content);
                $search_completion_percent = '{{ $completion_percent }}';
                $final_content = str_replace($search_completion_percent, $completion_percent, $name_content);
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');

                // Load HTML content  
                $dompdf->loadHtml(view('template::progression', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'completion_percent',
                    'final_content',
                    'course_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                )));

                $file_name = $student_name . '_progession_letter.pdf';
            } elseif ($student_template->template_name == 'Letter of Enrollment') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $dompdf->loadHtml(view('template::enrollment', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'company_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                )));

                $file_name = $student_name . '_letter_of_enrollment.pdf';
            } elseif ($student_template->template_name == 'Completion Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $dompdf->loadHtml(view('template::completion', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'company_name',
                    'course_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                )));
                $file_name = $student_name . '_completion_letter.pdf';
            } elseif ($student_template->template_name == 'Term Break Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $term_break_from = getStudentTemplateDataValue($student_template->id, 'term_break_from');
                $term_break_to = getStudentTemplateDataValue($student_template->id, 'term_break_to');
                $dompdf->loadHtml(view('template::term_break', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'company_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer',
                    'term_break_from',
                    'term_break_to'
                )));

                $file_name = $student_name . '_term_break_letter.pdf';
            } elseif ($student_template->template_name == 'VP Request Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $work_hours = getStudentTemplateDataValue($student_template->id, 'work_hours');
                $dompdf->loadHtml(view('template::vp_request', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'company_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer',
                    'work_hours'
                )));

                $file_name = $student_name . '_vp_request_letter.pdf';
            } elseif ($student_template->template_name == 'Leave Approval Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $leave_from = getStudentTemplateDataValue($student_template->id, 'leave_from');
                $leave_to = getStudentTemplateDataValue($student_template->id, 'leave_to');
                $dompdf->loadHtml(view('template::leave', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer',
                    'leave_from',
                    'leave_to'
                )));

                $file_name = $student_name . '_leave_approval_letter.pdf';
            } elseif ($student_template->template_name == 'VP Letter') {
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $dompdf->loadHtml(view('template::vp', compact(
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'course_name',
                    'company_name',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                )));

                $file_name = $student_name . '_vp_letter.pdf';
            } elseif ($student_template->template_name == 'Statement of Receipt') {
                $receipt_no = getStudentTemplateDataValue($student_template->id, 'receipt_no');
                $account_name = getStudentTemplateDataValue($student_template->id, 'account_name');
                $bsb = getStudentTemplateDataValue($student_template->id, 'bsb');
                $account_number = getStudentTemplateDataValue($student_template->id, 'account_number');
                $bank_name = getStudentTemplateDataValue($student_template->id, 'bank_name');
                $regards_signature = asset(getStudentTemplateDataValue($student_template->id, 'regards_signature'));
                $regards_name = getStudentTemplateDataValue($student_template->id, 'regards_name');
                $regards_position = getStudentTemplateDataValue($student_template->id, 'regards_position');
                $regards_college_name = getStudentTemplateDataValue($student_template->id, 'regards_college_name');
                $regards_email = getStudentTemplateDataValue($student_template->id, 'regards_email');
                $regards_phone = getStudentTemplateDataValue($student_template->id, 'regards_phone');
                $footer = getStudentTemplateDataValue($student_template->id, 'footer');
                $intake = $student_template->intakeCourse->intake->name;
                $fee_course_id = $student_template->intakeCourse->course->course_code;
                $fee_course_name = $student_template->intakeCourse->course->course_name;
                $student_intake_course_fee = StudentIntakeCourseFee::where('intake_course_id', $student_template->intake_course_id)->where('student_id', $student_template->student_id)->first();
                $total_fee = $student_intake_course_fee->fee;
                $payments = $student_intake_course_fee->installments;
                $installment_payments = [];
                foreach ($payments as $payment) {
                    if ($payment->studentIntakeCourseFeePayment != NULL) {
                        $installment_payments[] = [
                            'payment_name' => $payment->name,
                            'total_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                            'paid_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        ];
                    } else {
                        $installment_payments[] = [
                            'payment_name' => $payment->name,
                            'total_amount' => $payment->amount,
                            'paid_amount' => 0,
                        ];
                    }
                }
                $total_received = array_sum(array_column($installment_payments, 'paid_amount'));
                $balance = $total_fee - $total_received;
                $dompdf->loadHtml(view('template::statement_receipt', compact(
                    'receipt_no',
                    'account_name',
                    'bsb',
                    'account_number',
                    'bank_name',
                    'intake',
                    'logo',
                    'date',
                    'student_name',
                    'student_id',
                    'student_dob',
                    'fee_course_id',
                    'fee_course_name',
                    'total_fee',
                    'installment_payments',
                    'total_received',
                    'balance',
                    'student_intake_course',
                    'regards_signature',
                    'regards_name',
                    'regards_position',
                    'regards_college_name',
                    'regards_email',
                    'regards_phone',
                    'footer'
                )));
                $file_name = $student_name . '_statement_of_receipt.pdf';
            }

            // (Optional) Setup the paper size and orientation  
            $dompdf->setPaper('A4', 'potrait');

            // Render the HTML as PDF  
            $dompdf->render();


            // Output the generated PDF (1 = download and 0 = preview) 
            $dompdf->stream($file_name, array("Attachment" => 1));
        } else {
            return redirect()->route('admin.student.index')->with('failure', 'This user does not have permission to print student template');
        }
    }
}
