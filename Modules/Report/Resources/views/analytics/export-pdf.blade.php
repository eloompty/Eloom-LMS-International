<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Analytics Dashboard Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        h2 { font-size: 13px; margin: 20px 0 6px 0; border-bottom: 2px solid #343a40; padding-bottom: 4px; }
        .subtitle { text-align: center; color: #666; font-size: 12px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th { background-color: #343a40; color: white; padding: 7px 6px; text-align: left; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #dee2e6; font-size: 10px; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .kpi-table td:first-child { font-weight: bold; width: 55%; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <h1>Analytics Dashboard Report</h1>
    <p class="subtitle">Generated on {{ date('d/m/Y H:i') }} &mdash; {{ getTitle() }}</p>

    <h2>Key Performance Indicators</h2>
    <table class="kpi-table">
        <tbody>
            <tr><td>Total Active Enrollments</td><td>{{ number_format($kpis['total_enrollments']) }}</td></tr>
            <tr><td>New Enrollments This Month</td><td>{{ number_format($kpis['new_enrollments_month']) }}</td></tr>
            <tr><td>YTD Revenue</td><td>{{ number_format($kpis['ytd_revenue'], 2) }}</td></tr>
            <tr><td>Outstanding Fees (Overdue)</td><td>{{ number_format($kpis['total_outstanding'], 2) }}</td></tr>
            <tr><td>Attendance Rate (30 days)</td><td>{{ $kpis['attendance_rate'] }}%</td></tr>
        </tbody>
    </table>

    <h2>At-Risk Student Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Risk Level</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Critical</td><td>{{ $riskSummary['critical'] }}</td></tr>
            <tr><td>High</td><td>{{ $riskSummary['high'] }}</td></tr>
            <tr><td>Medium</td><td>{{ $riskSummary['medium'] }}</td></tr>
            <tr><td>Low</td><td>{{ $riskSummary['low'] }}</td></tr>
        </tbody>
    </table>

    <h2>Monthly Revenue (Last 12 Months)</h2>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueMonths as $i => $month)
            <tr>
                <td>{{ $month }}</td>
                <td>{{ number_format($revenueTotals[$i] ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ getTitle() }} &mdash; Analytics Dashboard Report &mdash; {{ date('Y') }}
    </div>
</body>
</html>
