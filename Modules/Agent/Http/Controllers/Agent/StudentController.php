<?php

namespace Modules\Agent\Http\Controllers\Agent;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Student\Entities\StudentAgent;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $agent_id = Auth::guard('agent')->user()->id;
        $students = StudentAgent::with(['student'])->where(function ($query){
            $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 1));
        })->where('agent_id', $agent_id)->whereIn('status', [0, 1])->orderBy('id', 'desc')->get();
        activityLog('Agent', 'Opened Enrolled Student Menu from web');
        return view('agent::agent.enrolledstudent.index', compact('students'))->with('no', 1);
    }
}
