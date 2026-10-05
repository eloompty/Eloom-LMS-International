<?php

namespace Modules\AgentBranchUser\Http\Controllers\User;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Entities\Company;
use Modules\Condition\Entities\Condition;
use Modules\Credit\Entities\Credit;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentOffer;

class StudentOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent_branch_user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::findorfail($id);
        activityLog('Agent Branch User', 'Opened Student Offer Letter Menu');
        $letters = StudentOffer::where('student_id', $id)->orderby('id', 'desc')->get();
        foreach ($letters as $key => $value) {
            $intake_course_ids = explode(',', $value->intake_course_ids);
            $intake_courses = [];
            foreach ($intake_course_ids as $id) {
                $intake_course = IntakeCourse::find($id);
                $intake_courses[] = $intake_course->course->course_name . '(' . $intake_course->intake->name . ')';
            }
            $letters[$key]['intakes'] = implode(", ", $intake_courses);
        }
        return view('agentbranchuser::user.student.offer.index', compact('letters', 'student'))->with('no', 1);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::findorfail($id);
        activityLog('Agent Branch User', 'Opened Student Offer Letter Create Page');
        $intake_courses = $student->intake;
        $conditions = Condition::where('status', 1)->get();
        $credits = Credit::where('status', 1)->get();
        return view('agentbranchuser::user.student.offer.create', compact('student', 'intake_courses', 'conditions', 'credits'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['student_id'] = $id;
        $data['intake_course_ids'] = (implode(",", $data['intake_course_ids']));
        if (isset($data['condition'])) {
            $condition = Condition::find($data['condition']);
            $data['condition_title'] = $condition->title;
            $data['condition_description'] = $condition->description;
        }
        if (isset($data['credit'])) {
            $credit = Credit::find($data['credit']);
            $data['credit_title'] = $credit->title;
            $data['credit_description'] = $credit->description;
        }
        StudentOffer::create($data);
        activityLog('Agent Branch User', 'Offer letter of ' . userName('Student', $id) . ' generated');
        return redirect()->route('branch-user.student.offer.index', $id)->with('success', 'Offer letter has been created');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $letter = StudentOffer::findorfail($id);
        $intake_courses = $letter->student->intake;
        $intake_course_ids = explode(',', $letter->intake_course_ids);
        $conditions = Condition::where('status', 1)->get();
        $credits = Credit::where('status', 1)->get();
        activityLog('Agent Branch User', 'Offer letter of ' . userName('Student', $id) . ' edit page opened');
        return view('agentbranchuser::user.student.offer.edit', compact('letter', 'intake_courses', 'intake_course_ids', 'conditions', 'credits'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['intake_course_ids'] = (implode(",", $data['intake_course_ids']));
        if (isset($data['condition'])) {
            $condition = Condition::find($data['condition']);
            $data['condition_title'] = $condition->title;
            $data['condition_description'] = $condition->description;
        }
        if (isset($data['credit'])) {
            $credit = Credit::find($data['credit']);
            $data['credit_title'] = $credit->title;
            $data['credit_description'] = $credit->description;
        }
        $letter = StudentOffer::where('id', $id)->first();
        $letter->update($data);
        activityLog('Agent Branch User', 'Offer letter of ' . userName('Student', $letter->student_id) . ' updated');
        return redirect()->route('branch-user.student.offer.index', $letter->student_id)->with('success', 'Offer letter has been updated');
    }

    public function printPDF($id)
    {
        $letter = StudentOffer::findorfail($id);
        $student_course_ids = $intake_course_ids = explode(',', $letter->intake_course_ids);
        $student_courses = StudentIntakeCourse::where('student_id', $letter->student_id)->whereIn('intake_course_id', $student_course_ids)->get();
        $student_fees = StudentIntakeCourseFee::where('student_id', $letter->student_id)->whereIn('intake_course_id', $student_course_ids)->get();
        $company = Company::first();
        // Instantiate and use the dompdf class 
        // $dompdf = new Dompdf(); 
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        // Load HTML content  
        $dompdf->loadHtml(view('student::offer-letter.show', compact('letter', 'company', 'student_courses', 'student_fees')));

        // (Optional) Setup the paper size and orientation  
        $dompdf->setPaper('A4', 'potrait');

        // Render the HTML as PDF  
        $dompdf->render();

        $file_name = userName('Student', $letter->student_id) . '_offer_letter.pdf';
        // Output the generated PDF (1 = download and 0 = preview) 
        $dompdf->stream($file_name, array("Attachment" => 1));
    }
}
