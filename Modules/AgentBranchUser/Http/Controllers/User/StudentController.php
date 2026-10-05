<?php

namespace Modules\AgentBranchUser\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Student\Entities\StudentAgent;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent_branch_user');
    }

    /**
     * Display a listing of the enrolled student.
     * @return Renderable
     */
    public function index()
    {
        $user_id = Auth::guard('agent_branch_user')->user()->id;
        $students = StudentAgent::with(['student'])->where(function ($query){
            $query->whereHas('student', fn ($q) => $q->where('is_enrolled', 1));
        })->where('user_id', $user_id)->whereIn('status', [0, 1])->orderBy('id', 'desc')->get();
        activityLog('Agent Branch User', 'Opened enrolled student page from web');
        return view('agentbranchuser::user.enrolledstudent.index', compact('students'))->with('no', 1);
    }
}
