<?php

namespace Modules\Agent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Student\Entities\StudentAgent;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the agent student list.
     * @return Renderable
     */
    public function index($id)
    {
        $agent = Agent::find($id);
        if (checkRole('agent', 'view') == true && $agent) {
            activityLog('Admin', 'Opened Agent Student List');
            $students = StudentAgent::with(['student'])->where(function ($query) {
                $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 1));
            })->where('agent_id', $id)->orderBy('id', 'desc')->get();
            return view('agent::enrolledstudent.index', compact('students', 'agent'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
