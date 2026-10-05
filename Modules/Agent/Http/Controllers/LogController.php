<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Log\Entities\Log;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the agent log.
     * @return Renderable
     */
    public function index($id)
    {
        $agent = Agent::find($id);
        if ($agent && checkRole('agent', 'view') == true) {
            $logs = Log::where('user_id', $id)->where('user_type', 'Agent')->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' .$agent->name . ' Log List');
            return view('agent::log.index', compact('agent', 'logs'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
