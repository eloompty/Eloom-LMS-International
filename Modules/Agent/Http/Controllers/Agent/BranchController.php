<?php

namespace Modules\Agent\Http\Controllers\Agent;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Agent\Entities\AgentBranch;
use Modules\Country\Entities\Country;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    /**
     * Display a listing of the branch.
     * @return Renderable
     */
    public function index()
    {
        $agent_id = Auth::guard('agent')->user()->id;
        $branches = AgentBranch::where('agent_id', $agent_id)->whereIn('status', [0,1])->orderBy('id', 'desc')->get();
        activityLog('Agent', 'Opened Branch Menu from web');
        return view('agent::agent.branch.index', compact('branches'))->with('no', 1);
    }

    /**
     * Show the form for creating a new branch.
     * @return Renderable
     */
    public function create()
    {
        activityLog('Agent', 'Opened create branch page from web');
        $countries = Country::where('status', 1)->pluck('name', 'id');
        return view('agent::agent.branch.create', compact('countries'));
    }

    /**
     * Store a newly created branch in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['agent_id'] = Auth::guard('agent')->user()->id;
        AgentBranch::create($data);
        activityLog('Agent', $data['name'] . ' branch created from web');
        return redirect()->route('agent.branch.index')->with('success', 'Branch created successfully');
    }

    /**
     * Show the form for editing the specified branch.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $agent_id = Auth::guard('agent')->user()->id;
        $branch = AgentBranch::where('id', $id)->where('agent_id', $agent_id)->where('status', 1)->first();
        if ($branch) {
            $countries = Country::where('status', 1)->pluck('name', 'id');
            activityLog('Agent', $branch->name . ' branch edit page opened from web');
            return view('agent::agent.branch.edit', compact('branch', 'countries'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified branch in storage.
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
        activityLog('Agent', $branch->name . ' branch updated from web');
        return redirect()->route('agent.branch.index')->with('success', 'Branch updated successfully');
    }
}
