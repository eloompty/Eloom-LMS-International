<?php

namespace Modules\AgentBranchUser\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Agent\Entities\AgentBranch;
use Modules\AgentBranchUser\Entities\AgentBranchUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the agent branch user.
     * @return Renderable
     */
    public function index($id)
    {
        $branch = AgentBranch::find($id);
        if (checkRole('agent_branch_user', 'view') == true && $branch) {
            activityLog('Admin', 'Opened ' . $branch->name .' user list');
            $users = AgentBranchUser::where('branch_id', $id)->orderBy('id', 'desc')->get();
            return view('agentbranchuser::index', compact('users', 'branch'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new agent branch user.
     * @return Renderable
     */
    public function create($id)
    {
        $branch = AgentBranch::find($id);
        if (checkRole('agent_branch_user', 'add') == true && $branch) {
            activityLog('Admin', 'Opened '. $branch->name . ' add user page');
            return view('agentbranchuser::create', compact('branch'));
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created agent branch user in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $branch = AgentBranch::find($id);
        if ($branch) {
            $data = $request->all();
            $data['branch_id'] = $id;
            if ($request->hasfile('image')) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('/images/agents/branches/users'), $imageName);
                $data['image'] = 'images/agents/branches/users/' . $imageName;
            }
            $data['password'] = Hash::make($data['password']);
            AgentBranchUser::create($data);
            return redirect()->route('admin.agent.branch.user.index', $branch->id)->with('success', 'Agent Branch User has been added successfully');
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified agent branch user.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $user = AgentBranchUser::find($id);
        if (checkRole('agent_branch_user', 'edit') == true && $user) {
            activityLog('Admin', 'Opened '. $user->name . ' edit user page');
            return view('agentbranchuser::edit', compact('user'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified agent branch user in storage.
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
        activityLog('Admin', $data['name'] . ' user updated');
        return redirect()->route('admin.agent.branch.user.index', $user->branch_id)->with('success', 'Agent Branch User has been updated successfully');
    }

    /**
     * Remove the specified agent branch user from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('agent_branch_user', 'delete') == true) {
            AgentBranchUser::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'User has been deleted successfully');
        } else {
            return redirect()->back()->with('failure', 'This user does not have permission to delete user');
        }
    }
}
