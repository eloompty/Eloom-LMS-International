<?php

namespace Modules\Agent\Http\Controllers\Agent;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Agent\Entities\AgentBranch;
use Modules\AgentBranchUser\Entities\AgentBranchUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $agent_id = Auth::guard('agent')->user()->id;
        $users = AgentBranchUser::join('agent_branches', 'agent_branches.id', '=', 'agent_branch_users.branch_id')
            ->select('agent_branch_users.*')
            ->where('agent_branches.agent_id', $agent_id)
            ->whereIn('agent_branches.status', [0, 1])
            ->orderBy('agent_branch_users.id', 'desc')
            ->get();
        activityLog('Agent', 'Opened User Menu from web');
        return view('agent::agent.user.index', compact('users'))->with('no', 1);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $agent_id = Auth::guard('agent')->user()->id;
        activityLog('Agent', 'Opened create user page from web');
        $branches = AgentBranch::where('agent_id', $agent_id)->where('status', 1)->pluck('name', 'id');
        return view('agent::agent.user.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/agents/branches/users'), $imageName);
            $data['image'] = 'images/agents/branches/users/' . $imageName;
        }
        $data['password'] = Hash::make($data['password']);
        AgentBranchUser::create($data);
        activityLog('Agent', $data['name'] . ' user created from web');
        return redirect()->route('agent.user.index')->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $user = AgentBranchUser::find($id);
        $agent_id = Auth::guard('agent')->user()->id;
        if ($user && $user->branch->agent_id == $agent_id) {
            activityLog('Agent', $user->name . ' user edit page opened from web');
            $branches = AgentBranch::where('agent_id', $agent_id)->where('status', 1)->pluck('name', 'id');
            return view('agent::agent.user.edit', compact('user', 'branches'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/agents/branches/users'), $imageName);
            $data['image'] = 'images/agents/branches/users/' . $imageName;
        }
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $user = AgentBranchUser::where('id', $id)->first();
        $user->update($data);
        activityLog('Agent', $user->name . ' user updated from web');
        return redirect()->route('agent.user.index')->with('success', 'Branch updated successfully');
    }
}
