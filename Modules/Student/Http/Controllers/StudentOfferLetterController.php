<?php

namespace Modules\Student\Http\Controllers;

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
use Modules\Student\Entities\StudentOfferTemplate;
use PhpOffice\PhpWord\TemplateProcessor;

class StudentOfferLetterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the offer letter.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::find($id);
        if (checkRole('student', 'view') == true) {
            activityLog('Admin', 'Opened Student Offer Letter Menu');
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
            return view('student::offer-letter.index', compact('letters', 'student'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new offer letter.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::find($id);
        if (checkRole('student', 'add') == true) {
            activityLog('Admin', 'Opened Student Offer Letter Create Page');
            $intake_courses = $student->intake;
            $conditions = Condition::where('status', 1)->get();
            $credits = Credit::where('status', 1)->get();
            $templates = StudentOfferTemplate::where('status', 1)->orderBy('name')->get();
            $date = date('Y-m-d');
            return view('student::offer-letter.create', compact('student', 'intake_courses', 'conditions', 'credits', 'templates', 'date'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created offer letter in storage.
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
        activityLog('Admin', 'Offer letter of ' . userName('Student', $id) . ' generated');
        return redirect()->route('admin.student.offer.letter.index', $id)->with('success', 'Offer letter has been created');
    }

    /**
     * Show the specified offer letter.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return $this->renderOfferLetter($id, false);
    }

    /**
     * Render the offer letter preview (browser HTML). When $autoPrint is true the
     * page opens the browser print dialog on load.
     */
    private function renderOfferLetter($id, $autoPrint = false)
    {
        $letter = StudentOffer::findorfail($id);
        $data = $this->buildOfferData($letter);
        $offerCollegeLogo = getSettingValue('offer_college_logo');
        $offerSignature = getSettingValue('offer_signature');
        $data['autoPrint'] = $autoPrint;

        $template = $letter->offer_template_id ? StudentOfferTemplate::find($letter->offer_template_id) : null;
        if ($template) {
            $data['placeholders'] = offerTemplateTokens($letter, $data['company']);
            $data['offerCollegeLogo'] = $offerCollegeLogo;
            $data['offerSignature'] = $offerSignature;
            $data['isPdf'] = false;
            $body = renderOfferTemplate($template, $data);
            return view('student::offer-letter.render', array_merge($data, compact('body')));
        }

        // Legacy fallback for offers created before templates existed.
        return view('student::offer-letter.show', array_merge($data, compact('offerCollegeLogo', 'offerSignature')));
    }

    /**
     * Build the shared data set used to render an offer letter (template or legacy).
     */
    private function buildOfferData(StudentOffer $letter)
    {
        $student_course_ids = explode(',', $letter->intake_course_ids);
        $student_courses = StudentIntakeCourse::where('student_id', $letter->student_id)->whereIn('intake_course_id', $student_course_ids)->get();
        $student_fees = StudentIntakeCourseFee::where('student_id', $letter->student_id)->whereIn('intake_course_id', $student_course_ids)->get();
        $company = Company::first();

        $enrollment = $totatFee = $materialFee = $first_instalments = [];
        $total_first_installments = $cricos = $installments = [];
        foreach ($student_fees as $fee) {
            $enrollment[] = $fee->enrollment_fee;
            $totatFee[] = $fee->fee;
            $materialFee[] = $fee->material_fee;
            $feeInstallments = $fee->fee_installments;
            $firstFeeInstallment = $feeInstallments->first();
            $first_instalments[] = $firstFeeInstallment ? $firstFeeInstallment->amount : 0;
            if ($firstFeeInstallment) {
                $total_first_installments[] = $firstFeeInstallment->studentIntakeCourseFee->intakeCourse->course->course_name . ': $' . $firstFeeInstallment->amount;
                $cricos[] = $firstFeeInstallment->studentIntakeCourseFee->intakeCourse->course->cricos_code;
            }
            $installments[] = $feeInstallments->where('name', '!=', 'First Installment');
        }

        $total_enrollment = array_sum($enrollment);
        $total_fee = array_sum($totatFee);
        $total_material_fee = array_sum($materialFee);
        $first_installment = array_sum($first_instalments);

        // Flat payment plan (all instalments across selected courses, ordered by due date).
        $payment_plan = [];
        foreach ($student_fees as $fee) {
            foreach ($fee->fee_installments as $inst) {
                $payment_plan[] = ['amount' => $inst->amount, 'due_date' => $inst->due_date];
            }
        }
        usort($payment_plan, function ($a, $b) {
            return strcmp((string) $a['due_date'], (string) $b['due_date']);
        });

        // Subjects of each selected intake course, grouped per course.
        $course_subjects = [];
        foreach ($student_courses as $sc) {
            $ic = $sc->intakeCourse;
            $course_subjects[] = [
                'course_name' => $ic ? ($ic->course->course_name . ' (' . $ic->intake->name . ')') : '',
                'subjects'    => \Modules\Intake\Entities\IntakeSubject::with('subject')
                    ->where('intake_course_id', $sc->intake_course_id)
                    ->where('status', 1)
                    ->orderBy('sequence')
                    ->get(),
            ];
        }

        return compact(
            'letter', 'company', 'student_courses', 'student_fees',
            'total_enrollment', 'total_fee', 'total_material_fee', 'first_installment',
            'total_first_installments', 'cricos', 'installments', 'course_subjects', 'payment_plan'
        );
    }

    /**
     * Show the form for editing the specified offer letter.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $letter = StudentOffer::find($id);
        if (checkRole('student', 'edit') == true && $letter) {
            $intake_courses = $letter->student->intake;
            $intake_course_ids = explode(',', $letter->intake_course_ids);
            $conditions = Condition::where('status', 1)->get();
            $credits = Credit::where('status', 1)->get();
            $templates = StudentOfferTemplate::where('status', 1)->orderBy('name')->get();
            activityLog('Admin', 'Offer letter of ' . userName('Student', $letter->student_id) . ' edit page opened');
            return view('student::offer-letter.edit', compact('letter', 'intake_courses', 'intake_course_ids', 'conditions', 'credits', 'templates'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified offer letter in storage.
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
        activityLog('Admin', 'Offer letter of ' . userName('Student', $letter->student_id) . ' updated');
        return redirect()->route('admin.student.offer.letter.index', $letter->student_id)->with('success', 'Offer letter has been updated');
    }

    /**
     * Remove the specified offer letter from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function exportWord($id)
    {
        $letter = StudentOffer::find($id);
        $company = Company::first();
        // $templateProcessor = new TemplateProcessor('offer-templates/test.docx');
        $templateProcessor = new TemplateProcessor('offer-templates/intership_offer.docx');
        $templateProcessor->setValue('student_name', userName('Student', $letter->student_id));
        $templateProcessor->setValue('company_name', $company->company_name);
        $templateProcessor->setValue('date', date('d/m/Y'));
        $templateProcessor->setValue('offer_signed_by_name', getSettingValue('offer_signed_by_name'));
        $templateProcessor->setValue('offer_signed_by_designation', getSettingValue('offer_signed_by_designation'));
        $fileName = $letter->id;
        $templateProcessor->saveAs($fileName . '.docx');
        return response()->download($fileName . '.docx')->deleteFileAfterSend(true);
    }

    public function printPDF($id)
    {
        // Show the same preview as the "Show" view, with the browser print dialog opened on load.
        return $this->renderOfferLetter($id, true);
    }
}
