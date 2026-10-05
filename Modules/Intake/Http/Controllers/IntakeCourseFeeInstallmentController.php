<?php

namespace Modules\Intake\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeCourseFee;
use Modules\Intake\Entities\IntakeCourseFeeInstallment;

class IntakeCourseFeeInstallmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the intake course fee installment.
     * @return Renderable
     */
    public function index($id)
    {
        $intakeCourseFee = IntakeCourseFee::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourseFee->intakeCourse->course_id);
        if (checkRole('intake_course_fee', 'view') == true && $intakeCourseFee && $check == true) {
            $installments = IntakeCourseFeeInstallment::where('intake_course_fee_id', $id)->where('parent_id', 0)->orderBy('id', 'asc')->get();
            foreach ($installments as $key => $value) {
                $extra_amount = [];
                foreach ($value->intakeCourseFeeInstallments as $inst) {
                    $extra_amount[] = $inst->amount;
                }
                $installments[$key]['extra_fee'] = array_sum($extra_amount);
            }
            $total_extra_fee = IntakeCourseFeeInstallment::where('intake_course_fee_id', $id)->where('parent_id', '!=', 0)->sum('amount');
            $total_fee = $intakeCourseFee->fee + $total_extra_fee;
            $total_intsallment = IntakeCourseFeeInstallment::where('intake_course_fee_id', $id)->where('parent_id', 0)->sum('amount');
            $remaining = $intakeCourseFee->fee - $total_intsallment;
            activityLog('Admin', $intakeCourseFee->intakeCourse->reference_name . ' fee installment lists opened');
            return view('intake::course.fee.installment.index', compact('intakeCourseFee', 'installments', 'total_fee', 'total_intsallment', 'remaining', 'total_extra_fee'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified intake course fee installment.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $intakeCourseFee = IntakeCourseFee::findorfail($id);
        $check = checkCourseDeliverySite($intakeCourseFee->intakeCourse->course_id);
        if (checkRole('intake_course_fee', 'edit') == true && $intakeCourseFee && $check == true) {
            $installments = IntakeCourseFeeInstallment::where('intake_course_fee_id', $id)->where('parent_id', 0)->orderBy('id', 'asc')->get();
            $fee = $intakeCourseFee->fee;
            $total_extra_fee = IntakeCourseFeeInstallment::where('intake_course_fee_id', $id)->where('parent_id', '!=', 0)->sum('amount');
            $total_fee = $fee + $total_extra_fee;
            $total_intsallment = IntakeCourseFeeInstallment::where('intake_course_fee_id', $id)->where('parent_id', 0)->sum('amount');
            $remaining = $fee - $total_intsallment;
            activityLog('Admin', $intakeCourseFee->intakeCourse->reference_name . ' fee installment edit page opened');
            return view('intake::course.fee.installment.edit', compact('intakeCourseFee', 'installments', 'fee', 'total_fee', 'total_intsallment', 'remaining', 'total_extra_fee'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified intake course fee installment in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $intakeCourseFee = IntakeCourseFee::find($id);

        $type_fee = [];
        foreach ($intakeCourseFee->intakeCourseFeeTypes as $fee_type) {
            $type_fee[] = $fee_type->value;
        }

        $installment_fee = [];
        $extra = array_sum($type_fee);
        foreach ($intakeCourseFee->fee_installments as $icf) {
            $installment_fee[] = $icf->amount;
        }

        $total_fee = array_sum($installment_fee);
        if ($data['amount'][0] < $extra) {
            return redirect()->back()->with('failure', 'Amount of first installment should be more than ' . $extra);
        }

        if (array_sum($data['amount']) > $total_fee) {
            return redirect()->back()->with('failure', 'Total amount should not be more than ' . $total_fee);
        }

        $installments = $intakeCourseFee->fee_installments;
        foreach ($installments as $key => $value) {
            $installment[] = $value->id;
        };

        $deleteInstallment = array_diff($installment, $data['installment_id']);
        foreach ($deleteInstallment as $key => $value) {
            $installment = IntakeCourseFeeInstallment::where('id', $value)->delete();
        }

        // $installmentCount = count($data['name']);
        // $installmentIdCount = count($data['installment_id']);
        // if ($installmentCount > $installmentIdCount) {
        //     $diff = $installmentCount - $installmentIdCount;
        //     for ($i = 0; $i < $diff; $i++) {
        //         array_push($data['installment_id'], 0);
        //     }
        // }

        dd($data);
        foreach ($data['name'] as $key => $value) {
            $installment = IntakeCourseFeeInstallment::where('id', $data['installment_id'][$key])->first();
            if ($installment) {
                $installment->update([
                    'name' => $value,
                    'amount' => $data['amount'][$key],
                    'due_date' => $data['due_date'][$key],
                ]);
            } else {
                IntakeCourseFeeInstallment::create([
                    'intake_course_fee_id' => $id,
                    'name' => $value,
                    'amount' => $data['amount'][$key],
                    'due_date' => $data['due_date'][$key],
                ]);
            }
        }
        return redirect()->route('admin.intake.course.fee.installment.index', $id)->with('success', 'Installment updated successfully');
    }
}
