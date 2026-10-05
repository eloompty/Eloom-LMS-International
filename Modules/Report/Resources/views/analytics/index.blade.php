@extends('user::layouts.master')
@section('title', 'Admin | Analytics Dashboard')
@php $userTheme = Auth::guard('user')->user()->theme ?? 'theme2'; @endphp

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $text }}</strong>
</div>
@endif

@if ($userTheme != 'theme3')
{{-- ===== DEFAULT / THEME2 LAYOUT ===== --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-tachometer-alt"></i> Analytics Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.report.menu') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Analytics Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-12 text-right">
                <a href="{{ route('admin.report.analytics.export-pdf') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('admin.report.analytics.export-csv') }}" class="btn btn-success btn-sm ml-1">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($kpis['total_enrollments']) }}</h3>
                        <p>Total Active Enrollments</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-graduate"></i></div>
                    <a href="{{ route('admin.student.index') }}" class="small-box-footer">View Students <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ number_format($kpis['new_enrollments_month']) }}</h3>
                        <p>New Enrollments This Month</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-plus"></i></div>
                    <a href="{{ route('admin.student.index') }}" class="small-box-footer">View Students <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($kpis['ytd_revenue'], 2) }}</h3>
                        <p>YTD Revenue</p>
                    </div>
                    <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    <a href="{{ route('admin.report.fee.index') }}" class="small-box-footer">Fee Report <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($kpis['total_outstanding'], 2) }}</h3>
                        <p>Outstanding Fees (Overdue)</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    <a href="{{ route('admin.report.due.index') }}" class="small-box-footer">Due Report <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $kpis['attendance_rate'] }}%</h3>
                        <p>Attendance Rate (30 days)</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-check"></i></div>
                    <span class="small-box-footer">&nbsp;</span>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $riskSummary['critical'] }}</h3>
                        <p>Critical Risk Students</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <a href="{{ route('admin.report.risk.index', ['risk_level' => 'critical']) }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color: #ff851b; color: white;">
                    <div class="inner">
                        <h3>{{ $riskSummary['high'] }}</h3>
                        <p>High Risk Students</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    <a href="{{ route('admin.report.risk.index', ['risk_level' => 'high']) }}" class="small-box-footer" style="color: rgba(255,255,255,0.8);">View <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $riskSummary['medium'] }}</h3>
                        <p>Medium Risk Students</p>
                    </div>
                    <div class="icon"><i class="fas fa-info-circle"></i></div>
                    <a href="{{ route('admin.report.risk.index', ['risk_level' => 'medium']) }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Enrollment Trend (12 Months)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="enrollmentChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Monthly Revenue Trend (12 Months)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> At-Risk Distribution</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="riskPieChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-users mr-1"></i> Top 5 Agents by Enrollment</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="agentChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@else
{{-- ===== THEME 3: Enterprise Analytics Dashboard ===== --}}
<style>
    .analytics-page-title {
        font-size: 24px;
        font-weight: 600;
        line-height: 1.2;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .analytics-page-title i {
        color: #2563eb;
        font-size: 20px;
    }

    .analytics-subtitle {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
        font-weight: 400;
    }

    .dark-mode .analytics-page-title { color: #f8fafc; }
    .dark-mode .analytics-subtitle   { color: #cbd5e1; }

    .analytics-export-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .analytics-chart-footer {
        padding: 10px 16px;
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        border-top: 1px solid #e5e7eb;
        background: transparent;
    }

    .risk-chart-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        padding: 0 16px 16px;
    }

    .risk-summary-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #fff;
    }

    .risk-pill-dot {
        flex: 0 0 10px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .risk-pill-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        flex: 1;
    }

    .risk-pill-count {
        font-size: 20px;
        font-weight: 600;
        color: #0f172a;
    }

    @media (max-width: 575.98px) {
        .analytics-page-title { font-size: 20px; }
        .analytics-export-bar { justify-content: flex-start; margin-top: 10px; }
        .risk-chart-summary { grid-template-columns: 1fr 1fr; }
    }
</style>

<div class="enterprise-dashboard">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-7">
                    <h1 class="analytics-page-title mb-0">
                        <i class="fas fa-tachometer-alt"></i> Analytics Dashboard
                    </h1>
                    <p class="analytics-subtitle">Live KPIs across enrollment, revenue, attendance, and student risk.</p>
                </div>
                <div class="col-sm-5 d-flex align-items-center justify-content-sm-end mt-2 mt-sm-0" style="gap: 8px; flex-wrap: wrap;">
                    <ol class="breadcrumb float-sm-right mb-0 mr-2" style="background: transparent; padding: 0;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.report.menu') }}">Reports</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                    <div class="analytics-export-bar">
                        <a href="{{ route('admin.report.analytics.export-pdf') }}" class="panel-action">
                            <i class="fas fa-file-pdf" style="color: #dc2626;"></i> PDF
                        </a>
                        <a href="{{ route('admin.report.analytics.export-csv') }}" class="panel-action">
                            <i class="fas fa-file-csv" style="color: #059669;"></i> CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- KPI Metric Cards — Row 1: Enrollment & Revenue --}}
            <div class="row">
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.student.index') }}">
                        <span class="metric-icon metric-blue"><i class="fas fa-user-graduate"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Active Enrollments</span>
                            <span class="metric-value">{{ number_format($kpis['total_enrollments']) }}</span>
                            <span class="metric-note">Currently enrolled students</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.student.index') }}">
                        <span class="metric-icon metric-cyan"><i class="fas fa-user-plus"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">New This Month</span>
                            <span class="metric-value">{{ number_format($kpis['new_enrollments_month']) }}</span>
                            <span class="metric-note">Enrollments in {{ now()->format('M Y') }}</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.report.fee.index') }}">
                        <span class="metric-icon metric-green"><i class="fas fa-dollar-sign"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">YTD Revenue</span>
                            <span class="metric-value">${{ number_format($kpis['ytd_revenue'], 2) }}</span>
                            <span class="metric-note">Collected in {{ now()->year }}</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.report.due.index') }}">
                        <span class="metric-icon metric-red"><i class="fas fa-exclamation-circle"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Overdue Fees</span>
                            <span class="metric-value">${{ number_format($kpis['total_outstanding'], 2) }}</span>
                            <span class="metric-note">Outstanding past-due balance</span>
                        </span>
                    </a>
                </div>
            </div>

            {{-- KPI Metric Cards — Row 2: Attendance & Risk --}}
            <div class="row">
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <span class="metric-card" style="cursor: default;">
                        <span class="metric-icon metric-amber"><i class="fas fa-calendar-check"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Attendance (30 days)</span>
                            <span class="metric-value">{{ $kpis['attendance_rate'] }}%</span>
                            <span class="metric-note">Overall campus attendance rate</span>
                        </span>
                    </span>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.report.risk.index', ['risk_level' => 'critical']) }}">
                        <span class="metric-icon metric-red"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Critical Risk</span>
                            <span class="metric-value">{{ $riskSummary['critical'] }}</span>
                            <span class="metric-note">Requires immediate intervention</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.report.risk.index', ['risk_level' => 'high']) }}">
                        <span class="metric-icon metric-amber"><i class="fas fa-fire"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">High Risk</span>
                            <span class="metric-value">{{ $riskSummary['high'] }}</span>
                            <span class="metric-note">Close monitoring required</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.report.risk.index', ['risk_level' => 'medium']) }}">
                        <span class="metric-icon metric-slate"><i class="fas fa-info-circle"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Medium Risk</span>
                            <span class="metric-value">{{ $riskSummary['medium'] }}</span>
                            <span class="metric-note">Students to watch this period</span>
                        </span>
                    </a>
                </div>
            </div>

            {{-- Charts Row 1: Enrollment + Revenue --}}
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card dashboard-panel chart-feature h-100">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-line"></i> Enrollment Trend (12 Months)</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-frame">
                                <canvas id="enrollmentChart"></canvas>
                            </div>
                        </div>
                        <div class="analytics-chart-footer">
                            New enrollments per month — all active courses combined
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card dashboard-panel h-100" style="border-color: #d1fae5; background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 46%, #ecfdf5 100%);">
                        <div class="card-header" style="background: transparent; border-bottom-color: #d1fae5;">
                            <h3 class="card-title"><i class="fas fa-chart-bar" style="color: #059669 !important;"></i> Monthly Revenue (12 Months)</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-frame">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                        <div class="analytics-chart-footer" style="border-top-color: #d1fae5;">
                            Revenue collected from fee payments per month
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row 2: Risk Pie + Agent Bar --}}
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card dashboard-panel h-100">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-pie" style="color: #dc2626 !important;"></i> At-Risk Distribution</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-frame">
                                <canvas id="riskPieChart"></canvas>
                            </div>
                        </div>
                        <div class="risk-chart-summary">
                            <div class="risk-summary-pill">
                                <span class="risk-pill-dot" style="background: #dc2626;"></span>
                                <span class="risk-pill-label">Critical</span>
                                <span class="risk-pill-count">{{ $riskSummary['critical'] }}</span>
                            </div>
                            <div class="risk-summary-pill">
                                <span class="risk-pill-dot" style="background: #d97706;"></span>
                                <span class="risk-pill-label">High</span>
                                <span class="risk-pill-count">{{ $riskSummary['high'] }}</span>
                            </div>
                            <div class="risk-summary-pill">
                                <span class="risk-pill-dot" style="background: #f59e0b;"></span>
                                <span class="risk-pill-label">Medium</span>
                                <span class="risk-pill-count">{{ $riskSummary['medium'] }}</span>
                            </div>
                            <div class="risk-summary-pill">
                                <span class="risk-pill-dot" style="background: #059669;"></span>
                                <span class="risk-pill-label">Low</span>
                                <span class="risk-pill-count">{{ $riskSummary['low'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card dashboard-panel h-100">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-users" style="color: #0891b2 !important;"></i> Top 5 Agents by Enrollment</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-frame">
                                <canvas id="agentChart"></canvas>
                            </div>
                        </div>
                        <div class="analytics-chart-footer">
                            Student enrolments attributed to top 5 referral agents
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endif
@endsection

@section('scripts')
@if ($userTheme != 'theme3')
{{-- ===== DEFAULT CHART STYLES ===== --}}
<script>
    if ($('#enrollmentChart').length) {
        new Chart($('#enrollmentChart').get(0).getContext('2d'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($enrollmentMonths); ?>,
                datasets: [{
                    label: 'Enrollments',
                    fill: true,
                    backgroundColor: 'rgba(37, 99, 235, .08)',
                    borderWidth: 3,
                    lineTension: .25,
                    spanGaps: true,
                    borderColor: '#2563eb',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#0891b2',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    data: <?php echo json_encode($enrollmentCounts); ?>
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false },
                scales: {
                    xAxes: [{
                        ticks: { fontColor: '#64748b', fontStyle: 'bold' },
                        gridLines: { display: false, drawBorder: false }
                    }],
                    yAxes: [{
                        ticks: { beginAtZero: true, precision: 0, fontColor: '#64748b', fontStyle: 'bold' },
                        gridLines: { display: true, color: 'rgba(148, 163, 184, .22)', drawBorder: false }
                    }]
                }
            }
        });
    }

    if ($('#revenueChart').length) {
        new Chart($('#revenueChart').get(0).getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($revenueMonths); ?>,
                datasets: [{
                    label: 'Revenue',
                    backgroundColor: 'rgba(5, 150, 105, 0.65)',
                    borderColor: '#059669',
                    borderWidth: 1,
                    data: <?php echo json_encode($revenueTotals); ?>
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        gridLines: { display: true, color: 'rgba(0, 0, 0, .1)', zeroLineColor: 'transparent' },
                        ticks: { beginAtZero: true, fontColor: '#64748b' }
                    }],
                    xAxes: [{
                        gridLines: { display: false },
                        ticks: { fontColor: '#64748b' }
                    }]
                }
            }
        });
    }

    if ($('#riskPieChart').length) {
        new Chart($('#riskPieChart').get(0).getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Critical', 'High', 'Medium', 'Low'],
                datasets: [{
                    data: [
                        {{ $riskSummary['critical'] }},
                        {{ $riskSummary['high'] }},
                        {{ $riskSummary['medium'] }},
                        {{ $riskSummary['low'] }}
                    ],
                    backgroundColor: ['#dc3545', '#ff851b', '#ffc107', '#28a745'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: { display: true, position: 'right' }
            }
        });
    }

    if ($('#agentChart').length) {
        new Chart($('#agentChart').get(0).getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($agentLabels); ?>,
                datasets: [{
                    label: 'Students',
                    backgroundColor: ['#A8A196', '#9BABB8', '#7C96AB', '#413543', '#DBC4F0'],
                    borderWidth: 1,
                    data: <?php echo json_encode($agentCounts); ?>
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        gridLines: { display: true, color: 'rgba(0, 0, 0, .1)', zeroLineColor: 'transparent' },
                        ticks: { beginAtZero: true, precision: 0, fontColor: '#64748b' }
                    }],
                    xAxes: [{
                        gridLines: { display: false },
                        ticks: { fontColor: '#64748b' }
                    }]
                }
            }
        });
    }
</script>

@else
{{-- ===== THEME 3 CHART STYLES ===== --}}
<script>
    var t3FontColor  = '#64748b';
    var t3GridColor  = 'rgba(148, 163, 184, .18)';
    var t3FontWeight = '700';

    function t3AxesDefaults() {
        return {
            xAxes: [{
                ticks: { fontColor: t3FontColor, fontStyle: t3FontWeight, fontSize: 11 },
                gridLines: { display: false, drawBorder: false }
            }],
            yAxes: [{
                ticks: { beginAtZero: true, precision: 0, fontColor: t3FontColor, fontStyle: t3FontWeight, fontSize: 11 },
                gridLines: { display: true, color: t3GridColor, drawBorder: false }
            }]
        };
    }

    // Enrollment Line Chart
    if ($('#enrollmentChart').length) {
        new Chart($('#enrollmentChart').get(0).getContext('2d'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($enrollmentMonths); ?>,
                datasets: [{
                    label: 'Enrollments',
                    fill: true,
                    backgroundColor: 'rgba(37, 99, 235, .07)',
                    borderColor: '#2563eb',
                    borderWidth: 2.5,
                    lineTension: .3,
                    spanGaps: true,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    data: <?php echo json_encode($enrollmentCounts); ?>
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#1e293b',
                    titleFontColor: '#f1f5f9',
                    bodyFontColor: '#cbd5e1',
                    cornerRadius: 6
                },
                scales: t3AxesDefaults()
            }
        });
    }

    // Revenue Bar Chart
    if ($('#revenueChart').length) {
        new Chart($('#revenueChart').get(0).getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($revenueMonths); ?>,
                datasets: [{
                    label: 'Revenue ($)',
                    backgroundColor: 'rgba(5, 150, 105, 0.75)',
                    hoverBackgroundColor: '#059669',
                    borderRadius: 4,
                    borderWidth: 0,
                    data: <?php echo json_encode($revenueTotals); ?>
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#1e293b',
                    titleFontColor: '#f1f5f9',
                    bodyFontColor: '#cbd5e1',
                    cornerRadius: 6,
                    callbacks: {
                        label: function(item) {
                            return ' $' + parseFloat(item.yLabel).toLocaleString('en-AU', {minimumFractionDigits: 2});
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: { fontColor: t3FontColor, fontStyle: t3FontWeight, fontSize: 11 },
                        gridLines: { display: false, drawBorder: false }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: t3FontColor,
                            fontStyle: t3FontWeight,
                            fontSize: 11,
                            callback: function(v) { return '$' + v.toLocaleString(); }
                        },
                        gridLines: { display: true, color: t3GridColor, drawBorder: false }
                    }]
                }
            }
        });
    }

    // Risk Pie Chart
    if ($('#riskPieChart').length) {
        new Chart($('#riskPieChart').get(0).getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Critical', 'High', 'Medium', 'Low'],
                datasets: [{
                    data: [
                        {{ $riskSummary['critical'] }},
                        {{ $riskSummary['high'] }},
                        {{ $riskSummary['medium'] }},
                        {{ $riskSummary['low'] }}
                    ],
                    backgroundColor: ['#dc2626', '#d97706', '#f59e0b', '#059669'],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 6
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutoutPercentage: 60,
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: '#374151',
                        fontStyle: '700',
                        fontSize: 12,
                        padding: 16,
                        usePointStyle: true
                    }
                },
                tooltips: {
                    backgroundColor: '#1e293b',
                    titleFontColor: '#f1f5f9',
                    bodyFontColor: '#cbd5e1',
                    cornerRadius: 6
                }
            }
        });
    }

    // Agent Bar Chart
    if ($('#agentChart').length) {
        new Chart($('#agentChart').get(0).getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($agentLabels); ?>,
                datasets: [{
                    label: 'Students',
                    backgroundColor: ['#2563eb', '#0891b2', '#059669', '#d97706', '#8b5cf6'],
                    hoverBackgroundColor: ['#1d4ed8', '#0e7490', '#047857', '#b45309', '#7c3aed'],
                    borderWidth: 0,
                    data: <?php echo json_encode($agentCounts); ?>
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#1e293b',
                    titleFontColor: '#f1f5f9',
                    bodyFontColor: '#cbd5e1',
                    cornerRadius: 6
                },
                scales: {
                    xAxes: [{
                        ticks: { fontColor: t3FontColor, fontStyle: t3FontWeight, fontSize: 12 },
                        gridLines: { display: false, drawBorder: false }
                    }],
                    yAxes: [{
                        ticks: { beginAtZero: true, precision: 0, fontColor: t3FontColor, fontStyle: t3FontWeight, fontSize: 11 },
                        gridLines: { display: true, color: t3GridColor, drawBorder: false }
                    }]
                }
            }
        });
    }
</script>
@endif
@endsection
