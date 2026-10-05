<?php

namespace Modules\Report\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Report\Entities\ReportTemplate;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;

class FeeReceivedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }
    
    /**
     * Display a listing of the fee received.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('fee_received_report', 'view') == true) {
            $data = $request->all();
            $from = NULL;
            $to = NULL;
            $agent = NULL;
            $agents = Agent::where('status', 1)->get();
            if (isset($data['agent'])) {
                $agent = $agent_ids[] = $data['agent'];
            } else {
                $all_agents = Agent::where('status', 1)->get();
                foreach ($all_agents as $key => $value) {
                    $agent_ids[] = $value->id;
                }
            }
            $all_payments = StudentIntakeCourseFeeInstallment::join('student_intake_course_fees', 'student_intake_course_fees.id', '=', 'student_intake_course_fee_installments.student_intake_course_fee_id')
                ->join('students', 'students.id', '=', 'student_intake_course_fees.student_id')
                ->whereIn('students.status', [0, 1])
                ->where('students.is_enrolled', 1)
                ->where('student_intake_course_fee_installments.status', 2)
                ->select('student_intake_course_fee_installments.*', 'student_intake_course_fees.student_id');
            if ($agent == NULL) {
                $allPayments = $all_payments;
            } else {
                $allPayments = $all_payments->join('student_agents', 'student_agents.student_id', '=', 'students.id')
                    ->where(function ($query) use ($agent_ids) {
                        $query->whereNull('student_agents.student_id')
                            ->orWhereIn('student_agents.agent_id', $agent_ids);
                    });
            }
            if (isset($data['from']) || isset($data['to'])) {
                if ($data['from'] == NULL) {
                    $student_from = StudentIntakeCourseFeeInstallment::orderBy('due_date', 'asc')->first();
                    $data['from'] = $student_from->due_date;
                }
                if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                $from = $data['from'];
                $to = $data['to'];
                $payments = $allPayments
                    ->join('student_intake_course_fee_installment_payments', 'student_intake_course_fee_installment_payments.student_intake_course_fee_installment_id', '=', 'student_intake_course_fee_installments.id')
                    ->whereBetween('student_intake_course_fee_installment_payments.paid_date', [$from, $to])
                    ->whereBetween('student_intake_course_fee_installment_payments.paid_date', [$from, $to])
                    ->orderBy('student_intake_course_fee_installments.id', 'desc')
                    ->get();
            } else {
                $payments = $allPayments->orderBy('student_intake_course_fee_installments.id', 'desc')->get();
            }
            activityLog('Admin', 'Opened Fee Received Report Menu');
            return view('report::fee.index', compact('payments', 'from', 'to', 'agent', 'agents'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    public function print(Request $request)
    {
        if (checkRole('fee_received_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['template_id']) || $data['template_id'] == NULL) {
                return redirect()->back()->with('failure', 'Please choose one template');
            } else {
                $from = NULL;
                $to = NULL;
                $agent = NULL;
                $agents = Agent::where('status', 1)->get();
                if (isset($data['agent'])) {
                    $agent = $agent_ids[] = $data['agent'];
                } else {
                    $all_agents = Agent::where('status', 1)->get();
                    foreach ($all_agents as $key => $value) {
                        $agent_ids[] = $value->id;
                    }
                }
                $all_payments = StudentIntakeCourseFeeInstallment::join('student_intake_course_fees', 'student_intake_course_fees.id', '=', 'student_intake_course_fee_installments.student_intake_course_fee_id')
                    ->join('students', 'students.id', '=', 'student_intake_course_fees.student_id')
                    ->whereIn('students.status', [0, 1])
                    ->where('students.is_enrolled', 1)
                    ->where('student_intake_course_fee_installments.status', 2)
                    ->select('student_intake_course_fee_installments.*', 'student_intake_course_fees.student_id');
                if ($agent == NULL) {
                    $allPayments = $all_payments;
                } else {
                    $allPayments = $all_payments->join('student_agents', 'student_agents.student_id', '=', 'students.id')
                        ->where(function ($query) use ($agent_ids) {
                            $query->whereNull('student_agents.student_id')
                                ->orWhereIn('student_agents.agent_id', $agent_ids);
                        });
                }
                if (isset($data['from']) || isset($data['to'])) {
                    if ($data['from'] == NULL) {
                        $student_from = StudentIntakeCourseFeeInstallment::orderBy('due_date', 'asc')->first();
                        $data['from'] = $student_from->due_date;
                    }
                    if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                    $from = $data['from'];
                    $to = $data['to'];
                    $payments = $allPayments
                        ->join('student_intake_course_fee_installment_payments', 'student_intake_course_fee_installment_payments.student_intake_course_fee_installment_id', '=', 'student_intake_course_fee_installments.id')
                        ->whereBetween('student_intake_course_fee_installment_payments.paid_date', [$from, $to])
                        ->whereBetween('student_intake_course_fee_installment_payments.paid_date', [$from, $to])
                        ->orderBy('student_intake_course_fee_installments.id', 'desc')
                        ->get();
                } else {
                    $payments = $allPayments->orderBy('student_intake_course_fee_installments.id', 'desc')->get();
                }
                $template = ReportTemplate::findorfail($data['template_id']);
                $no = 1;

                $options = new Options();
                $options->set('isRemoteEnabled', true);
                $options->set('isHtml5ParserEnabled', true);
                $dompdf = new Dompdf($options);

                $placeholders = [
                    '{{school_name}}'  => getTitle(),
                    '{{report_date}}'  => date('d/m/Y'),
                    '{{generated_by}}' => userName('Admin', auth('user')->user()->id),
                    '{{report_title}}' => 'Fee Received Report',
                ];
                $dompdf->loadHtml(view('report::fee.show', compact('payments', 'from', 'to', 'template', 'no', 'agent', 'agents', 'placeholders')));
                $file_name = 'Fee_Received_Report.pdf';
                // (Optional) Setup the paper size and orientation  
                $dompdf->setPaper('A4', 'potrait');

                // Render the HTML as PDF  
                $dompdf->render();


                // Output the generated PDF (1 = download and 0 = preview) 
                $dompdf->stream($file_name, array("Attachment" => 1));
            }
        } else {
            return abort(404);
        }
    }

    public function show(Request $request)
    {
        if (checkRole('fee_received_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['template_id']) || $data['template_id'] == NULL) {
                return redirect()->back()->with('failure', 'Please choose one template');
            } else {
                $from = NULL;
                $to = NULL;
                $agent = NULL;
                $agents = Agent::where('status', 1)->get();
                if (isset($data['agent'])) {
                    $agent = $agent_ids[] = $data['agent'];
                } else {
                    $all_agents = Agent::where('status', 1)->get();
                    foreach ($all_agents as $key => $value) {
                        $agent_ids[] = $value->id;
                    }
                }
                $all_payments = StudentIntakeCourseFeeInstallment::join('student_intake_course_fees', 'student_intake_course_fees.id', '=', 'student_intake_course_fee_installments.student_intake_course_fee_id')
                    ->join('students', 'students.id', '=', 'student_intake_course_fees.student_id')
                    ->whereIn('students.status', [0, 1])
                    ->where('students.is_enrolled', 1)
                    ->where('student_intake_course_fee_installments.status', 2)
                    ->select('student_intake_course_fee_installments.*', 'student_intake_course_fees.student_id');
                if ($agent == NULL) {
                    $allPayments = $all_payments;
                } else {
                    $allPayments = $all_payments->join('student_agents', 'student_agents.student_id', '=', 'students.id')
                        ->where(function ($query) use ($agent_ids) {
                            $query->whereNull('student_agents.student_id')
                                ->orWhereIn('student_agents.agent_id', $agent_ids);
                        });
                }
                if (isset($data['from']) || isset($data['to'])) {
                    if ($data['from'] == NULL) {
                        $student_from = StudentIntakeCourseFeeInstallment::orderBy('due_date', 'asc')->first();
                        $data['from'] = $student_from->due_date;
                    }
                    if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                    $from = $data['from'];
                    $to = $data['to'];
                    $payments = $allPayments
                        ->join('student_intake_course_fee_installment_payments', 'student_intake_course_fee_installment_payments.student_intake_course_fee_installment_id', '=', 'student_intake_course_fee_installments.id')
                        ->whereBetween('student_intake_course_fee_installment_payments.paid_date', [$from, $to])
                        ->whereBetween('student_intake_course_fee_installment_payments.paid_date', [$from, $to])
                        ->orderBy('student_intake_course_fee_installments.id', 'desc')
                        ->get();
                } else {
                    $payments = $allPayments->orderBy('student_intake_course_fee_installments.id', 'desc')->get();
                }
                $template = ReportTemplate::findorfail($data['template_id']);
                $placeholders = [
                    '{{school_name}}'  => getTitle(),
                    '{{report_date}}'  => date('d/m/Y'),
                    '{{generated_by}}' => userName('Admin', auth('user')->user()->id),
                    '{{report_title}}' => 'Fee Received Report',
                ];
                activityLog('Admin', 'Opened Fee Received Report Show Menu');
                return view('report::fee.show', compact('payments', 'from', 'to', 'template', 'placeholders'))->with('no', 1);
            }
        } else {
            return abort(404);
        }
    }
}
