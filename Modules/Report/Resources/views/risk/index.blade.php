@extends('user::layouts.master')
@section('title', 'Admin | Student Risk Analysis')

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

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-chart-line"></i> Student Risk Analysis</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.report.menu') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Risk Analysis</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner"><h3>{{ $summary['critical'] }}</h3><p>Critical Risk</p></div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <a href="{{ route('admin.report.risk.index', ['risk_level' => 'critical']) }}" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color: #ff851b; color: white;">
                    <div class="inner"><h3>{{ $summary['high'] }}</h3><p>High Risk</p></div>
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    <a href="{{ route('admin.report.risk.index', ['risk_level' => 'high']) }}" class="small-box-footer" style="color: rgba(255,255,255,0.8);">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner"><h3>{{ $summary['medium'] }}</h3><p>Medium Risk</p></div>
                    <div class="icon"><i class="fas fa-info-circle"></i></div>
                    <a href="{{ route('admin.report.risk.index', ['risk_level' => 'medium']) }}" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner"><h3>{{ $summary['low'] }}</h3><p>Low Risk</p></div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                    <a href="{{ route('admin.report.risk.index', ['risk_level' => 'low']) }}" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <form method="get" action="{{ route('admin.report.risk.index') }}" class="form-inline">
                                    <label class="mr-2">Filter by Level:</label>
                                    <select name="risk_level" class="form-control mr-2">
                                        <option value="">All Levels</option>
                                        <option value="critical" {{ $riskLevel == 'critical' ? 'selected' : '' }}>Critical</option>
                                        <option value="high" {{ $riskLevel == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="medium" {{ $riskLevel == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low" {{ $riskLevel == 'low' ? 'selected' : '' }}>Low</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                                </form>
                            </div>
                            <div class="col-md-8 text-right">
                                <form method="post" action="{{ route('admin.report.risk.analyze') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info" onclick="this.disabled=true; this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Analyzing...'; this.form.submit();">
                                        <i class="fas fa-sync-alt"></i> Re-Analyze All Students
                                    </button>
                                </form>
                                <a href="{{ route('admin.report.risk.export', ['risk_level' => $riskLevel]) }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </a>
                                <a href="{{ route('admin.setting.risk-scoring.index') }}" class="btn btn-outline-primary ml-2">
                                    <i class="fas fa-sliders-h"></i> Scoring Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Student Risk Scores
                            @if($riskLevel)
                            <span class="badge badge-secondary">{{ ucfirst($riskLevel) }} only</span>
                            @endif
                            <small class="text-muted ml-2">({{ $scores->count() }} records)</small>
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($scores) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>ID No</th>
                                    <th>Course</th>
                                    <th>Intake</th>
                                    <th>Attendance</th>
                                    <th>Assignments</th>
                                    <th>Grades</th>
                                    <th>Fees</th>
                                    <th>Engagement</th>
                                    <th>Overall Score</th>
                                    <th>Risk Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scores as $index => $score)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ userName('Student', $score->student_id) }}</td>
                                    <td>{{ optional($score->student)->id_no ?? '-' }}</td>
                                    <td>{{ optional(optional(optional($score->studentIntakeCourse)->intakeCourse)->course)->course_name ?? '-' }}</td>
                                    <td>{{ optional(optional(optional($score->studentIntakeCourse)->intakeCourse)->intake)->name ?? '-' }}</td>
                                    @foreach(['attendance_score', 'assignment_score', 'grade_score', 'fee_score', 'engagement_score'] as $scoreField)
                                    <td>
                                        @php $fieldScore = $score->{$scoreField}; @endphp
                                        <div class="progress progress-sm" title="Risk: {{ $fieldScore }}/100">
                                            <div class="progress-bar bg-{{ $fieldScore >= 61 ? 'danger' : ($fieldScore >= 31 ? 'warning' : 'success') }}" style="width: {{ $fieldScore }}%"></div>
                                        </div>
                                        <small>{{ $fieldScore }}/100</small>
                                    </td>
                                    @endforeach
                                    <td><strong class="text-{{ $score->risk_level_color }}">{{ $score->overall_score }}</strong></td>
                                    <td>
                                        @php $badgeColors = ['critical' => 'danger', 'high' => 'warning', 'medium' => 'info', 'low' => 'success']; @endphp
                                        <span class="badge badge-{{ $badgeColors[$score->risk_level] ?? 'secondary' }}">{{ ucfirst($score->risk_level) }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary btn-view-details" data-id="{{ $score->id }}" data-toggle="modal" data-target="#detailModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.student.show', $score->student_id) }}" class="btn btn-sm btn-outline-info" title="View Student">
                                            <i class="fas fa-user"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Risk Data Available</h4>
                            <p>No student risk scores have been generated yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-shield"></i> Risk Analysis Details</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function riskEscapeHtml(value) {
    return $('<div>').text(value == null ? '' : value).html();
}

function riskFormatDetails(details) {
    if (!details || typeof details !== 'object') {
        return '';
    }

    var parts = [];
    Object.keys(details).forEach(function(key) {
        if (key !== 'period' && key !== 'note' && details[key] !== null) {
            parts.push(riskEscapeHtml(key.replace(/_/g, ' ')) + ': ' + riskEscapeHtml(details[key]));
        }
    });

    var result = parts.join(' | ');
    if (details.note) {
        result += (result ? ' <br><i>' : '<i>') + riskEscapeHtml(details.note) + '</i>';
    }

    return result;
}

$(document).on('click', '.btn-view-details', function() {
    var id = $(this).data('id');
    var body = $('#detailModalBody');
    body.html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Loading...</p></div>');

    $.ajax({
        url: '{{ url("admin/report/risk/student") }}/' + id,
        type: 'GET',
        success: function(data) {
            var levelColors = {critical: 'danger', high: 'warning', medium: 'info', low: 'success'};
            var badgeColor = levelColors[data.risk_level] || 'secondary';
            var details = data.details || {};
            var html = '<div class="row">';

            html += '<div class="col-md-6">';
            html += '<h5>' + riskEscapeHtml(data.student_name) + '</h5>';
            html += '<p class="text-muted mb-1">ID: ' + riskEscapeHtml(data.student_id_no || '-') + '</p>';
            html += '<p class="text-muted mb-1">Course: ' + riskEscapeHtml(data.course_name) + '</p>';
            html += '<p class="text-muted">Analyzed: ' + riskEscapeHtml(data.analyzed_at) + '</p>';
            html += '</div>';
            html += '<div class="col-md-6 text-right">';
            html += '<h1 class="text-' + badgeColor + '">' + riskEscapeHtml(data.overall_score) + '<small>/100</small></h1>';
            html += '<span class="badge badge-' + badgeColor + ' badge-lg" style="font-size: 1rem; padding: 0.5em 1em;">' + riskEscapeHtml(data.risk_level_label) + ' Risk</span>';
            html += '</div></div><hr>';

            var signals = [
                {name: 'Attendance', score: data.attendance_score || 0, weight: '{{ $settings->attendance_weight }}%', details: details.attendance, icon: 'calendar-check'},
                {name: 'Assignments', score: data.assignment_score || 0, weight: '{{ $settings->assignment_weight }}%', details: details.assignment, icon: 'tasks'},
                {name: 'Grades', score: data.grade_score || 0, weight: '{{ $settings->grade_weight }}%', details: details.grade, icon: 'graduation-cap'},
                {name: 'Fee Payments', score: data.fee_score || 0, weight: '{{ $settings->fee_weight }}%', details: details.fee, icon: 'dollar-sign'},
                {name: 'Engagement', score: data.engagement_score || 0, weight: '{{ $settings->engagement_weight }}%', details: details.engagement, icon: 'sign-in-alt'}
            ];

            html += '<h6 class="mb-3"><strong>Signal Breakdown</strong></h6>';
            signals.forEach(function(signal) {
                var color = signal.score >= 61 ? 'danger' : (signal.score >= 31 ? 'warning' : 'success');
                html += '<div class="mb-3">';
                html += '<div class="d-flex justify-content-between align-items-center mb-1">';
                html += '<span><i class="fas fa-' + signal.icon + '"></i> ' + signal.name + ' <small class="text-muted">(weight: ' + signal.weight + ')</small></span>';
                html += '<strong class="text-' + color + '">' + signal.score + '/100</strong>';
                html += '</div>';
                html += '<div class="progress" style="height: 10px;">';
                html += '<div class="progress-bar bg-' + color + '" style="width: ' + signal.score + '%"></div>';
                html += '</div>';

                var detailText = riskFormatDetails(signal.details);
                if (detailText) {
                    html += '<small class="text-muted">' + detailText + '</small>';
                }
                html += '</div>';
            });

            body.html(html);
        },
        error: function(xhr) {
            var message = xhr.status === 404 ? 'Risk details were not found.' : 'Failed to load details.';
            body.html('<div class="alert alert-danger">' + message + '</div>');
        }
    });
});
</script>
@endsection
