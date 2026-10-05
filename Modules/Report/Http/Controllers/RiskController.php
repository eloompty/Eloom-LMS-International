<?php

namespace Modules\Report\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Report\Entities\StudentRiskScore;
use Modules\Report\Services\StudentRiskAnalyzer;
use Modules\Setting\Entities\RiskScoringSetting;
use Modules\Student\Entities\Student;

class RiskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display the risk analysis dashboard.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('student_risk_report', 'view') == true || checkRole('student_report', 'view') == true) {
            activityLog('Admin', 'Opened Student Risk Analysis Dashboard');

            $query = StudentRiskScore::with([
                'student',
                'studentIntakeCourse.intakeCourse.intake',
                'studentIntakeCourse.intakeCourse.course',
            ])->orderBy('overall_score', 'desc');

            if ($request->has('risk_level') && $request->risk_level != '') {
                $query->where('risk_level', $request->risk_level);
            }

            $scores = $query->get();

            $summary = [
                'critical' => StudentRiskScore::where('risk_level', 'critical')->count(),
                'high' => StudentRiskScore::where('risk_level', 'high')->count(),
                'medium' => StudentRiskScore::where('risk_level', 'medium')->count(),
                'low' => StudentRiskScore::where('risk_level', 'low')->count(),
                'total' => StudentRiskScore::count(),
            ];

            $riskLevel = $request->risk_level;
            $settings = RiskScoringSetting::first() ?? new RiskScoringSetting([
                'attendance_weight' => 30,
                'assignment_weight' => 25,
                'grade_weight' => 20,
                'fee_weight' => 15,
                'engagement_weight' => 10,
            ]);

            return view('report::risk.index', compact('scores', 'summary', 'riskLevel', 'settings'));
        } else {
            return abort(404);
        }
    }

    /**
     * Get detailed risk breakdown for a specific student.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (checkRole('student_risk_report', 'view') == true || checkRole('student_report', 'view') == true) {
            $score = StudentRiskScore::with([
                'student',
                'studentIntakeCourse.intakeCourse.course',
            ])->findOrFail($id);

            $course = optional(optional(optional($score->studentIntakeCourse)->intakeCourse)->course);

            return response()->json([
                'student_name' => userName('Student', $score->student_id),
                'student_id_no' => optional($score->student)->id_no,
                'course_name' => $course->course_name ?? 'N/A',
                'overall_score' => $score->overall_score,
                'risk_level' => $score->risk_level,
                'risk_level_label' => $score->risk_level_label,
                'attendance_score' => $score->attendance_score,
                'assignment_score' => $score->assignment_score,
                'grade_score' => $score->grade_score,
                'fee_score' => $score->fee_score,
                'engagement_score' => $score->engagement_score,
                'details' => $score->details ?? [],
                'analyzed_at' => $score->analyzed_at ? dateTimeFormat($score->analyzed_at) : 'Never',
            ]);
        } else {
            return abort(404);
        }
    }

    /**
     * Trigger a manual risk analysis.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function analyze(Request $request)
    {
        if (checkRole('student_risk_report', 'view') == true || checkRole('student_report', 'view') == true) {
            $analyzer = new StudentRiskAnalyzer();

            if ($request->has('student_id') && $request->student_id) {
                $student = Student::findOrFail($request->student_id);
                $analyzer->analyzeStudent($student);
                activityLog('Admin', 'Ran risk analysis for student ID: ' . $request->student_id);
                return redirect()->route('admin.report.risk.index')->with('success', 'Risk analysis completed for ' . userName('Student', $student->id));
            }

            $count = $analyzer->analyzeAll();
            activityLog('Admin', 'Ran risk analysis for all students');
            return redirect()->route('admin.report.risk.index')->with('success', "Risk analysis completed. Analyzed {$count} student records.");
        } else {
            return abort(404);
        }
    }

    /**
     * Export at-risk students as PDF.
     *
     * @param Request $request
     * @return void
     */
    public function export(Request $request)
    {
        if (checkRole('student_risk_report', 'view') == true || checkRole('student_report', 'view') == true) {
            $query = StudentRiskScore::with([
                'student',
                'studentIntakeCourse.intakeCourse.intake',
                'studentIntakeCourse.intakeCourse.course',
            ])->orderBy('overall_score', 'desc');

            if ($request->has('risk_level') && $request->risk_level != '') {
                $query->where('risk_level', $request->risk_level);
            }

            $scores = $query->get();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $dompdf->loadHtml(view('report::risk.export', compact('scores')));
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            activityLog('Admin', 'Exported Student Risk Analysis Report');
            $dompdf->stream('Student_Risk_Analysis_Report.pdf', ['Attachment' => 1]);
        } else {
            abort(404);
        }
    }
}
