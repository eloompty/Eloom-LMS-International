<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Assignment\Entities\Assignment;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;

class TrainerAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the assignments.
     * @return Renderable
     */
    public function index()
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $assignments = Assignment::where('assignments.trainer_id', $trainer_id)->where('assignments.status',1)
        ->join('trainer_intakes', 'trainer_intakes.intake_unit_id', '=', 'assignments.intake_unit_id')
        ->select('assignments.*', 'trainer_intakes.id as trainer_intake_id')
        ->orderby('assignments.id', 'desc')
        ->get();
        activityLog('Trainer', 'Opened assignments menu from web');
        return view('trainer::trainer.assignment.index', compact('assignments'))->with('no', 1);
    }
}
