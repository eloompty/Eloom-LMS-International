<?php

namespace Modules\AgentBranchUser\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\AgentBranchUser\Entities\AgentBranchUser;
use Modules\Student\Entities\StudentAgent;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent_branch_user');
    }

    /* Agent Branch User Dashboard */
    public function dashboard()
    {
        activityLog('Agent Branch User', 'Opened Agent Branch User Dashboard from web');
        $id = Auth::guard('agent_branch_user')->user()->id;
        $total_student = StudentAgent::with(['student'])->where(function ($query) {
            $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 0));
        })->where('user_id', $id)->count();
        $total_enrolled_student = StudentAgent::with(['student'])->where(function ($query) {
            $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 1));
        })->where('user_id', $id)->count();
        return view('agentbranchuser::user.dashboard.dashboard', compact('total_student', 'total_enrolled_student'));
    }

    /* Agent Branch User Profile */
    public function profile()
    {
        activityLog('Agent Branch User', 'Opened Agent Branch User Profile from web');
        return view('agentbranchuser::user.profile');
    }

    /* Change Agent Branch User Password */
    public function changePassword()
    {
        activityLog('Agent Branch User', 'Opened Change Password from web');
        return view('agentbranchuser::user.password.change');
    }

    /* Update Agent Branch User Password */
    public function fillPassword(Request $request)
    {
        $agent_branch_user = Auth::guard('agent_branch_user')->user();
        // Check current password matches from database
        $current = Hash::check($request->current_password, $agent_branch_user->password);
        if ($current == true) {
            if ($request->new_password == $request->confirm_password) {
                // Check new password matches from database
                $new = Hash::check($request->new_password, $agent_branch_user->password);
                if ($new == true) {
                    activityLog('Agent Branch User', 'Update Password Failed');
                    return redirect()->back()->with('failure', 'New password cannot be as current password');
                } else {
                    $change = AgentBranchUser::find($agent_branch_user->id);
                    $change->password = Hash::make($request->new_password);
                    $change->save();
                    activityLog('Agent Branch User', 'Updated Password from Web');
                    return redirect()->back()->with('success', 'Password has been updated');
                }
            } else {
                return redirect()->back()->with('failure', 'New Password and Current Password must be same');
            }
        } else {
            activityLog('Agent Branch User', 'Update Password Failed');
            return redirect()->back()->with('failure', 'Wrong Password');
        }
    }
}
