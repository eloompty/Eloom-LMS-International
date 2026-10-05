@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Gradebook')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-7">
                <h1>{{ $student->first_name }} {{ $student->last_name }}</h1>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.gradebook.index') }}">Gradebooks</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        {{-- Course meta + back button --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4" style="gap:10px;">
            <p class="mb-0" style="font-size:13px; font-weight:700; color:#6b7280;">
                {{ optional(optional($intakeCourse->intakeCourse)->intake)->name }}
                @if(optional(optional($intakeCourse->intakeCourse)->intake)->name)&nbsp;/&nbsp;@endif
                {{ optional(optional($intakeCourse->intakeCourse)->course)->course_name }}
            </p>
            <a href="{{ route('trainer.gradebook.index') }}" class="panel-action">
                <i class="fas fa-arrow-left"></i> All Students
            </a>
        </div>

        {{-- KPI cards --}}
        @php
            $standingColor = ['Good Standing' => 'metric-green', 'At Risk' => 'metric-amber', 'Failing' => 'metric-red'][$data['standing']] ?? 'metric-slate';
            $standingIcon  = ['Good Standing' => 'fa-check-circle', 'At Risk' => 'fa-exclamation-circle', 'Failing' => 'fa-times-circle'][$data['standing']] ?? 'fa-question-circle';
        @endphp
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-4 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-blue"><i class="fas fa-chart-line"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Overall Grade</span>
                        <span class="metric-value">{{ $data['overall_pct'] !== null ? $data['overall_pct'].'%' : 'N/A' }}</span>
                        @if($data['overall_pct'] !== null)
                        <div class="progress mt-2" style="height:4px; border-radius:2px; background:#e5e7eb;">
                            <div class="progress-bar bg-primary gb-bar" data-pct="{{ min($data['overall_pct'], 100) }}" style="border-radius:2px; width:0;"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-cyan"><i class="fas fa-calendar-check"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Attendance Rate</span>
                        <span class="metric-value">{{ $data['attendance_rate'] !== null ? $data['attendance_rate'].'%' : 'N/A' }}</span>
                        @if($data['attendance_rate'] !== null)
                        <div class="progress mt-2" style="height:4px; border-radius:2px; background:#e5e7eb;">
                            <div class="progress-bar bg-info gb-bar" data-pct="{{ min($data['attendance_rate'], 100) }}" style="border-radius:2px; width:0;"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-3">
                <div class="metric-card">
                    <div class="metric-icon {{ $standingColor }}"><i class="fas {{ $standingIcon }}"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Academic Standing</span>
                        <span class="metric-value" style="font-size:18px; line-height:1.3;">{{ $data['standing'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Subjects --}}
        @if(count($data['subjects']))
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-book-open"></i> Subjects</h3>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th class="text-center">Full</th>
                            <th class="text-center">Pass</th>
                            <th class="text-center">Obtained</th>
                            <th class="text-center" style="min-width:130px;">Percentage</th>
                            <th class="text-center">Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['subjects'] as $row)
                        @php
                            $pct = $row['percentage'];
                            $pctBarClass = $row['result'] === 'Pass' ? 'bg-success' : ($row['result'] === 'Fail' ? 'bg-danger' : 'bg-secondary');
                        @endphp
                        <tr>
                            <td style="font-weight:700;">{{ $row['name'] }}</td>
                            <td class="text-center">{{ $row['full_marks'] }}</td>
                            <td class="text-center">{{ $row['pass_marks'] }}</td>
                            <td class="text-center" style="font-weight:700;">{{ $row['obtain_marks'] ?? '—' }}</td>
                            <td class="text-center">
                                @if($pct !== null)
                                <div class="d-flex align-items-center justify-content-center" style="gap:7px;">
                                    <div class="progress flex-grow-1" style="height:6px; border-radius:3px; background:#e5e7eb; min-width:55px; max-width:80px;">
                                        <div class="progress-bar {{ $pctBarClass }} gb-bar" data-pct="{{ min($pct, 100) }}" style="border-radius:3px; width:0;"></div>
                                    </div>
                                    <span style="font-size:12px; font-weight:800; min-width:36px; text-align:right;">{{ $pct }}%</span>
                                </div>
                                @else
                                <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $row['result'] === 'Pass' ? 'success' : ($row['result'] === 'Fail' ? 'danger' : 'secondary') }}">
                                    {{ $row['result'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Units --}}
        @if(count($data['units']))
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-layer-group"></i> Units</h3>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th class="text-center">Full</th>
                            <th class="text-center">Pass</th>
                            <th class="text-center">Obtained</th>
                            <th class="text-center" style="min-width:130px;">Percentage</th>
                            <th class="text-center">Result</th>
                            <th class="text-center">Outcome</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['units'] as $row)
                        @php
                            $pct = $row['percentage'];
                            $pctBarClass = $row['result'] === 'Pass' ? 'bg-success' : ($row['result'] === 'Fail' ? 'bg-danger' : 'bg-secondary');
                        @endphp
                        <tr>
                            <td style="font-weight:700;">{{ $row['name'] }}</td>
                            <td class="text-center">{{ $row['full_marks'] }}</td>
                            <td class="text-center">{{ $row['pass_marks'] }}</td>
                            <td class="text-center" style="font-weight:700;">{{ $row['obtain_marks'] ?? '—' }}</td>
                            <td class="text-center">
                                @if($pct !== null)
                                <div class="d-flex align-items-center justify-content-center" style="gap:7px;">
                                    <div class="progress flex-grow-1" style="height:6px; border-radius:3px; background:#e5e7eb; min-width:55px; max-width:80px;">
                                        <div class="progress-bar {{ $pctBarClass }} gb-bar" data-pct="{{ min($pct, 100) }}" style="border-radius:3px; width:0;"></div>
                                    </div>
                                    <span style="font-size:12px; font-weight:800; min-width:36px; text-align:right;">{{ $pct }}%</span>
                                </div>
                                @else
                                <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $row['result'] === 'Pass' ? 'success' : ($row['result'] === 'Fail' ? 'danger' : 'secondary') }}">
                                    {{ $row['result'] }}
                                </span>
                            </td>
                            <td class="text-center" style="font-size:12px; color:#374151;">{{ $row['outcome'] ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if(!count($data['subjects']) && !count($data['units']))
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-graduation-cap"></i>
                <p class="mb-0" style="font-weight:700; font-size:14px; color:#374151;">No grade data available yet.</p>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.gb-bar').forEach(function(el) {
        el.style.width = (el.dataset.pct || 0) + '%';
    });
</script>
@endsection
