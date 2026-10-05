<?php

namespace Modules\AgentBranchUser\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AgentBranchUser\Entities\AgentBranchUser;
use Modules\Log\Entities\Log;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $agent_branch_user = AgentBranchUser::find($id);
        if ($agent_branch_user) {
            $logs = Log::where('user_id', $id)->where('user_type', 'Agent Branch User')->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' .$agent_branch_user->name . ' Log List');
            return view('agentbranchuser::log.index', compact('agent_branch_user', 'logs'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
