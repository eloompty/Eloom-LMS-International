@extends('user::layouts.master')
@section('title', 'Admin | Gradebook')

@section('content')
@php
    $userTheme = Auth::guard('user')->user()->theme ?? 'light';
@endphp

@if ($userTheme === 'theme3')
<!-- Enterprise UI (Theme 3) -->
<style>
    .enterprise-gradebook-wrapper {
        padding: 1.5rem 0;
    }

    /* Profile Banner */
    .ent-banner-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.25rem;
    }
    .ent-banner-left {
        min-width: 0;
    }
    .ent-student-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .ent-student-meta {
        font-size: 0.9rem;
        color: #475569;
        font-weight: 600;
    }
    .ent-student-meta i {
        color: #3b82f6;
    }
    .btn-ent-action {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #1e293b;
        font-weight: 700;
        border-radius: 8px;
        padding: 0.65rem 1.25rem;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .btn-ent-action:hover {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.15);
    }

    /* Enterprise Metric Cards */
    .ent-metrics-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .ent-metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .ent-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .ent-metric-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .ent-metric-value {
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.2;
        color: #0f172a;
    }
    .ent-metric-icon {
        position: absolute;
        right: 1.5rem;
        bottom: 1.25rem;
        font-size: 2.25rem;
        opacity: 0.08;
    }
    .ent-metric-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 100%;
    }
    .bar-primary { background-color: #2563eb; }
    .bar-info { background-color: #06b6d4; }
    .bar-standing { background-color: #10b981; }

    /* Tables Card */
    .ent-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .ent-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }
    .ent-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .ent-card-title i {
        color: #3b82f6;
    }
    .ent-card-body {
        padding: 1.5rem;
    }

    /* Enterprise Table Styling */
    .ent-table {
        width: 100%;
        margin-bottom: 0;
        background-color: transparent;
        border-collapse: collapse;
    }
    .ent-table th {
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid #e2e8f0;
        background-color: #f8fafc;
    }
    .ent-table td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
        color: #334155;
    }
    .ent-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .ent-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Custom Badges */
    .ent-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }
    .ent-badge-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .ent-badge-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .ent-badge-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .ent-badge-secondary { background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }

    @media (max-width: 991.98px) {
        .ent-metrics-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .ent-table th, .ent-table td {
            padding: 0.75rem 1rem;
        }
    }
</style>

<section class="content-header">
<div class="container-fluid enterprise-gradebook-wrapper">
    <!-- Profile Banner -->
    <div class="ent-banner-card">
        <div class="ent-banner-left">
            <div class="ent-student-name">Gradebook &mdash; {{ $student->first_name }} {{ $student->last_name }}</div>
            <div class="ent-student-meta">
                <i class="fas fa-university mr-1"></i>
                {{ optional(optional(optional($intakeCourse->intakeCourse)->intake))->name }}
                <span class="mx-1">&middot;</span>
                {{ optional(optional(optional($intakeCourse->intakeCourse)->course))->course_name }}
            </div>
        </div>
        <div>
            <a href="{{ route('admin.gradebook.transcript', [$student->id, $intakeCourse->id]) }}"
               class="btn-ent-action text-decoration-none" target="_blank">
                <i class="fas fa-file-pdf"></i> Download Official Transcript
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="ent-metrics-row">
        <!-- Overall Percentage Card -->
        <div class="ent-metric-card">
            <div class="ent-metric-label">Overall Percentage</div>
            <div class="ent-metric-value">{{ $data['overall_pct'] !== null ? $data['overall_pct'].'%' : 'N/A' }}</div>
            <div class="ent-metric-icon text-primary"><i class="fas fa-chart-bar"></i></div>
            <div class="ent-metric-bar bar-primary"></div>
        </div>
        <!-- Attendance Card -->
        <div class="ent-metric-card">
            <div class="ent-metric-label">Attendance Rate</div>
            <div class="ent-metric-value">{{ $data['attendance_rate'] !== null ? $data['attendance_rate'].'%' : 'N/A' }}</div>
            <div class="ent-metric-icon text-info"><i class="fas fa-calendar-check"></i></div>
            <div class="ent-metric-bar bar-info"></div>
        </div>
        <!-- Academic Standing Card -->
        <div class="ent-metric-card">
            <div class="ent-metric-label">Academic Standing</div>
            <div class="ent-metric-value" style="font-size: 1.5rem; margin-top: 0.25rem;">
                @php
                    $standingClass = match($data['standing']) {
                        'Good Standing' => 'success',
                        'At Risk'       => 'warning',
                        'Failing'       => 'danger',
                        default         => 'secondary',
                    };
                @endphp
                <span class="ent-badge ent-badge-{{ $standingClass }}">{{ $data['standing'] }}</span>
            </div>
            <div class="ent-metric-icon text-success"><i class="fas fa-user-graduate"></i></div>
            <div class="ent-metric-bar bar-standing"></div>
        </div>
    </div>

    <!-- Subjects Section -->
    @if(count($data['subjects']))
    <div class="ent-card">
        <div class="ent-card-header">
            <h3 class="ent-card-title"><i class="fas fa-book-open"></i> Subjects Portfolio</h3>
        </div>
        <div class="ent-card-body p-0">
            <div class="table-responsive">
                <table class="ent-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th class="text-center" style="width: 120px;">Full Marks</th>
                            <th class="text-center" style="width: 120px;">Pass Marks</th>
                            <th class="text-center" style="width: 120px;">Obtained</th>
                            <th class="text-center" style="width: 100px;">Percentage</th>
                            <th class="text-center" style="width: 120px;">Result</th>
                            <th class="text-center" style="width: 120px;">Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['subjects'] as $row)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $row['name'] }}</td>
                            <td class="text-center">{{ $row['full_marks'] }}</td>
                            <td class="text-center">{{ $row['pass_marks'] }}</td>
                            <td class="text-center font-weight-bold text-slate">{{ $row['obtain_marks'] }}</td>
                            <td class="text-center font-weight-bold">{{ $row['percentage'] !== null ? $row['percentage'].'%' : '-' }}</td>
                            <td class="text-center">
                                @php
                                    $resultClass = match($row['result']) {
                                        'Pass'  => 'success',
                                        'Fail'  => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="ent-badge ent-badge-{{ $resultClass }}">{{ $row['result'] }}</span>
                            </td>
                            <td class="text-center">
                                @if($row['is_complete'])
                                    <span class="ent-badge ent-badge-success">Yes</span>
                                @else
                                    <span class="ent-badge ent-badge-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Units Section -->
    @if(count($data['units']))
    <div class="ent-card">
        <div class="ent-card-header">
            <h3 class="ent-card-title"><i class="fas fa-briefcase"></i> Competency Units Summary</h3>
        </div>
        <div class="ent-card-body p-0">
            <div class="table-responsive">
                <table class="ent-table">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th class="text-center" style="width: 120px;">Full Marks</th>
                            <th class="text-center" style="width: 120px;">Pass Marks</th>
                            <th class="text-center" style="width: 120px;">Obtained</th>
                            <th class="text-center" style="width: 100px;">Percentage</th>
                            <th class="text-center" style="width: 120px;">Result</th>
                            <th class="text-center">Competency Outcome</th>
                            <th class="text-center" style="width: 120px;">Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['units'] as $row)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $row['name'] }}</td>
                            <td class="text-center">{{ $row['full_marks'] }}</td>
                            <td class="text-center">{{ $row['pass_marks'] }}</td>
                            <td class="text-center font-weight-bold text-slate">{{ $row['obtain_marks'] }}</td>
                            <td class="text-center font-weight-bold">{{ $row['percentage'] !== null ? $row['percentage'].'%' : '-' }}</td>
                            <td class="text-center">
                                @php
                                    $resultClass = match($row['result']) {
                                        'Pass'  => 'success',
                                        'Fail'  => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="ent-badge ent-badge-{{ $resultClass }}">{{ $row['result'] }}</span>
                            </td>
                            <td class="text-center font-weight-bold text-slate">{{ $row['outcome'] ?? '-' }}</td>
                            <td class="text-center">
                                @if($row['is_complete'])
                                    <span class="ent-badge ent-badge-success">Yes</span>
                                @else
                                    <span class="ent-badge ent-badge-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
</section>

@else
<!-- Standard AdminLTE UI (Theme 1 & 2) -->
<section class="content-header">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Gradebook — {{ $student->first_name }} {{ $student->last_name }}</h4>
            <div>
                <a href="{{ route('admin.gradebook.transcript', [$student->id, $intakeCourse->id]) }}"
                   class="btn btn-secondary btn-sm" target="_blank">Download Official Transcript</a>
            </div>
        </div>
        <div class="col-12 text-muted">
            {{ optional(optional(optional($intakeCourse->intakeCourse)->intake))->name }}
            &nbsp;/&nbsp;
            {{ optional(optional(optional($intakeCourse->intakeCourse)->course))->course_name }}
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Overall</h5>
                    <h2>{{ $data['overall_pct'] !== null ? $data['overall_pct'].'%' : 'N/A' }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Attendance</h5>
                    <h2>{{ $data['attendance_rate'] !== null ? $data['attendance_rate'].'%' : 'N/A' }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Standing</h5>
                    <h2>
                        @php
                            $badgeClass = match($data['standing']) {
                                'Good Standing' => 'success',
                                'At Risk'       => 'warning',
                                'Failing'       => 'danger',
                                default         => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $badgeClass }}">{{ $data['standing'] }}</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Subjects --}}
    @if(count($data['subjects']))
    <h5>Subjects</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Subject</th>
                    <th>Full Marks</th>
                    <th>Pass Marks</th>
                    <th>Obtained</th>
                    <th>%</th>
                    <th>Result</th>
                    <th>Complete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['subjects'] as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['full_marks'] }}</td>
                    <td>{{ $row['pass_marks'] }}</td>
                    <td>{{ $row['obtain_marks'] }}</td>
                    <td>{{ $row['percentage'] !== null ? $row['percentage'].'%' : '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $row['result'] === 'Pass' ? 'success' : ($row['result'] === 'Fail' ? 'danger' : 'secondary') }}">
                            {{ $row['result'] }}
                        </span>
                    </td>
                    <td>{{ $row['is_complete'] ? 'Yes' : 'No' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Units --}}
    @if(count($data['units']))
    <h5>Units</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Unit</th>
                    <th>Full Marks</th>
                    <th>Pass Marks</th>
                    <th>Obtained</th>
                    <th>%</th>
                    <th>Result</th>
                    <th>Outcome</th>
                    <th>Complete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['units'] as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['full_marks'] }}</td>
                    <td>{{ $row['pass_marks'] }}</td>
                    <td>{{ $row['obtain_marks'] }}</td>
                    <td>{{ $row['percentage'] !== null ? $row['percentage'].'%' : '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $row['result'] === 'Pass' ? 'success' : ($row['result'] === 'Fail' ? 'danger' : 'secondary') }}">
                            {{ $row['result'] }}
                        </span>
                    </td>
                    <td>{{ $row['outcome'] ?? '-' }}</td>
                    <td>{{ $row['is_complete'] ? 'Yes' : 'No' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
</section>
@endif
@endsection
