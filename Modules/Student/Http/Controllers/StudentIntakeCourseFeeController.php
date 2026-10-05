<?php

namespace Modules\Student\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Entities\Company;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;
use Modules\Intake\Entities\IntakeCourseFeeType;
use Modules\Payment\Entities\Payment;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentAgent;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Student\Entities\StudentIntakeCourseFeeType;

class StudentIntakeCourseFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student intake course fee.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student_intake_course_fee', 'view') == true && feeSetting('fee_module') == 'yes') {
            $studentIntakeCourses = StudentIntakeCourse::where('student_id', $id)->where('status', 1)->get();
            $intake_course_ids = [];
            foreach ($studentIntakeCourses as $studentIntakeCourse) {
                $intake_course_ids[] = $studentIntakeCourse->intake_course_id;
            }
            $fees = StudentIntakeCourseFee::where('student_id', $id)->whereIn('intake_course_id', $intake_course_ids)->where('status', 1)->orderBy('id', 'asc')->get();
            foreach ($fees as $key => $value) {
                $fees[$key]['installments'] = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $value->id)->where('parent_id', 0)->orderBy('due_date')->get();
                $installment_discounts = scholarshipInstallmentDiscounts($value->id);
                $p_amount = [];
                $r_amount = [];
                foreach ($fees[$key]['installments'] as $index => $installment) {
                    $paid = $installment->studentIntakeCourseFeePayment;
                    if ($paid) {
                        $p_amount[] = $paid->paid_amount;
                        $r_amount[] = $paid->remaining_amount;
                        if ($paid->paymentRefund && $paid->paymentRefund->reinstate == 1) {
                            $ri_amount[] = $paid->paid_amount;
                        } else {
                            $ri_amount[] = 0;
                        }
                    } else {
                        $p_amount[] = 0;
                        $r_amount[] = 0;
                        $ri_amount[] = 0;
                    }
                    if ($installment->status == 3) {
                        $rf_amount[] = $installment->amount;
                    } else {
                        $rf_amount[] = 0;
                    }
                    $extra_amount = [];
                    foreach ($installment->studentCourseFeeInstallments as $fee_installment) {
                        $extra_amount[] = $fee_installment->amount;
                    }
                    $fees[$key]['installments'][$index]['extra_fee'] = array_sum($extra_amount);
                    $fees[$key]['installments'][$index]['scholarship_discount'] = $installment_discounts[$installment->id] ?? 0;
                }
                $fee = $value->fee;
                $extra = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->where('parent_id', '!=', 0)->sum('amount');
                $fees[$key]['total_fee'] = $extra + $fee;
                $fees[$key]['total_intsallment'] = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $value->id)->where('parent_id', 0)->sum('amount');
                $fees[$key]['paid_installment'] = array_sum($p_amount);
                $fees[$key]['remaining'] = $fees[$key]['total_fee'] - ($fees[$key]['total_intsallment'] - array_sum($r_amount) - array_sum($ri_amount)) - $extra;
                $fees[$key]['remaining_installment'] = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $value->id)->where('parent_id', 0)->where('status', 1)->sum('amount');
                $fees[$key]['refunded_amount'] = array_sum($rf_amount);
                $fees[$key]['student_intake_course'] = StudentIntakeCourse::where('student_id', $id)->where('intake_course_id', $value->intake_course_id)->first();
            }
            $payments = Payment::where('status', 1)->get();
            $today = date('Y-m-d');
            activityLog('Admin', 'Opened ' . userName('Student', $id) . ' Intake Course Fee Menu');
            return view('student::fee.index', compact('student', 'fees', 'payments', 'today'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student intake course fee.
     * @return Renderable
     */
    public function create($id)
    {
        $student = Student::findorfail($id);
        if (checkRole('student_intake_course_fee', 'add') == true && feeSetting('fee_module') == 'yes') {
            $intake_courses = StudentIntakeCourse::where('student_id', $id)->where('status', 1)->get();
            return view('student::fee.create', compact('student', 'intake_courses'));
        } else {
            return redirect()->route('admin.student.fee.index', $id)->with('failure', 'This user does not have permission to add student fee');
        }
    }

    /**
     * Store a newly created student intake course fee in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $student = Student::find($id);
        $studentIntakeCourse = StudentIntakeCourseFee::where('student_id', $student->id)->where('intake_course_id', $data['intake_course_id'])->first();
        if ($studentIntakeCourse) {
            return redirect()->back()->with('failure', 'Student Fee with this intake has already been assigned');
        } else {
            $intake_course_fee = IntakeCourseFee::find($data['fee_id']);
            $student_intake_course_fee = StudentIntakeCourseFee::create([
                'student_id' => $id,
                'intake_course_id' => $request->intake_course_id,
                'name' => $intake_course_fee->name,
                'fee' => $intake_course_fee->fee,
                'extra_fee' => $intake_course_fee->extra_fee,
                'installments' => $intake_course_fee->installments,
                'due_date' => $intake_course_fee->due_date,
                'status' => $intake_course_fee->status
            ]);
            $intake_course_fee_types = IntakeCourseFeeType::where('intake_course_fee_id', $intake_course_fee->id)->get();
            foreach ($intake_course_fee_types as $type) {
                StudentIntakeCourseFeeType::create([
                    'student_intake_course_fee_id' =>  $student_intake_course_fee->id,
                    'key' => $type->key,
                    'value' => $type->value,
                    'type' => $type->type,
                    'status' => $type->status
                ]);
            }
            $studentIntakeCourse = StudentIntakeCourse::where('student_id', $id)->where('intake_course_id', $request->intake_course_id)->first();
            $intake_course_fee_installments = IntakeCourseFeeInstallment::where('intake_course_fee_id', $intake_course_fee->id)->where('parent_id', 0)->get();
            foreach ($intake_course_fee_installments as $key => $value) {
                $due_date = $this->studentFeeInstallmentDueDate($studentIntakeCourse ? $studentIntakeCourse->intakeCourse : null, $key + 1, $value->due_date);
                $student_course_fee_installment = StudentIntakeCourseFeeInstallment::create([
                    'student_intake_course_fee_id' => $student_intake_course_fee->id,
                    'name' => $value->name,
                    'amount' => $value->amount,
                    'due_date' => $due_date,
                    'status' => $value->status
                ]);

                $parents = IntakeCourseFeeInstallment::where('intake_course_fee_id', $intake_course_fee->id)->where('parent_id', $value->id)->get();
                foreach ($parents as $parent) {
                    StudentIntakeCourseFeeInstallment::create([
                        'student_intake_course_fee_id' => $student_intake_course_fee->id,
                        'name' => $parent->name,
                        'amount' => $parent->amount,
                        'due_date' => $this->studentFeeInstallmentDueDate($studentIntakeCourse ? $studentIntakeCourse->intakeCourse : null, $key + 1, $parent->due_date),
                        'parent_id' => $student_course_fee_installment->id,
                        'status' => $parent->status
                    ]);
                }
            }
            activityLog('Admin', 'Fee of ' . userName('Student', $student->id) . ' created');
            return redirect()->route('admin.student.fee.index', $id)->with('success', 'Student Fee added successfully');
        }
    }

    private function studentFeeInstallmentDueDate($intakeCourse, $installmentNumber, $templateDueDate = null)
    {
        if ($this->isUsableInstallmentDueDate($templateDueDate)) {
            return $templateDueDate;
        }

        $startingDate = $intakeCourse ? $intakeCourse->starting_date : null;
        $timestamp = $startingDate ? strtotime($startingDate) : false;
        if ($timestamp === false) {
            return $templateDueDate;
        }

        $months = max(1, (int) $installmentNumber) * 6;
        return date('Y-m-d', strtotime('+' . $months . ' months', $timestamp));
    }

    private function isUsableInstallmentDueDate($dueDate)
    {
        $timestamp = $dueDate ? strtotime($dueDate) : false;
        if ($timestamp === false) {
            return false;
        }

        return (int) date('Y', $timestamp) >= 2000;
    }

    /**
     * Show the form for editing the specified student intake course fee.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $studentIntakeCourseFee = StudentIntakeCourseFee::findorfail($id);
        if (checkRole('student_intake_course_fee', 'edit') == true && feeSetting('fee_module') == 'yes') {
            activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourseFee->student_id) . ' Fee Edit Page');
            return view('student::intake.fee.edit', compact('studentIntakeCourseFee'));
        } else {
            return redirect()->route('admin.student.intake.course.fee.index', $studentIntakeCourseFee->student_id)->with('failure', 'This user does not have permission to edit student fee');
        }
    }

    /**
     * Update the specified student intake course fee in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $studentIntakeCourseFee = StudentIntakeCourseFee::where('id', $id)->first();
        $studentIntakeCourseFee->update($data);
        activityLog('Admin', 'Opened ' . userName('Student', $studentIntakeCourseFee->student_id) . ' Fee Updates');
        return redirect()->route('admin.student.intake.course.fee.index', $studentIntakeCourseFee->student_id)->with('success', 'Student Fee updated successfully');
    }

    /**
     * Remove the specified student intake course fee from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /* Get Intake Course Fee By Intake Course Id */
    function getIntakeCourseFee(Request $request)
    {
        $fees = IntakeCourseFee::where('intake_course_id', $request->intake_course_id)->where('status', 1)->pluck('name', 'id');
        return response()->json($fees);
    }

    public function pay($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $installment = StudentIntakeCourseFeeInstallment::findorfail($id);
            $student = Student::find($installment->studentIntakeCourseFee->student_id);
            $first_installment = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $installment->student_intake_course_fee_id)->first();
            if ($installment->id == $first_installment->id) {
                $fee = StudentIntakeCourseFee::find($installment->student_intake_course_fee_id);
                $fee_types = $fee->fee_types;
            } else {
                $fee_types = [];
            }
            $extra_amount = [];
            foreach ($installment->studentCourseFeeInstallments as $fee_installment) {
                $extra_amount[] = $fee_installment->amount;
            }
            $extra_fee = array_sum($extra_amount);
            $discounts = scholarshipInstallmentDiscounts($installment->student_intake_course_fee_id);
            $scholarship_discount = $discounts[$installment->id] ?? 0;
            $total_fee = $installment->amount + $extra_fee - $scholarship_discount;
            $today = date('Y-m-d');
            $payments = Payment::where('status', 1)->get();
            return view('student::fee.pay', compact('installment', 'student', 'today', 'payments', 'fee_types', 'extra_fee', 'total_fee', 'scholarship_discount'));
        } else {
            return abort(404);
        }
    }

    public function payEdit($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $installment = StudentIntakeCourseFeeInstallment::findorfail($id);
            $student = Student::find($installment->studentIntakeCourseFee->student_id);
            $first_installment = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $installment->student_intake_course_fee_id)->first();
            if ($installment->id == $first_installment->id) {
                $fee = StudentIntakeCourseFee::find($installment->student_intake_course_fee_id);
                $fee_types = $fee->fee_types;
            } else {
                $fee_types = [];
            }
            $extra_amount = [];
            foreach ($installment->studentCourseFeeInstallments as $fee_installment) {
                $extra_amount[] = $fee_installment->amount;
            }
            $extra_fee = array_sum($extra_amount);
            $discounts = scholarshipInstallmentDiscounts($installment->student_intake_course_fee_id);
            $scholarship_discount = $discounts[$installment->id] ?? 0;
            $total_fee = $installment->amount + $extra_fee - $scholarship_discount;
            $payments = Payment::where('status', 1)->get();
            return view('student::fee.edit', compact('installment', 'student', 'payments', 'fee_types', 'extra_fee', 'total_fee', 'scholarship_discount'));
        } else {
            return abort(404);
        }
    }

    public function receipt($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $installment = StudentIntakeCourseFeeInstallment::findorfail($id);
            $student = Student::find($installment->studentIntakeCourseFee->student_id);
            $company = Company::first();
            return view('student::fee.receipt', compact('installment', 'student', 'company'));
        } else {
            return abort(404);
        }
    }

    public function receiptPrint($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $installment = StudentIntakeCourseFeeInstallment::findorfail($id);
            $student = Student::find($installment->studentIntakeCourseFee->student_id);
            $company = Company::first();
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml(view('student::fee.receipt', compact('installment', 'student', 'company')));

            $file_name = str_replace(" ", "_", userName('Student', $student->id)) . '_' . str_replace(" ", "_", $installment->name) . '_' . '_receipt.pdf';

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'potrait');

            // Render the HTML as PDF
            $dompdf->render();


            // Output the generated PDF (1 = download and 0 = preview)
            $dompdf->stream($file_name, array("Attachment" => 1));
        } else {
            return abort(404);
        }
    }

    public function statement($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $studentIntakeCourseFee = StudentIntakeCourseFee::findorfail($id);
            $installments = $studentIntakeCourseFee->fee_installments;
            foreach ($installments as $payment) {
                if ($payment->studentIntakeCourseFeePayment != NULL) {
                    $installment_payments[] = [
                        'payment_name' => $payment->name,
                        'total_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        'paid_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        'date' => dateFormat($payment->studentIntakeCourseFeePayment->paid_date)
                    ];
                } else {
                    $installment_payments[] = [
                        'payment_name' => $payment->name,
                        'total_amount' => $payment->amount,
                        'paid_amount' => 0,
                        'date' => dateFormat($payment->due_date) . ' (Due Date)'
                    ];
                }
            }
            $total_received = array_sum(array_column($installment_payments, 'paid_amount'));
            $balance = $studentIntakeCourseFee->fee - $total_received;
            $student = Student::find($studentIntakeCourseFee->student_id);
            $company = Company::first();
            return view('student::fee.statement', compact('studentIntakeCourseFee', 'installment_payments', 'total_received', 'student', 'company'));
        } else {
            return abort(404);
        }
    }

    public function statementPrint($id)
    {
        if (checkRole('student_intake_course_fee', 'edit') == true) {
            $studentIntakeCourseFee = StudentIntakeCourseFee::findorfail($id);
            $installments = $studentIntakeCourseFee->fee_installments;
            foreach ($installments as $payment) {
                if ($payment->studentIntakeCourseFeePayment != NULL) {
                    $installment_payments[] = [
                        'payment_name' => $payment->name,
                        'total_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        'paid_amount' => $payment->studentIntakeCourseFeePayment->total_amount,
                        'date' => dateFormat($payment->studentIntakeCourseFeePayment->paid_date)
                    ];
                } else {
                    $installment_payments[] = [
                        'payment_name' => $payment->name,
                        'total_amount' => $payment->amount,
                        'paid_amount' => 0,
                        'date' => dateFormat($payment->due_date) . ' (Due Date)'
                    ];
                }
            }
            $total_received = array_sum(array_column($installment_payments, 'paid_amount'));
            $balance = $studentIntakeCourseFee->fee - $total_received;
            $student = Student::find($studentIntakeCourseFee->student_id);
            $company = Company::first();
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml(view('student::fee.statement', compact('studentIntakeCourseFee', 'installment_payments', 'total_received', 'student', 'company')));
            $file_name = str_replace(" ", "_", userName('Student', $student->id)) . '_' . str_replace(" ", "_", $studentIntakeCourseFee->name) . '_' . 'statement.pdf';

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'potrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF (1 = download and 0 = preview)
            $dompdf->stream($file_name, array("Attachment" => 1));
        } else {
            return abort(404);
        }
    }
}
