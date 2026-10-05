<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the payments.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('payment', 'view') == true) {
            activityLog('Admin', 'Opened Payment Menu');
            $payments = Payment::orderBy('id', 'desc')->get();
            return view('payment::index', compact('payments'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new payment.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('payment', 'add') == true) {
            activityLog('Admin', 'Opened Create Payment Page');
            return view('payment::create');
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created payment in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $payment = Payment::create($data);
        activityLog('Admin', $payment->name . ' payment created');
        return redirect()->route('admin.payment.index')->with('success', 'Payment has been added successfully');
    }

    /**
     * Show the form for editing the specified payment.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('payment', 'edit') == true) {
            $payment = Payment::find($id);
            activityLog('Admin', $payment->name .  'edit page opened');
            return view('payment::edit', compact('payment'));
        } else {
            return redirect()->route('admin.payment.index')->with('failure', 'This user does not have permission to edit payment');
        }
    }

    /**
     * Update the specified payment in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        Payment::where('id', $id)->update($data);
        $payment = Payment::find($id);
        activityLog('Admin', $payment->name . ' updated');
        return redirect()->route('admin.payment.index')->with('success', 'Payment has been updated successfully');
    }

    /**
     * Update status of payment  to deleted.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('payment', 'delete') == true) {
            $payment = Payment::where('id', $id)->first();
            $payment->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $payment->name . ' updated to deleted');
            return redirect()->back()->with('success', 'Payment deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete payment');
        }
    }
}
