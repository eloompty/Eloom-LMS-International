<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallmentPayment;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallmentPaymentCommission;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the agent commission.
     * @return Renderable
     */
    public function index($id)
    {
        $agent = Agent::findorfail($id);
        if (checkRole('agent', 'view') == true && $agent) {
            activityLog('Admin', 'Opened ' . $agent->name . ' commission list');
            $commissions = StudentIntakeCourseFeeInstallmentPayment::join('student_intake_course_fee_installments', 'student_intake_course_fee_installments.id', '=', 'student_intake_course_fee_installment_payments.student_intake_course_fee_installment_id')
                ->join('student_intake_course_fees', 'student_intake_course_fees.id', '=', 'student_intake_course_fee_installments.student_intake_course_fee_id')
                ->join('students', 'students.id', '=', 'student_intake_course_fees.student_id')
                ->join('student_agents', 'student_agents.student_id', 'students.id')
                ->select('student_intake_course_fee_installment_payments.*')
                ->where('agent_id', $id)
                ->orderBy('student_intake_course_fee_installment_payments.id', 'desc')
                ->get();
            return view('agent::commission.index', compact('commissions', 'agent'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Paying Commission to agent.
     * @return Renderable
     */
    public function payCommission($id)
    {
        $commission = StudentIntakeCourseFeeInstallmentPayment::findorfail($id);
        if (checkRole('agent', 'view') == true) {
            activityLog('Admin', 'Opened ' . userName('Student', $commission->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student_id) . ' payment commission');
            $agent_id = $commission->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student->studentAgent->agent_id;
            return view('agent::commission.payment.index', compact('commission', 'agent_id'));
        } else {
            return abort(404);
        }
    }

    /**
     * Paying Commission to agent.
     * @return Renderable
     */
    public function paymentCommission(Request $request, $id)
    {
        $data = $request->all();
        $data['student_intake_course_fee_installment_payment_id'] = $id;
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/agents/receipt'), $imageName);
            $data['receipt'] = 'images/agents/receipt/' . $imageName;
        }
        StudentIntakeCourseFeeInstallmentPaymentCommission::create($data);
        $commission = StudentIntakeCourseFeeInstallmentPayment::find($id);
        $commission->paid_to_agent = 1;
        $commission->save();
        activityLog('Admin', 'Commision for ' . userName('Agent', $data['agent_id']) . ' has been paid');
        return redirect()->route('admin.agent.commission.index', $data['agent_id'])->with('success', 'Commsion has been paid');
    }

    /**
     * Displaying receipt of commission paid to agent.
     * @return Renderable
     */
    public function receipt($id)
    {
        $commission = StudentIntakeCourseFeeInstallmentPayment::findorfail($id);
        if (checkRole('agent', 'view') == true) {
            $agent_id = $commission->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student->studentAgent->agent_id;
            activityLog('Admin', 'Open receipt of ' . userName('Agent', $agent_id));
            return view('agent::commission.receipt.index', compact('commission', 'agent_id'));
        } else {
            return abort(404);
        }
    }
}
