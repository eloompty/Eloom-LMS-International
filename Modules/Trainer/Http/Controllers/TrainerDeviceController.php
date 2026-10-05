<?php

namespace Modules\Trainer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerDevice;

class TrainerDeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the trainer devices.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer = Trainer::find($id);
        if ($trainer) {
            $devices = TrainerDevice::where('trainer_id', $id)->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' . userName('Trainer', $trainer->id) . ' Device List');
            return view('trainer::device.index', compact('trainer', 'devices'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
