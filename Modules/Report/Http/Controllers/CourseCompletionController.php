<?php

namespace Modules\Report\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Report\Entities\ReportTemplate;
use Modules\Student\Entities\StudentIntakeCourse;

class CourseCompletionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the course completion report.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('course_completion_report', 'view') == true) {
            $data = $request->all();
            $from = NULL;
            $to = NULL;
            $all_students = StudentIntakeCourse::join('students', 'students.id', '=', 'student_intake_courses.student_id')
                ->whereIn('students.status', [0, 1])
                ->whereIn('student_intake_courses.status', [0,1])
                ->where('students.is_enrolled', 1)
                ->select('student_intake_courses.*');
            if (isset($data['from']) || isset($data['to'])) {
                if ($data['from'] == NULL) {
                    $studentIntakeCourse = StudentIntakeCourse::orderBy('starting_date', 'asc')->first();
                    $data['from'] = $studentIntakeCourse->starting_date;
                }
                if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                $from = $data['from'];
                $to = $data['to'];
                $students = $all_students->whereBetween('student_intake_courses.ending_date', [$from, $to])
                    ->orderBy('student_intake_courses.id', 'desc')->get();
            } else {
                $students = $all_students->orderBy('student_intake_courses.id', 'desc')->get();
            }
            activityLog('Admin', 'Opened Course Completion Report Menu');
            return view('report::coursecompletion.index', compact('students', 'from', 'to'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    public function print(Request $request)
    {
        if (checkRole('student_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['template_id']) || $data['template_id'] == NULL) {
                return redirect()->back()->with('failure', 'Please choose one template');
            } else {
                $from = NULL;
                $to = NULL;
                $all_students = StudentIntakeCourse::join('students', 'students.id', '=', 'student_intake_courses.student_id')
                    ->whereIn('students.status', [0, 1])
                    ->whereIn('student_intake_courses.status', [0,1])
                    ->where('students.is_enrolled', 1)
                    ->select('student_intake_courses.*');
                if (isset($data['from']) || isset($data['to'])) {
                    if ($data['from'] == NULL) {
                        $studentIntakeCourse = StudentIntakeCourse::orderBy('starting_date', 'asc')->first();
                        $data['from'] = $studentIntakeCourse->starting_date;
                    }
                    if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                    $from = $data['from'];
                    $to = $data['to'];
                    $students = $all_students->whereBetween('student_intake_courses.ending_date', [$from, $to])
                        ->orderBy('student_intake_courses.id', 'desc')->get();
                } else {
                    $students = $all_students->orderBy('student_intake_courses.id', 'desc')->get();
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
                    '{{report_title}}' => 'Course Completion Report',
                ];
                $dompdf->loadHtml(view('report::coursecompletion.show', compact('students', 'from', 'to', 'template', 'no', 'placeholders')));
                $file_name = 'Course_Completion_Report.pdf';
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
        if (checkRole('student_report', 'view') == true) {
            $data = $request->all();
            if (!isset($data['template_id']) || $data['template_id'] == NULL) {
                return redirect()->back()->with('failure', 'Please choose one template');
            } else {
                $template = ReportTemplate::findorfail($data['template_id']);
                $from = NULL;
                $to = NULL;
                $all_students = StudentIntakeCourse::join('students', 'students.id', '=', 'student_intake_courses.student_id')
                    ->whereIn('students.status', [0, 1])
                    ->whereIn('student_intake_courses.status', [0,1])
                    ->where('students.is_enrolled', 1)
                    ->select('student_intake_courses.*');
                if (isset($data['from']) || isset($data['to'])) {
                    if ($data['from'] == NULL) {
                        $studentIntakeCourse = StudentIntakeCourse::orderBy('starting_date', 'asc')->first();
                        $data['from'] = $studentIntakeCourse->starting_date;
                    }
                    if ($data['to'] == NULL) $data['to'] = date('Y-m-d');
                    $from = $data['from'];
                    $to = $data['to'];
                    $students = $all_students->whereBetween('student_intake_courses.ending_date', [$from, $to])
                        ->orderBy('student_intake_courses.id', 'desc')->get();
                } else {
                    $students = $all_students->orderBy('student_intake_courses.id', 'desc')->get();
                }
                $placeholders = [
                    '{{school_name}}'  => getTitle(),
                    '{{report_date}}'  => date('d/m/Y'),
                    '{{generated_by}}' => userName('Admin', auth('user')->user()->id),
                    '{{report_title}}' => 'Course Completion Report',
                ];
                activityLog('Admin', 'Opened Student Report Show Menu');
                return view('report::coursecompletion.show', compact('students', 'from', 'to', 'template', 'placeholders'))->with('no', 1);
            }
        } else {
            return abort(404);
        }
    }
}
