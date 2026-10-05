<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;
use Modules\Student\Entities\StudentAgent;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallmentPayment;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallmentPaymentNote;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallmentPaymentRefund;

class StudentIntakeCourseFeeInstallmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student intake course fee installment.
     * @return Renderable
     */
    public function index($id)
    {
        $student_intake_course_fee = StudentIntakeCourseFee::find($id);
        if (checkRole('student_intake_course_fee', 'view') == true && $student_intake_course_fee) {
            $installments = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->orderBy('due_date')->get();
            if ($student_intake_course_fee->enrollment_fee_wavier == 1) $enrollment_fee = 0;
            else $enrollment_fee = $student_intake_course_fee->enrollment_fee;
            if ($student_intake_course_fee->material_fee_wavier == 1) $material_fee = 0;
            else $material_fee = $student_intake_course_fee->material_fee;
            $fee = $student_intake_course_fee->fee;
            $total_fee = $enrollment_fee + $material_fee + $fee;
            $total_intsallment = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->sum('amount');
            foreach ($installments as $key => $value) {
                $paid = $value->studentIntakeCourseFeePayment;
                if ($paid) {
                    $p_amount[] = $paid->paid_amount;
                    $r_amount[] = $paid->remaining_amount;
                } else {
                    $p_amount[] = 0;
                    $r_amount[] = 0;
                }
            }
            $paid_installment = array_sum($p_amount);
            $remaining = $total_fee - ($total_intsallment - array_sum($r_amount));
            $remaining_installment = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->where('status', 1)->sum('amount');
            $student_intake_course = StudentIntakeCourse::where('student_id', $student_intake_course_fee->student_id)->where('intake_course_id', $student_intake_course_fee->intake_course_id)->first();
            $payments = Payment::where('status', 1)->get();
            $student_agent = StudentAgent::where('student_id', $id)->first();
            if ($student_agent) {
                $agent_commission = $student_intake_course_fee->student->studentAgent->agent->rate;
                if ($student_intake_course_fee->student->studentAgent->branch == NULL) {
                    $branch_commission = 0;
                } else {
                    $branch_commission = $student_intake_course_fee->student->studentAgent->branch->rate;
                }
            } else {
                $agent_commission = 0;
                $branch_commission = 0;
            }
            return view('student::intake.fee.installment.index', compact(
                'student_intake_course_fee',
                'installments',
                'enrollment_fee',
                'material_fee',
                'fee',
                'total_fee',
                'remaining',
                'student_intake_course',
                'paid_installment',
                'remaining_installment',
                'payments',
                'agent_commission',
                'branch_commission'
            ))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified student intake course fee installment.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $student_intake_course_fee = StudentIntakeCourseFee::find($id);
        if (checkRole('student_intake_course_fee', 'edit') == true && $student_intake_course_fee) {
            $installments = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->orderBy('due_date',)->get();
            if ($student_intake_course_fee->enrollment_fee_wavier == 1) $enrollment_fee = 0;
            else $enrollment_fee = $student_intake_course_fee->enrollment_fee;
            if ($student_intake_course_fee->material_fee_wavier == 1) $material_fee = 0;
            else $material_fee = $student_intake_course_fee->material_fee;
            $fee = $student_intake_course_fee->fee;
            $total_fee = $enrollment_fee + $material_fee + $fee;
            $total_intsallment = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->sum('amount');
            foreach ($installments as $key => $value) {
                $paid = $value->studentIntakeCourseFeePayment;
                if ($paid) {
                    $p_amount[] = $paid->paid_amount;
                    $installments[$key]['installment_paid_amount'] = $paid->paid_amount;
                    $r_amount[] = $paid->remaining_amount;
                    if ($paid->paymentRefund && $paid->paymentRefund->reinstate == 1) {
                        $ri_amount[] = $paid->paid_amount;
                    } else {
                        $ri_amount[] = 0;
                    }
                } else {
                    $p_amount[] = 0;
                    $installments[$key]['installment_paid_amount'] = 0;
                    $r_amount[] = 0;
                    $ri_amount[] = 0;
                }
                if ($value->status == 3) {
                    $rf_amount[] = $value->amount;
                } else {
                    $rf_amount[] = 0;
                }
            }
            $paid_installment = array_sum($p_amount);
            $remaining_amount = array_sum($r_amount);
            $reinstate_amount = array_sum($ri_amount);
            $refunded_amount = array_sum($rf_amount);
            $remaining = $total_fee - ($total_intsallment - $remaining_amount - $reinstate_amount);
            $remaining_installment = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->where('status', 1)->sum('amount');
            $student_intake_course = StudentIntakeCourse::where('student_id', $student_intake_course_fee->student_id)->where('intake_course_id', $student_intake_course_fee->intake_course_id)->first();
            return view('student::fee.installment.edit', compact(
                'student_intake_course_fee',
                'installments',
                'enrollment_fee',
                'material_fee',
                'fee',
                'total_fee',
                'remaining',
                'student_intake_course',
                'paid_installment',
                'remaining_amount',
                'remaining_installment',
                'refunded_amount'
            ));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified student intake course fee installment in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if (isset($data['enrollment_fee_wavier'])) {
            $fee_data['enrollment_fee_wavier'] = 1;
            $installment_data['enrollment_fee'] = $fee_data['enrollment_fee'] = 0;
            StudentIntakeCourseFee::where('id', $id)->update($fee_data);
            StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->update($installment_data);
        }
        if (isset($data['material_fee_wavier'])) {
            $fee_data['material_fee_wavier'] = 1;
            $fee_data['material_fee'] = 0;
            $installment_data['material_fee'] = $fee_data['material_fee'] = 0;
            StudentIntakeCourseFee::where('id', $id)->update($fee_data);
            StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $id)->update($installment_data);
        }
        $studnetIntakeCourseFee = StudentIntakeCourseFee::find($id);

        if ($studnetIntakeCourseFee->enrollment_fee_wavier == 1) $enrollment_fee = 0;
        else $enrollment_fee = $studnetIntakeCourseFee->enrollment_fee;
        if ($studnetIntakeCourseFee->material_fee_wavier == 1) $material_fee = 0;
        else $material_fee = $studnetIntakeCourseFee->material_fee;
        $extra = $enrollment_fee + $material_fee;
        $fee = $studnetIntakeCourseFee->fee;
        $total_fee = $enrollment_fee + $material_fee + $fee;

        if ($data['amount'][0] < $extra) {
            return redirect()->back()->with('failure', 'Amount of first installment should be more than ' . $extra);
        }

        $inst_amount = $data['amount'];

        foreach ($data['installment_paid_amount'] as $p => $p_amount) {
            if ($p_amount != 0) {
                $inst_amount[$p] = $p_amount;
            }
        }

        if (array_sum($inst_amount) > $total_fee) {
            return redirect()->back()->with('failure', 'Total amount should not be more than ' . $total_fee);
        }

        $installments = $studnetIntakeCourseFee->installments;
        foreach ($installments as $key => $value) {
            $installment[] = $value->id;
        };

        $deleteInstallment = array_diff($installment, $data['installment_id']);
        foreach ($deleteInstallment as $key => $value) {
            $installment = StudentIntakeCourseFeeInstallment::where('id', $value)->delete();
        }

        $installmentCount = count($data['name']);
        $installmentIdCount = count($data['installment_id']);
        if ($installmentCount > $installmentIdCount) {
            $diff = $installmentCount - $installmentIdCount;
            for ($i = 0; $i < $diff; $i++) {
                array_push($data['installment_id'], 0);
            }
        }

        foreach ($data['name'] as $key => $value) {
            $installment = StudentIntakeCourseFeeInstallment::where('id', $data['installment_id'][$key])->first();
            if ($installment) {
                $installment->update([
                    'name' => $value,
                    'amount' => $data['amount'][$key],
                    'due_date' => $data['due_date'][$key],
                ]);
            } else {
                StudentIntakeCourseFeeInstallment::create([
                    'student_intake_course_fee_id' => $id,
                    'name' => $value,
                    'amount' => $data['amount'][$key],
                    'due_date' => $data['due_date'][$key],
                ]);
            }
        }
        activityLog('Admin', $studnetIntakeCourseFee->name . ' of ' . userName('Student', $studnetIntakeCourseFee->student_id) . ' updated');
        return redirect()->route('admin.student.fee.index', $studnetIntakeCourseFee->student_id)->with('success', $studnetIntakeCourseFee->name . ' Installment updated successfully');
    }

    public function intallmentPayment(Request $request, $id)
    {
        $data = $request->all();
        $data['student_intake_course_fee_installment_id'] = $id;
        $installment = StudentIntakeCourseFeeInstallment::find($id);
        if ($request->has('receipt')) {
            $imageName = time() . '.' . request()->receipt->getClientOriginalExtension();
            request()->receipt->move(public_path('/images/students/intallments'), $imageName);
            $data['receipt'] = 'images/students/intallments/' . $imageName;
        }
        $payment = StudentIntakeCourseFeeInstallmentPayment::create($data);
        if ($data['paid_amount'] < $data['total_amount']) {
            StudentIntakeCourseFeeInstallment::create([
                'student_intake_course_fee_id' => $installment->student_intake_course_fee_id,
                'name' => $installment->name . ' Remaining Payment',
                'amount' => $data['remaining_amount'],
                'due_date' => $installment->due_date,
            ]);
        }
        if (isset($data['comments']) && $data['comments'] != "") {
            StudentIntakeCourseFeeInstallmentPaymentNote::create([
                'student_intake_course_fee_installment_payment_id' => $payment->id,
                'notes' => $data['comments'],
            ]);
        }
        StudentIntakeCourseFeeInstallment::where('id', $id)->update(['status' => 2]);
        activityLog('Admin', $installment->name . ' of ' . userName('Student', $installment->studentIntakeCourseFee->student_id) . ' is paid');
        $message = $installment->name . ' of ' . $installment->studentIntakeCourseFee->name . ' has been paid';
        return redirect()->route('admin.student.fee.index', $installment->studentIntakeCourseFee->student_id)->with('success', $message);
    }

    public function installmentUpdate(Request $request, $id)
    {
        $data = $request->all();
        $payment = StudentIntakeCourseFeeInstallmentPayment::where('id', $id)->first();
        unset($data['_token']);
        if ($request->has('receipt')) {
            $imageName = time() . '.' . request()->receipt->getClientOriginalExtension();
            request()->receipt->move(public_path('/images/students/intallments'), $imageName);
            $data['receipt'] = 'images/students/intallments/' . $imageName;
        }
        if (isset($data['comments']) && $data['comments'] != "") {
            StudentIntakeCourseFeeInstallmentPaymentNote::create([
                'student_intake_course_fee_installment_payment_id' => $payment->id,
                'notes' => $data['comments'],
            ]);
        }
        $payment->update($data);
        activityLog('Admin', $payment->studentIntakeCourseFeeInstallment->name  . ' of ' . userName('Student', $payment->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student_id) . ' has been updated');
        $message = $payment->studentIntakeCourseFeeInstallment->name . ' payment updated';
        return redirect()->route('admin.student.fee.index', $payment->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student_id)->with('success', $message);
    }

    public function refundPayment(Request $request, $id)
    {
        $data = $request->all();
        $payment = StudentIntakeCourseFeeInstallmentPayment::where('id', $id)->first();
        if ($request->has('receipt')) {
            $imageName = time() . '.' . request()->receipt->getClientOriginalExtension();
            request()->receipt->move(public_path('/images/students/intallments/refunds'), $imageName);
            $data['receipt'] = 'images/students/intallments/refunds/' . $imageName;
        }
        $data['student_intake_course_fee_installment_payment_id'] = $id;
        StudentIntakeCourseFeeInstallmentPaymentRefund::create($data);
        if (isset($data['reinstate']) && $data['reinstate'] == 1) {
            StudentIntakeCourseFeeInstallment::create([
                'student_intake_course_fee_id' => $payment->studentIntakeCourseFeeInstallment->student_intake_course_fee_id,
                'name' => $payment->studentIntakeCourseFeeInstallment->name . ' Reinstate Refund Payment',
                'amount' => $data['refunded_amount'],
                'due_date' => $payment->studentIntakeCourseFeeInstallment->due_date,
            ]);
        }
        StudentIntakeCourseFeeInstallment::where('id',  $payment->studentIntakeCourseFeeInstallment->id)->update(['status' => 3]);
        activityLog('Admin', $payment->studentIntakeCourseFeeInstallment->name  . ' of ' . userName('Student', $payment->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student_id) . ' has been refunded');
        $message = $payment->studentIntakeCourseFeeInstallment->name . ' payment refunded';
        return redirect()->back()->with('success', $message);
    }
}
