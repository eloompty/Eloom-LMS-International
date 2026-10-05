<?php

namespace Modules\Email\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Email\Entities\Email;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the Email.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('email', 'view') == true) {
            activityLog('Admin', 'Opened Email List');
            $emails = Email::orderby('id', 'desc')->get();
            return view('email::index', compact('emails'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new Email.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('email', 'add') == true) {
            activityLog('Admin', 'Opened create EmailPage');
            return view('email::create');
        } else {
            return redirect()->route('admin.email.index')->with('failure', 'This user does not have permission to add Email');
        }
    }

    /**
     * Store a newly created Email in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $email = Email::create($data);
        activityLog('Admin', $email->name . ' Email created');
        return redirect()->route('admin.email.index')->with('success', 'Email has been added successfully');
    }

    /**
     * Show the form for editing the specified Email.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('email', 'edit') == true) {
            $email = Email::findorfail($id);
            activityLog('Admin', $email->name .  'edit page opened');
            return view('email::edit', compact('email'));
        } else {
            return redirect()->route('admin.email.index')->with('failure', 'This user does not have permission to edit Email');
        }
    }

    /**
     * Update the specified Email in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $email = Email::where('id', $id)->first();
        $email->update($data);
        activityLog('Admin', $email->name . ' updated');
        return redirect()->route('admin.email.index')->with('success', 'Email has been updated successfully');
    }

    /**
     * Remove the specified Email from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('email', 'delete') == true) {
            $email = Email::where('id', $id)->first();
            $email->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $email->name . ' updated to deleted');
            return redirect()->back()->with('success', 'Email deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Email');
        }
    }
}
