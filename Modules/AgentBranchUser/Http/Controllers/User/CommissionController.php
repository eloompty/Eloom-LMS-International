<?php

namespace Modules\AgentBranchUser\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallmentPayment;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent_branch_user');
    }

    /**
     * Display a listing of the commission.
     * @return Renderable
     */
    public function index()
    {
        $agent_id = Auth::guard('agent_branch_user')->user()->branch->agent_id;
        $commissions = StudentIntakeCourseFeeInstallmentPayment::join('student_intake_course_fee_installments', 'student_intake_course_fee_installments.id', '=', 'student_intake_course_fee_installment_payments.student_intake_course_fee_installment_id')
            ->join('student_intake_course_fees', 'student_intake_course_fees.id', '=', 'student_intake_course_fee_installments.student_intake_course_fee_id')
            ->join('students', 'students.id', '=', 'student_intake_course_fees.student_id')
            ->join('student_agents', 'student_agents.student_id', 'students.id')
            ->select('student_intake_course_fee_installment_payments.*')
            ->where('agent_id', $agent_id)
            ->orderBy('student_intake_course_fee_installment_payments.id', 'desc')
            ->get();
        return view('agentbranchuser::user.commission.index', compact('commissions'))->with('no', 1);
    }
}
