<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }
    
    public function menu()
    {
        if (checkRole('agent_report', 'view') == true || checkRole('commission_report', 'view') == true || checkRole('course_completion_report', 'view') == true || checkRole('due_pays_report', 'view') == true || checkRole('fee_received_report', 'view') == true || checkRole('intake_report', 'view') == true || checkRole('student_report', 'view') == true || checkRole('report_template', 'view') == true) {
            activityLog('Admin',  'Opened Report Menu Page');
            return view('report::menu');
        } else {
            return abort(404);
        }
    }
}
