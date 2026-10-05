<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentOfferTemplate;

class StudentOfferTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the offer letter templates.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('offer_template', 'view') == true) {
            activityLog('Admin', 'Opened Offer Letter Template Menu');
            $templates = StudentOfferTemplate::orderBy('id', 'desc')->get();
            return view('student::offer-template.index', compact('templates'))->with('no', 1);
        }
        return abort(404);
    }

    /**
     * Show the builder for creating a new template.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('offer_template', 'add') == true) {
            activityLog('Admin', 'Opened Offer Letter Template Create Page');
            return view('student::offer-template.create');
        }
        return abort(404);
    }

    /**
     * Store a newly created template.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required',
            'layout' => 'required',
        ]);

        StudentOfferTemplate::create([
            'name'   => $request->name,
            'layout' => $request->layout,
            'status' => $request->status,
        ]);
        activityLog('Admin', $request->name . ' offer letter template created');
        return redirect()->route('admin.offer.template.index')->with('success', 'Offer letter template has been created');
    }

    /**
     * Show the builder for editing the specified template.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('offer_template', 'edit') == true) {
            activityLog('Admin', 'Opened Offer Letter Template Edit Page');
            $template = StudentOfferTemplate::findorfail($id);
            return view('student::offer-template.edit', compact('template'));
        }
        return abort(404);
    }

    /**
     * Update the specified template.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required',
            'layout' => 'required',
        ]);

        $template = StudentOfferTemplate::findorfail($id);
        $template->update([
            'name'   => $request->name,
            'layout' => $request->layout,
            'status' => $request->status,
        ]);
        activityLog('Admin', $request->name . ' offer letter template updated');
        return redirect()->route('admin.offer.template.index')->with('success', 'Offer letter template has been updated');
    }

    /**
     * Remove the specified template.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('offer_template', 'delete') == true) {
            $template = StudentOfferTemplate::findorfail($id);
            activityLog('Admin', $template->name . ' offer letter template deleted');
            $template->delete();
            return redirect()->route('admin.offer.template.index')->with('success', 'Offer letter template has been deleted');
        }
        return abort(404);
    }
}
