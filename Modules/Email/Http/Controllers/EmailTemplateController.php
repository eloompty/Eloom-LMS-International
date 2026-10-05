<?php

namespace Modules\Email\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Email\Entities\EmailTemplate;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the Email Template.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('email_template', 'view') == true) {
            activityLog('Admin', 'Opened Email Template List');
            $templates = EmailTemplate::orderby('id', 'desc')->get();
            return view('email::template.index', compact('templates'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new Email Template.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('email_template', 'add') == true) {
            activityLog('Admin', 'Opened create Email Template Page');
            return view('email::template.create');
        } else {
            return redirect()->route('admin.email.template.index')->with('failure', 'This user does not have permission to add Email');
        }
    }

    /**
     * Store a newly created Email Template in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $template = EmailTemplate::create($data);
        activityLog('Admin', $template->title . ' Email created');
        return redirect()->route('admin.email.template.index')->with('success', 'Email has been added successfully');
    }

    /**
     * Show the form for editing the specified Email Template.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('email_template', 'edit') == true) {
            $template = EmailTemplate::findorfail($id);
            activityLog('Admin', $template->name .  'edit page opened');
            return view('email::template.edit', compact('template'));
        } else {
            return redirect()->route('admin.email.template.index')->with('failure', 'This user does not have permission to edit Email');
        }
    }

    /**
     * Update the specified Email Template in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $template = EmailTemplate::where('id', $id)->first();
        $template->update($data);
        activityLog('Admin', $template->title . ' updated');
        return redirect()->route('admin.email.template.index')->with('success', 'Email has been updated successfully');
    }

    /**
     * Remove the specified Email Template from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('email_template', 'delete') == true) {
            $template = EmailTemplate::where('id', $id)->first();
            $template->update(['status' => 2]);
            activityLog('Admin', 'Status of ' . $template->title . ' updated to deleted');
            return redirect()->back()->with('success', 'Email deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Email');
        }
    }
}
