<?php

namespace Modules\AgentBranchUser\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AgentBranchUser\Entities\AgentBranchUser;
use Modules\AgentBranchUser\Entities\AgentBranchUserDevice;

class DeviceController extends Controller
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
            $devices = AgentBranchUserDevice::where('agent_branch_user_id', $id)->get();
            activityLog('Admin', 'Opened ' .$agent_branch_user->name . ' Device List');
            return view('agentbranchuser::device.index', compact('agent_branch_user', 'devices'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
