<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentBranch;
use Modules\Country\Entities\Country;
use Modules\Student\Entities\StudentAgent;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the agent.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('agent', 'view') == true) {
            activityLog('Admin', 'Opened Agent Menu');
            $agents = Agent::orderBy('id', 'desc')->get();
            foreach ($agents as $key => $value) {
                $agents[$key]['applied_count'] = StudentAgent::with(['student'])->where(function ($query) {
                    $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 0));
                })->where('agent_id', $value->id)->count();
                $agents[$key]['enrolled_count'] = StudentAgent::with(['student'])->where(function ($query) {
                    $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 1));
                })->where('agent_id', $value->id)->count();
            }
            return view('agent::index', compact('agents'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new agent.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('agent', 'add') == true) {
            activityLog('Admin', 'Opened Create Agent Page');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            return view('agent::create', compact('countries'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created agent in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/agents'), $imageName);
            $data['image'] = 'images/agents/' . $imageName;
        }
        $data['password'] = Hash::make($data['password']);
        $data['admin_id'] = Auth::guard('user')->user()->id;
        $agent = Agent::create($data);
        $data['agent_id'] = $agent->id;
        $data['phone'] = $agent->mobile;
        AgentBranch::create($data);
        activityLog('Admin', $data['name'] .' agent created');
        return redirect()->route('admin.agent.index')->with('success', 'Agent created successfully');
    }

    /**
     * Show the form for editing the specified agent.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('agent', 'edit') == true) {
            $agent = Agent::find($id);
            $countries = Country::where('status', 1)->pluck('name', 'id');
            activityLog('Admin', 'Opened '. $agent->name .' edit page');
            return view('agent::edit', compact('agent', 'countries'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified agent in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $agent = Agent::where('id', $id)->first();
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/agents'), $imageName);
            $data['image'] = 'images/agents/' . $imageName;
        }
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $agent->update($data);
        activityLog('Admin', $agent->name . ' agent updated');
        return redirect()->route('admin.agent.index')->with('success', 'Agent updated successfully');
    }

    /**
     * Remove the specified agent from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('agent', 'delete') == true) {
            Agent::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Agent has been deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete agent');
        }
    }
}
