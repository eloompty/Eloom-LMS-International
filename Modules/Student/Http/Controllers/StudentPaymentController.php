<?php

namespace Modules\Student\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentAgent;
use Modules\Student\Entities\StudentPayment;
use Modules\Student\Entities\StudentPaymentCommission;

class StudentPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the student payment.
     * @return Renderable
     */
    public function index($id)
    {
        if (checkRole('student', 'view') == true && feeSetting('payment_module')=='yes') {
            $student = Student::findorfail($id);
            $payments = StudentPayment::where('student_id', $id)->orderBy('id', 'asc')->get();
            return view('student::payment.index', compact('student', 'payments'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new student payment.
     * @return Renderable
     */
    public function create($id)
    {
        if (checkRole('student', 'add') == true && feeSetting('payment_module')=='yes') {
            $student = Student::findorfail($id);
            $student_agent = StudentAgent::where('student_id', $id)->first();
            if ($student_agent) {
                $agent_commission = $student->studentAgent->agent->rate;
                if ($student->studentAgent->branch == NULL) {
                    $branch_commission = 0;
                } else {
                    $branch_commission = $student->studentAgent->branch->rate;
                }
            } else {
                $agent_commission = 0;
                $branch_commission = 0;
            }
            $today = date('Y-m-d');
            $paymentTypes = Payment::where('status', 1)->get();
            $intake_courses = $student->intake->where('status', 1);
            return view('student::payment.create', compact('student', 'today', 'paymentTypes', 'agent_commission', 'branch_commission', 'intake_courses'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created student payment in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $studentAgent = StudentAgent::where('student_id', $id)->first();
        if ($studentAgent == false) {
            $data['net_gross'] = 'net';
            $data['agent_commission_percent'] = 0;
            $data['agent_commission_amount'] = 0;
            $data['gst_percent'] = 0;
            $data['gst'] = 0;
            $data['gst_waiver'] = 1;
            $data['branch_commission_percent'] = 0;
            $data['branch_commission_amount'] = 0;
            $data['student_discount'] = 0;
        }
        if ($data['net_gross'] == 'net') {
            $data['paid_to_agent'] = 1;
        } else {
            $data['paid_to_agent'] = 0;
        }
        if ($data['gst'] == 0) {
            $data['gst_waiver'] = 1;
        } else {
            $data['gst_waiver'] = 0;
        }
        if ($request->has('receipt')) {
            $imageName = time() . '.' . request()->receipt->getClientOriginalExtension();
            request()->receipt->move(public_path('/images/students/payments'), $imageName);
            $data['receipt'] = 'images/students/payments/' . $imageName;
        }
        $data['student_id'] = $id;
        StudentPayment::create($data);
        return redirect()->route('admin.student.payment.index', $id)->with('success', 'Student payment has been added');
    }

    /**
     * Show the specified student payment.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (checkRole('student', 'view') == true && feeSetting('payment_module')=='yes') {
            $payment = StudentPayment::findorfail($id);
            return view('student::payment.show', compact('payment'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified student payment.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('student', 'edit') == true) {
            $payment = StudentPayment::findorfail($id);
            $paymentTypes = Payment::where('status', 1)->get();
            return view('student::payment.edit', compact('payment', 'paymentTypes'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified student payment in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $payment = StudentPayment::findorfail($id);
        $data = $request->all();
        $studentAgent = StudentAgent::where('student_id', $payment->student_id)->first();
        if ($studentAgent == false) {
            $data['net_gross'] = 'net';
            $data['agent_commission_percent'] = 0;
            $data['agent_commission_amount'] = 0;
            $data['gst_percent'] = 0;
            $data['gst'] = 0;
            $data['gst_waiver'] = 1;
            $data['branch_commission_percent'] = 0;
            $data['branch_commission_amount'] = 0;
            $data['student_discount'] = 0;
        }
        if ($data['net_gross'] == 'net') {
            $data['paid_to_agent'] = 1;
        } else {
            $data['paid_to_agent'] = 0;
        }
        if ($data['gst'] == 0) {
            $data['gst_waiver'] = 1;
        } else {
            $data['gst_waiver'] = 0;
        }
        if ($request->has('receipt')) {
            $imageName = time() . '.' . request()->receipt->getClientOriginalExtension();
            request()->receipt->move(public_path('/images/students/payments'), $imageName);
            $data['receipt'] = 'images/students/payments/' . $imageName;
        }
        unset($data['_token'], $data['net_gross']);
        StudentPayment::where('id', $id)->update($data);
        return redirect()->route('admin.student.payment.index', $payment->student_id)->with('success', 'Student payment has been updated');
    }

    /**
     * Remove the specified student payment from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function remainingAmount(Request $request)
    {
        $student_id = $request->student_id;
        $intake_course_id = $request->intake_course_id;
        $payment = StudentPayment::where('student_id', $student_id)->where('intake_course_id', $intake_course_id)->orderby('id', 'desc')->first();
        if ($payment || $payment->remaining_amount > 0) {
            $remaining = $payment->remaining_amount;
        } else {
            $remaining = NULL;
        }
        return response()->json($remaining);
    }

    public function payCommission($id)
    {
        if (checkRole('student', 'view') == true) {
            $payment = StudentPayment::findorfail($id);
            $today = date('Y-m-d');
            return view('student::payment.commission', compact('payment', 'today'));
        } else {
            return abort(404);
        }
    }

    public function commisionPaid(Request $request, $id)
    {
        $data = $request->all();
        if ($request->has('receipt')) {
            $imageName = time() . '.' . request()->receipt->getClientOriginalExtension();
            request()->receipt->move(public_path('/images/students/payments/commissions'), $imageName);
            $data['receipt'] = 'images/students/payments/commissions/' . $imageName;
        }
        $data['student_payment_id'] = $id;
        $payment = StudentPayment::findorfail($id);
        StudentPaymentCommission::create($data);
        return redirect()->route('admin.student.payment.index', $payment->student_id)->with('success', 'Agent Commission has been paid');
    }

    public function receipt($id)
    {
        $receipt = StudentPaymentCommission::where('student_payment_id', $id)->first();
        return view('student::payment.receipt', compact('receipt'));
    }

    public function print($id)
    {
        if (checkRole('student', 'view') == true) {
            $payment = StudentPayment::findorfail($id);
            // Instantiate and use the dompdf class
            // $dompdf = new Dompdf();
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            // Load HTML content
            $dompdf->loadHtml(view('student::payment.print', compact('payment')));

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'potrait');

            // Render the HTML as PDF
            $dompdf->render();

            $name = str_replace(' ', '-', userName('Student', $payment->student_id));
            $date = date('d-m-Y');
            $file_name =  $name. '-payment-' . $date . '.pdf';
            // Output the generated PDF (1 = download and 0 = preview)
            $dompdf->stream($file_name, array("Attachment" => 1));
        } else {
            return abort(404);
        }
    }
}
