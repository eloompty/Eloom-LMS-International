<?php

namespace Modules\CRM\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CRM\Entities\Lead;

class CRMController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('lead', 'view') == true) {
            activityLog('Admin', 'Opened Lead List');
            $leads = Lead::orderby('id', 'desc')->get();
            return view('crm::index', compact('leads'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new Lead.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('lead', 'add') == true) {
            activityLog('Admin', 'Opened create Lead Page');
            return view('crm::create');
        } else {
            return redirect()->route('admin.lead.index')->with('failure', 'This user does not have permission to add Lead');
        }
    }

    /**
     * Store a newly created Lead in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $lead = Lead::create($data);
        activityLog('Admin', 'lead_id: '. $lead->id . ' Lead created');
        return redirect()->route('admin.lead.index')->with('success', 'Lead has been added successfully');
    }

    /**
     * Show the form for editing the specified Lead.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('lead', 'edit') == true) {
            $lead = Lead::findorfail($id);
            activityLog('Admin', 'lead_id: '. $lead->id .  ' edit page opened');
            return view('crm::edit', compact('lead'));
        } else {
            return redirect()->route('admin.lead.index')->with('failure', 'This user does not have permission to edit Lead');
        }
    }

    /**
     * Update the specified Lead in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $lead = Lead::where('id', $id)->first();
        $lead->update($data);
        activityLog('Admin', 'lead_id: '. $lead->id . ' updated');
        return redirect()->route('admin.lead.index')->with('success', 'Lead has been updated successfully');
    }

    /**
     * Remove the specified Lead from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('lead', 'delete') == true) {
            $lead = Lead::where('id', $id)->first();
            $lead->update(['status' => 2]);
            activityLog('Admin', 'Status of lead_id' . $lead->id . ' updated to deleted');
            return redirect()->back()->with('success', 'Lead deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete Lead');
        }
    }
}
