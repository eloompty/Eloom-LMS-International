<?php

namespace Modules\Trainer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Log\Entities\Log;
use Modules\Trainer\Entities\Trainer;

class TrainerLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the trainer logs.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer = Trainer::find($id);
        if ($trainer) {
            $logs = Log::where('user_id', $id)->where('user_type', 'Trainer')->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' . userName('Trainer', $trainer->id) . ' Log List');
            return view('trainer::log.index', compact('trainer', 'logs'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
