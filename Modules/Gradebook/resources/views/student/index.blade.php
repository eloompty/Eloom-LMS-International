@extends('student::student.layouts.master')
@section('title', 'Student | My Gradebook')

@section('content')
{{-- Page Header --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>My Gradebook</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Gradebook</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="content">
    <div class="container-fluid">

        {{-- Course bar + Actions --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4" style="gap:10px;">
            <p class="mb-0" style="font-size:13px; font-weight:700; color:#6b7280;">
                {{ optional(optional($intakeCourse->intakeCourse)->intake)->name }}
                @if(optional(optional($intakeCourse->intakeCourse)->intake)->name && optional(optional($intakeCourse->intakeCourse)->course)->course_name)
                    &nbsp;/&nbsp;
                @endif
                {{ optional(optional($intakeCourse->intakeCourse)->course)->course_name }}
            </p>
            <div class="d-flex flex-wrap" style="gap:8px;">
                <a href="{{ route('student.gradebook.transcript', $intakeCourse->id) }}" class="panel-action">
                    <i class="fas fa-file-pdf"></i> Download Transcript
                </a>
                <a href="{{ route('student.gradebook.appeals') }}" class="panel-action">
                    <i class="fas fa-inbox"></i> My Appeals
                </a>
            </div>
        </div>

        {{-- KPI Cards --}}
        @php
            $standingColor = match($data['standing']) {
                'Good Standing' => 'metric-green',
                'At Risk'       => 'metric-amber',
                'Failing'       => 'metric-red',
                default         => 'metric-slate',
            };
            $standingIcon = match($data['standing']) {
                'Good Standing' => 'fa-check-circle',
                'At Risk'       => 'fa-exclamation-circle',
                'Failing'       => 'fa-times-circle',
                default         => 'fa-question-circle',
            };
        @endphp
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-4 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-blue">
                        <i class="fas fa-chart-line"></i>
                    </div>
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
                    <div class="metric-icon metric-cyan">
                        <i class="fas fa-calendar-check"></i>
                    </div>
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
                    <div class="metric-icon {{ $standingColor }}">
                        <i class="fas {{ $standingIcon }}"></i>
                    </div>
                    <div class="metric-content">
                        <span class="metric-label">Academic Standing</span>
                        <span class="metric-value" style="font-size:18px; line-height:1.3;">{{ $data['standing'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Subjects Table --}}
        @if(count($data['subjects']))
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book-open"></i>
                    Subjects
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th class="text-center">Full Marks</th>
                            <th class="text-center">Pass Marks</th>
                            <th class="text-center">Obtained</th>
                            <th class="text-center" style="min-width:130px;">Percentage</th>
                            <th class="text-center">Result</th>
                            <th class="text-center">Appeal</th>
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
                            <td class="text-center">
                                <button type="button"
                                    class="panel-action"
                                    style="font-size:11px; padding:4px 10px; min-height:28px;"
                                    data-toggle="modal"
                                    data-target="#appealModal"
                                    data-action="{{ route('student.gradebook.appeal.submit', ['subject', $row['id']]) }}">
                                    <i class="fas fa-flag"></i> Appeal
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Units Table --}}
        @if(count($data['units']))
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-layer-group"></i>
                    Units
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th class="text-center">Full Marks</th>
                            <th class="text-center">Pass Marks</th>
                            <th class="text-center">Obtained</th>
                            <th class="text-center" style="min-width:130px;">Percentage</th>
                            <th class="text-center">Result</th>
                            <th class="text-center">Outcome</th>
                            <th class="text-center">Appeal</th>
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
                            <td class="text-center">
                                <button type="button"
                                    class="panel-action"
                                    style="font-size:11px; padding:4px 10px; min-height:28px;"
                                    data-toggle="modal"
                                    data-target="#appealModal"
                                    data-action="{{ route('student.gradebook.appeal.submit', ['unit', $row['id']]) }}">
                                    <i class="fas fa-flag"></i> Appeal
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Empty state --}}
        @if(!count($data['subjects']) && !count($data['units']))
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-graduation-cap"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No grade data available yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Grades will appear here once your assessments have been marked.</p>
            </div>
        </div>
        @endif

    </div>
</section>

{{-- Appeal Modal --}}
<div class="modal fade" id="appealModal" tabindex="-1" role="dialog" aria-labelledby="appealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 20px 60px rgba(15,23,42,.15);">
            <div class="modal-header" style="border-bottom:1px solid #e5e7eb; padding:16px 20px;">
                <h5 class="modal-title" id="appealModalLabel"
                    style="font-weight:800; font-size:15px; display:flex; align-items:center; gap:9px; margin:0;">
                    <span style="display:inline-flex; align-items:center; justify-content:center;
                                 width:30px; height:30px; background:#eff6ff; border-radius:6px; color:#2563eb;">
                        <i class="fas fa-flag" style="font-size:12px;"></i>
                    </span>
                    Submit Grade Appeal
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity:.5;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="appealForm" method="POST">
                @csrf
                <div class="modal-body" style="padding:20px;">
                    <div class="form-group mb-0">
                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                            Reason for Appeal <span style="color:#dc2626;">*</span>
                        </label>
                        <textarea name="reason" class="form-control" rows="5" required
                            placeholder="Describe why you believe this grade should be reviewed..."
                            style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e5e7eb; padding:14px 20px; display:flex; justify-content:flex-end; gap:8px;">
                    <button type="button" class="panel-action" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"
                        style="font-weight:800; font-size:13px; border-radius:6px; padding:7px 16px;">
                        <i class="fas fa-paper-plane mr-1"></i> Submit Appeal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.gb-bar').forEach(function(el) {
        el.style.width = (el.dataset.pct || 0) + '%';
    });

    $('#appealModal').on('show.bs.modal', function(e) {
        $('#appealForm').attr('action', $(e.relatedTarget).data('action'));
    });
</script>
@endsection
