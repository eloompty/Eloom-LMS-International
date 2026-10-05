<?php

namespace Modules\Report\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Agent;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Report\Entities\ReportTemplate;
use Modules\Student\Entities\StudentAgent;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the agent report.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('agent_report', 'view') == true) {
            $data = $request->all();
            $from = NULL;
            $to = NULL;
            $agent = NULL;
            $agent_list = Agent::where('status', 1)->get();
            if (isset($data['agent'])) {
                $agent = $agent_ids[] = $data['agent'];
            } else {
                $all_agents = Agent::where('status', 1)->get();
                foreach ($all_agents as $key => $value) {
                    $agent_ids[] = $value->id;
                }
            }
            $all_agents = StudentAgent::join('students', 'students.id', '=', 'student_agents.student_id')
                ->join('student_intake_courses', 'student_intake_courses.student_id', '=', 'students.id')
                ->whereIn('students.status', [0, 1])
                ->where('students.is_enrolled', 1)
                ->whereIn('student_agents.agent_id', $agent_ids)
                ->select('student_agents.*', 'student_intake_courses.intake_course_id');
            if (isset($data['from']) || isset($data['to'])) {
                if ($data['from'] == NULL) {
                    $intakeCourseFrom = IntakeCourse::orderBy('starting_date', 'asc')->first();
                    $data['from'] = $intakeCourseFrom->starting_date;
                }
                if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                $from = $data['from'];
                $to = $data['to'];
                $agents = $all_agents->whereBetween('student_intake_courses.starting_date', [$from, $to])
                    ->whereBetween('student_intake_courses.ending_date', [$from, $to])
                    ->get();
            } else {
                $agents = $all_agents->get();
            }
            foreach ($agents as $key => $value) {
                $intakeCourse = IntakeCourse::find($value->intake_course_id);
                $agents[$key]['intake'] = $intakeCourse->intake->name;
                $agents[$key]['course'] = $intakeCourse->course->course_name;
            }
            activityLog('Admin', 'Opened Agent Report Menu');
            return view('report::agent.index', compact('agents', 'from', 'to', 'agent', 'agent_list'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    public function print(Request $request)
    {
        if (checkRole('agent_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['template_id']) || $data['template_id'] == NULL) {
                return redirect()->back()->with('failure', 'Please choose one template');
            } else {
                $from = NULL;
                $to = NULL;
                $agent = NULL;
                $agent_list = Agent::where('status', 1)->get();
                if (isset($data['agent'])) {
                    $agent = $agent_ids[] = $data['agent'];
                } else {
                    $all_agents = Agent::where('status', 1)->get();
                    foreach ($all_agents as $key => $value) {
                        $agent_ids[] = $value->id;
                    }
                }
                $all_agents = StudentAgent::join('students', 'students.id', '=', 'student_agents.student_id')
                    ->join('student_intake_courses', 'student_intake_courses.student_id', '=', 'students.id')
                    ->whereIn('students.status', [0, 1])
                    ->where('students.is_enrolled', 1)
                    ->whereIn('student_agents.agent_id', $agent_ids)
                    ->select('student_agents.*', 'student_intake_courses.intake_course_id');
                if (isset($data['from']) || isset($data['to'])) {
                    if ($data['from'] == NULL) {
                        $intakeCourseFrom = IntakeCourse::orderBy('starting_date', 'asc')->first();
                        $data['from'] = $intakeCourseFrom->starting_date;
                    }
                    if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                    $from = $data['from'];
                    $to = $data['to'];
                    $agents = $all_agents->whereBetween('student_intake_courses.starting_date', [$from, $to])
                        ->whereBetween('student_intake_courses.ending_date', [$from, $to])
                        ->get();
                } else {
                    $agents = $all_agents->get();
                }
                foreach ($agents as $key => $value) {
                    $intakeCourse = IntakeCourse::find($value->intake_course_id);
                    $agents[$key]['intake'] = $intakeCourse->intake->name;
                    $agents[$key]['course'] = $intakeCourse->course->course_name;
                }
                $template = ReportTemplate::findorfail($data['template_id']);
                $no = 1;

                $options = new Options();
                $options->set('isRemoteEnabled', true);
                $options->set('isHtml5ParserEnabled', true);
                $dompdf = new Dompdf($options);

                $placeholders = [
                    '{{school_name}}'  => getTitle(),
                    '{{report_date}}'  => date('d/m/Y'),
                    '{{generated_by}}' => userName('Admin', auth('user')->user()->id),
                    '{{report_title}}' => 'Agent Report',
                ];
                $dompdf->loadHtml(view('report::agent.show', compact('agents', 'from', 'to', 'template', 'no', 'placeholders')));
                $file_name = 'Agent_Report.pdf';
                // (Optional) Setup the paper size and orientation  
                $dompdf->setPaper('A4', 'potrait');

                // Render the HTML as PDF  
                $dompdf->render();


                // Output the generated PDF (1 = download and 0 = preview) 
                $dompdf->stream($file_name, array("Attachment" => 1));
            }
        } else {
            return abort(404);
        }
    }

    public function show(Request $request)
    {
        if (checkRole('agent_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['template_id']) || $data['template_id'] == NULL) {
                return redirect()->back()->with('failure', 'Please choose one template');
            } else {
                $template = ReportTemplate::findorfail($data['template_id']);
                $from = NULL;
                $to = NULL;
                $agent = NULL;
                $agent_list = Agent::where('status', 1)->get();
                if (isset($data['agent'])) {
                    $agent = $agent_ids[] = $data['agent'];
                } else {
                    $all_agents = Agent::where('status', 1)->get();
                    foreach ($all_agents as $key => $value) {
                        $agent_ids[] = $value->id;
                    }
                }
                $all_agents = StudentAgent::join('students', 'students.id', '=', 'student_agents.student_id')
                    ->join('student_intake_courses', 'student_intake_courses.student_id', '=', 'students.id')
                    ->whereIn('students.status', [0, 1])
                    ->where('students.is_enrolled', 1)
                    ->whereIn('student_agents.agent_id', $agent_ids)
                    ->select('student_agents.*', 'student_intake_courses.intake_course_id');
                if (isset($data['from']) || isset($data['to'])) {
                    if ($data['from'] == NULL) {
                        $intakeCourseFrom = IntakeCourse::orderBy('starting_date', 'asc')->first();
                        $data['from'] = $intakeCourseFrom->starting_date;
                    }
                    if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                    $from = $data['from'];
                    $to = $data['to'];
                    $agents = $all_agents->whereBetween('student_intake_courses.starting_date', [$from, $to])
                        ->whereBetween('student_intake_courses.ending_date', [$from, $to])
                        ->get();
                } else {
                    $agents = $all_agents->get();
                }
                foreach ($agents as $key => $value) {
                    $intakeCourse = IntakeCourse::find($value->intake_course_id);
                    $agents[$key]['intake'] = $intakeCourse->intake->name;
                    $agents[$key]['course'] = $intakeCourse->course->course_name;
                }
                $placeholders = [
                    '{{school_name}}'  => getTitle(),
                    '{{report_date}}'  => date('d/m/Y'),
                    '{{generated_by}}' => userName('Admin', auth('user')->user()->id),
                    '{{report_title}}' => 'Agent Report',
                ];
                activityLog('Admin', 'Opened Agent Report Show Menu');
                return view('report::agent.show', compact('agents', 'from', 'to', 'template', 'placeholders'))->with('no', 1);
            }
        } else {
            return abort(404);
        }
    }
}
