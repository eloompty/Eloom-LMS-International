<?php

namespace Modules\Report\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Report\Entities\ReportTemplate;
use Modules\Student\Entities\StudentIntakeCourse;

class IntakeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('intake_report', 'view') == true) {
            $data = $request->all();
            $from = NULL;
            $to = NULL;
            if (isset($data['from']) || isset($data['to'])) {
                if ($data['from'] == NULL) {
                    $intakeCourse = IntakeCourse::orderBy('starting_date', 'asc')->first();
                    $data['from'] = $intakeCourse->starting_date;
                }
                if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                $from = $data['from'];
                $to = $data['to'];
                $intakes = IntakeCourse::where('status', 1)->whereBetween('starting_date', [$from, $to])->whereBetween('ending_date', [$from, $to])->get();
            } else {
                $intakes = IntakeCourse::where('status', 1)->get();
            }
            foreach ($intakes as $key => $value) {
                $students = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
                    ->join('intake_courses', 'intake_courses.id', '=', 'student_intake_courses.intake_course_id')
                    ->where('intake_courses.status', 1)
                    ->whereIn('students.status', [0, 1])
                    ->whereIn('student_intake_courses.status', [0, 1])
                    ->where('students.is_enrolled', 1)
                    ->where('student_intake_courses.intake_course_id', $value->id)
                    ->count();
                $student_count[] = $students;
                $intakes[$key]['studentCount'] = $students;
                $unit_count[] = $value->intakeUnit->count();
            }
            $total_student = array_sum($student_count);
            $total_unit = array_sum($unit_count);
            activityLog('Admin', 'Opened Intake Report Menu');
            return view('report::intake.index', compact('intakes', 'from', 'to', 'total_student', 'total_unit'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    public function print(Request $request)
    {
        if (checkRole('intake_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['template_id']) || $data['template_id'] == NULL) {
                return redirect()->back()->with('failure', 'Please choose one template');
            } else {
                $from = NULL;
                $to = NULL;
                if (isset($data['from']) || isset($data['to'])) {
                    if ($data['from'] == NULL) {
                        $intakeCourse = IntakeCourse::orderBy('starting_date', 'asc')->first();
                        $data['from'] = $intakeCourse->starting_date;
                    }
                    if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                    $from = $data['from'];
                    $to = $data['to'];
                    $intakes = IntakeCourse::where('status', 1)->whereBetween('starting_date', [$from, $to])->whereBetween('ending_date', [$from, $to])->get();
                } else {
                    $intakes = IntakeCourse::where('status', 1)->get();
                }
                foreach ($intakes as $key => $value) {
                    $students = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
                        ->join('intake_courses', 'intake_courses.id', '=', 'student_intake_courses.intake_course_id')
                        ->where('intake_courses.status', 1)
                        ->whereIn('students.status', [0, 1])
                        ->whereIn('student_intake_courses.status', [0, 1])
                        ->where('students.is_enrolled', 1)
                        ->where('student_intake_courses.intake_course_id', $value->id)
                        ->count();
                    $student_count[] = $students;
                    $intakes[$key]['studentCount'] = $students;
                    $unit_count[] = $value->intakeUnit->count();
                }
                $total_student = array_sum($student_count);
                $total_unit = array_sum($unit_count);
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
                    '{{report_title}}' => 'Intake Report',
                ];
                $dompdf->loadHtml(view('report::intake.show', compact('intakes', 'from', 'to', 'total_student', 'total_unit', 'no', 'template', 'placeholders')));
                $file_name = 'Intake_Report.pdf';
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
        if (checkRole('intake_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['template_id']) || $data['template_id'] == NULL) {
                return redirect()->back()->with('failure', 'Please choose one template');
            } else {
                $from = NULL;
                $to = NULL;
                if (isset($data['from']) || isset($data['to'])) {
                    if ($data['from'] == NULL) {
                        $intakeCourse = IntakeCourse::orderBy('starting_date', 'asc')->first();
                        $data['from'] = $intakeCourse->starting_date;
                    }
                    if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                    $from = $data['from'];
                    $to = $data['to'];
                    $intakes = IntakeCourse::where('status', 1)->whereBetween('starting_date', [$from, $to])->whereBetween('ending_date', [$from, $to])->get();
                } else {
                    $intakes = IntakeCourse::where('status', 1)->get();
                }
                foreach ($intakes as $key => $value) {
                    $students = StudentIntakeCourse::join('students', 'students.id', 'student_intake_courses.student_id')
                        ->join('intake_courses', 'intake_courses.id', '=', 'student_intake_courses.intake_course_id')
                        ->where('intake_courses.status', 1)
                        ->whereIn('students.status', [0, 1])
                        ->whereIn('student_intake_courses.status', [0, 1])
                        ->where('students.is_enrolled', 1)
                        ->where('student_intake_courses.intake_course_id', $value->id)
                        ->count();
                    $student_count[] = $students;
                    $intakes[$key]['studentCount'] = $students;
                    $unit_count[] = $value->intakeUnit->count();
                }
                $total_student = array_sum($student_count);
                $total_unit = array_sum($unit_count);
                $template = ReportTemplate::findorfail($data['template_id']);
                $placeholders = [
                    '{{school_name}}'  => getTitle(),
                    '{{report_date}}'  => date('d/m/Y'),
                    '{{generated_by}}' => userName('Admin', auth('user')->user()->id),
                    '{{report_title}}' => 'Intake Report',
                ];
                activityLog('Admin', 'Opened Intake Report Show Menu');
                return view('report::intake.show', compact('intakes', 'from', 'to', 'total_student', 'total_unit', 'template', 'placeholders'))->with('no', 1);
            }
        } else {
            return abort(404);
        }
    }
}
