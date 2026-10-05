<?php

namespace Modules\Report\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Agent\Entities\Agent;
use Modules\Attendance\Entities\Attendance;
use Modules\Report\Entities\StudentRiskScore;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallmentPayment;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        if (checkRole('analytics_dashboard', 'view') != true) {
            return abort(404);
        }

        activityLog('Admin', 'Opened Analytics Dashboard');

        $riskSummary = $this->riskSummary();
        $kpis = $this->buildKpis($riskSummary);
        [$enrollmentMonths, $enrollmentCounts] = $this->enrollmentTrend();
        [$revenueMonths, $revenueTotals] = $this->revenueTrend();
        [$agentLabels, $agentCounts] = $this->topAgents();

        return view('report::analytics.index', compact(
            'kpis',
            'enrollmentMonths',
            'enrollmentCounts',
            'revenueMonths',
            'revenueTotals',
            'riskSummary',
            'agentLabels',
            'agentCounts'
        ));
    }

    public function exportPdf()
    {
        if (checkRole('analytics_dashboard', 'view') != true) {
            return abort(404);
        }

        $riskSummary = $this->riskSummary();
        $kpis = $this->buildKpis($riskSummary);
        [$revenueMonths, $revenueTotals] = $this->revenueTrend();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml(view('report::analytics.export-pdf', compact('kpis', 'revenueMonths', 'revenueTotals', 'riskSummary')));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        activityLog('Admin', 'Exported Analytics Dashboard PDF');
        $dompdf->stream('Analytics_Dashboard.pdf', ['Attachment' => 1]);
    }

    public function exportCsv()
    {
        if (checkRole('analytics_dashboard', 'view') != true) {
            return abort(404);
        }

        $riskSummary = $this->riskSummary();
        $kpis = $this->buildKpis($riskSummary);
        [$revenueMonths, $revenueTotals] = $this->revenueTrend();

        activityLog('Admin', 'Exported Analytics Dashboard CSV');

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Analytics_Dashboard.csv"',
        ];

        return response()->stream(function () use ($kpis, $revenueMonths, $revenueTotals, $riskSummary) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Analytics Dashboard — ' . now()->format('Y-m-d')]);
            fputcsv($handle, []);
            fputcsv($handle, ['KPI', 'Value']);
            fputcsv($handle, ['Total Active Enrollments', $kpis['total_enrollments']]);
            fputcsv($handle, ['New Enrollments This Month', $kpis['new_enrollments_month']]);
            fputcsv($handle, ['YTD Revenue', number_format($kpis['ytd_revenue'], 2)]);
            fputcsv($handle, ['Outstanding Fees (Overdue)', number_format($kpis['total_outstanding'], 2)]);
            fputcsv($handle, ['Overall Attendance Rate (30 days)', $kpis['attendance_rate'] . '%']);
            fputcsv($handle, []);
            fputcsv($handle, ['At-Risk Summary', '']);
            fputcsv($handle, ['Critical', $kpis['risk_critical']]);
            fputcsv($handle, ['High', $kpis['risk_high']]);
            fputcsv($handle, ['Medium', $kpis['risk_medium']]);
            fputcsv($handle, ['Low', $kpis['risk_low']]);
            fputcsv($handle, []);
            fputcsv($handle, ['Monthly Revenue Trend', '']);
            fputcsv($handle, ['Month', 'Revenue']);
            foreach ($revenueMonths as $i => $month) {
                fputcsv($handle, [$month, number_format($revenueTotals[$i] ?? 0, 2)]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    private function buildKpis(array $riskSummary): array
    {
        $totalEnrollments = StudentIntakeCourse::where('is_enrolled', 1)->where('status', 1)->count();

        $newEnrollmentsMonth = StudentIntakeCourse::where('is_enrolled', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $ytdRevenue = StudentIntakeCourseFeeInstallmentPayment::whereYear('paid_date', now()->year)
            ->sum('paid_amount');

        $totalOutstanding = StudentIntakeCourseFeeInstallment::where('status', 1)
            ->where('parent_id', 0)
            ->whereDate('due_date', '<', today())
            ->sum('amount');

        $since30Days = Carbon::now()->subDays(30);
        $attendanceBase = Attendance::where('date', '>=', $since30Days)->whereNull('intake_subject_id');
        $attendanceTotal = (clone $attendanceBase)->count();
        $attendancePresent = (clone $attendanceBase)->where('status', 1)->count();
        $attendanceRate = $attendanceTotal > 0 ? round(($attendancePresent / $attendanceTotal) * 100, 1) : 0;

        return [
            'total_enrollments'     => $totalEnrollments,
            'new_enrollments_month' => $newEnrollmentsMonth,
            'ytd_revenue'           => $ytdRevenue,
            'total_outstanding'     => $totalOutstanding,
            'attendance_rate'       => $attendanceRate,
            'risk_critical'         => $riskSummary['critical'],
            'risk_high'             => $riskSummary['high'],
            'risk_medium'           => $riskSummary['medium'],
            'risk_low'              => $riskSummary['low'],
        ];
    }

    private function enrollmentTrend(): array
    {
        $since = Carbon::now()->subMonths(12)->startOfMonth();
        $rows = StudentIntakeCourse::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('is_enrolled', 1)
            ->where('created_at', '>=', $since)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        return $this->fillMonthSlots($rows, 12);
    }

    private function revenueTrend(): array
    {
        $since = Carbon::now()->subMonths(12)->startOfMonth();
        $rows = StudentIntakeCourseFeeInstallmentPayment::selectRaw("DATE_FORMAT(paid_date, '%Y-%m') as month, SUM(paid_amount) as total")
            ->where('paid_date', '>=', $since)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return $this->fillMonthSlots($rows, 12);
    }

    private function fillMonthSlots($rows, int $count): array
    {
        $months = [];
        $values = [];
        for ($i = $count - 1; $i >= 0; $i--) {
            $key = Carbon::now()->subMonths($i)->format('Y-m');
            $months[] = Carbon::now()->subMonths($i)->format('M Y');
            $values[] = (int) ($rows[$key] ?? 0);
        }
        return [$months, $values];
    }

    private function riskSummary(): array
    {
        $raw = StudentRiskScore::selectRaw('risk_level, COUNT(*) as count')
            ->groupBy('risk_level')
            ->pluck('count', 'risk_level')
            ->toArray();

        return [
            'critical' => $raw['critical'] ?? 0,
            'high'     => $raw['high'] ?? 0,
            'medium'   => $raw['medium'] ?? 0,
            'low'      => $raw['low'] ?? 0,
        ];
    }

    private function topAgents(): array
    {
        $topAgents = Student::join('student_agents', 'student_agents.student_id', '=', 'students.id')
            ->select(DB::raw('count(*) as count, agent_id'))
            ->groupBy('agent_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        $agentMap = Agent::whereIn('id', $topAgents->pluck('agent_id')->filter())
            ->get()
            ->keyBy('id');

        $labels = [];
        $counts = [];

        foreach ($topAgents as $row) {
            $agent = $agentMap->get($row->agent_id);
            $labels[] = ($agent && $agent->company_name) ? acronym($agent->company_name) : 'N/A';
            $counts[] = $row->count;
        }

        return [$labels, $counts];
    }
}
