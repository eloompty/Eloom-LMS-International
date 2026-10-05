<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentBranch;
use Modules\Country\Entities\Country;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the agent branch.
     * @return Renderable
     */
    public function index($id)
    {
        $agent = Agent::find($id);
        if (checkRole('agent_branch', 'view') == true && $agent) {
            activityLog('Admin', 'Opened ' . $agent->name .' branch list');
            $branches = AgentBranch::where('agent_id', $id)->orderBy('id', 'desc')->get();
            return view('agent::branch.index', compact('branches', 'agent'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new agent branch.
     * @return Renderable
     */
    public function create($id)
    {
        $agent = Agent::find($id);
        if (checkRole('agent_branch', 'add') == true && $agent) {
            $countries = Country::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', 'Opened '. $agent->name . ' add branch page');
            return view('agent::branch.create', compact('agent', 'countries'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created agent branch in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $agent = Agent::find($id);
        if ($agent) {
            $data = $request->all();
            $data['agent_id'] = $id;
            AgentBranch::create($data);
            activityLog('Admin', $data['name'] . ' branch created');
            return redirect()->route('admin.agent.branch.index', $id)->with('success', 'Agent Branch has been added successfully');
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified agent branch.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $branch = AgentBranch::find($id);
        if (checkRole('agent_branch', 'edit') == true && $branch) {
            $countries = Country::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', 'Opened '. $branch->name . ' edit branch page');
            return view('agent::branch.edit', compact('branch', 'countries'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified agent branch in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $branch = AgentBranch::where('id', $id)->first();
        $branch->update($data);
        activityLog('Admin', $data['name'] . ' branch updated');
        return redirect()->route('admin.agent.branch.index', $branch->agent_id)->with('success', 'Agent Branch has been updated successfully');
    }

    /**
     * Remove the specified agent branch from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('agent_branch', 'delete') == true) {
            AgentBranch::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Branch has been deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete branch');
        }
    }
}
