<?php

namespace Modules\Agent\Http\Controllers\Agent;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentBranch;
use Modules\AgentBranchUser\Entities\AgentBranchUser;
use Modules\Student\Entities\StudentAgent;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    /* Agent Dashboard */
    public function dashboard()
    {
        activityLog('Agent', 'Opened Agent Dashboard from web');
        $id = Auth::guard('agent')->user()->id;
        $total_branch = AgentBranch::where('agent_id', $id)->count();
        $total_user = AgentBranchUser::join('agent_branches', 'agent_branches.id', '=', 'agent_branch_users.branch_id')->where('agent_id', $id)->count();
        $total_student = StudentAgent::with(['student'])->where(function ($query) {
            $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 0));
        })->where('agent_id', $id)->count();
        $total_enrolled_student = StudentAgent::with(['student'])->where(function ($query) {
            $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 1));
        })->where('agent_id', $id)->count();
        return view('agent::agent.dashboard.dashboard', compact('total_branch', 'total_user', 'total_student', 'total_enrolled_student'));
    }

    /* Agent Profile */
    public function profile()
    {
        activityLog('Agent', 'Opened Agent Profile from web');
        return view('agent::agent.profile');
    }

    /* Change Agent Password */
    public function changePassword()
    {
        activityLog('Agent', 'Opened Change Password from web');
        return view('agent::agent.password.change');
    }

    /* Update Agent Password */
    public function fillPassword(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        // Check current password matches from database
        $current = Hash::check($request->current_password, $agent->password);
        if ($current == true) {
            if ($request->new_password == $request->confirm_password) {
                // Check new password matches from database
                $new = Hash::check($request->new_password, $agent->password);
                if ($new == true) {
                    activityLog('Agent', 'Update Password Failed');
                    return redirect()->back()->with('failure', 'New password cannot be as current password');
                } else {
                    $change = Agent::find($agent->id);
                    $change->password = Hash::make($request->new_password);
                    $change->save();
                    activityLog('Agent', 'Updated Password from Web');
                    return redirect()->back()->with('success', 'Password has been updated');
                }
            } else {
                return redirect()->back()->with('failure', 'New Password and Current Password must be same');
            }
        } else {
            activityLog('Agent', 'Update Password Failed');
            return redirect()->back()->with('failure', 'Wrong Password');
        }
    }
}
