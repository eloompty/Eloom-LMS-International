<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Log\Entities\Log;
use Modules\Student\Entities\Student;

class StudentLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }
    
    /**
     * Display a listing of the logs.
     * @return Renderable
     */
    public function index($id)
    {
        $student = Student::find($id);
        if ($student) {
            $logs = Log::where('user_id', $id)->where('user_type', 'Student')->orderBy('id', 'desc')->get();
            activityLog('Admin', 'Opened ' . userName('Student', $student->id) . ' Log List');
            return view('student::log.index', compact('student', 'logs'))->with('no', 1);
        } else {
            return abort(404);
        }
    }
}
