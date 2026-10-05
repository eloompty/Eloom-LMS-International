<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentDevice;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the agent device.
     * @return Renderable
     */
    public function index($id)
    {
        $agent = Agent::find($id);
        if ($agent && checkRole('agent', 'view') == true) {
            $devices = AgentDevice::where('agent_id', $id)->get();
            activityLog('Admin', 'Opened ' .$agent->name . ' Device List');
            return view('agent::device.index', compact('agent', 'devices'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
