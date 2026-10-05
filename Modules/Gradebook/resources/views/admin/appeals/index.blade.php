@extends('user::layouts.master')
@section('title', 'Admin | Grade Appeals')

@section('header-script')
<style>
/* ── Grade Appeals table ── */
.appeals-table {
    min-width: 1380px;
    table-layout: fixed;
}
.appeals-table th,
.appeals-table td {
    white-space: normal;
    word-break: normal;
    overflow-wrap: anywhere;
    vertical-align: middle;
}
.appeals-table .col-id       { width: 54px; }
.appeals-table .col-student  { width: 148px; }
.appeals-table .col-course   { width: 190px; }
.appeals-table .col-intake   { width: 145px; }
.appeals-table .col-context  { width: 205px; }
.appeals-table .col-type     { width: 100px; }
.appeals-table .col-reason   { width: 235px; }
.appeals-table .col-status   { width: 130px; }
.appeals-table .col-response { width: 185px; }
.appeals-table .col-notes    { width: 165px; }
.appeals-table .col-action   { width: 240px; }

.appeal-muted {
    display: block;
    margin-top: 2px;
    color: #64748b;
    font-size: 11px;
    line-height: 1.35;
    font-weight: 700;
}
.appeal-text {
    max-height: 72px;
    overflow: auto;
    font-size: 12px;
    line-height: 1.5;
}
.appeal-action-form {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.appeal-action-form .form-control {
    border-radius: 6px !important;
    border-color: #d1d5db !important;
    font-size: 12px !important;
}
.appeal-action-form .btn-save {
    width: 100%;
    font-weight: 800;
    font-size: 12px;
    border-radius: 6px;
    padding: 5px 0;
}
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Grade Appeals</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Grade Appeals</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
             style="border-radius:8px; border:1px solid #a7f3d0; background:#f0fdf4; color:#065f46; font-size:13px; font-weight:700;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" style="color:#065f46;"><span>&times;</span></button>
        </div>
        @endif

        {{-- KPI summary --}}
        @php
            $col          = method_exists($appeals, 'getCollection') ? $appeals->getCollection() : $appeals;
            $pendingCnt   = $col->where('status', 'pending')->count();
            $reviewedCnt  = $col->where('status', 'trainer_reviewed')->count();
            $resolvedCnt  = $col->where('status', 'resolved')->count();
            $rejectedCnt  = $col->where('status', 'rejected')->count();
        @endphp
        <div class="row mb-4">
            <div class="col-6 col-sm-3 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-amber"><i class="fas fa-hourglass-half"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Pending</span>
                        <span class="metric-value">{{ $pendingCnt }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-cyan"><i class="fas fa-comment-dots"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Trainer Reviewed</span>
                        <span class="metric-value">{{ $reviewedCnt }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-green"><i class="fas fa-check-circle"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Resolved</span>
                        <span class="metric-value">{{ $resolvedCnt }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-red"><i class="fas fa-times-circle"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Rejected</span>
                        <span class="metric-value">{{ $rejectedCnt }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Appeals table --}}
        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-flag"></i>
                    All Grade Appeals
                </h3>
                @if($pendingCnt > 0)
                <div class="card-tools">
                    <span class="badge badge-warning"
                          style="font-size:12px; font-weight:800; padding:0.4rem 0.75rem; border-radius:999px;">
                        {{ $pendingCnt }} pending
                    </span>
                </div>
                @endif
            </div>

            @if($col->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-flag"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No grade appeals found.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Appeals submitted by students will appear here.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table dashboard-table mb-0 appeals-table">
                    <thead>
                        <tr>
                            <th class="col-id">#</th>
                            <th class="col-student">Student</th>
                            <th class="col-course">Course</th>
                            <th class="col-intake">Intake</th>
                            <th class="col-context">Subject / Unit</th>
                            <th class="col-type">Mark Type</th>
                            <th class="col-reason">Reason</th>
                            <th class="col-status text-center">Status</th>
                            <th class="col-response">Trainer Response</th>
                            <th class="col-notes">Admin Notes</th>
                            <th class="col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appeals as $appeal)
                        @php
                            $subjectName = '—';
                            $unitName    = null;
                            $courseName  = '—';
                            $intakeName  = '—';
                            $markName    = null;

                            if ($appeal->mark_type === 'subject' && $appeal->subjectMark) {
                                $mark               = $appeal->subjectMark;
                                $intakeSubject      = optional($mark->studentIntakeSubject)->intakeSubject;
                                $intakeCourse       = optional($intakeSubject)->intakeCourse;
                                $subjectName        = optional(optional($intakeSubject)->subject)->name ?: '—';
                                $courseName         = optional(optional($intakeCourse)->course)->course_name ?: '—';
                                $intakeName         = optional(optional($intakeCourse)->intake)->name ?: '—';
                                $markName           = $mark->name;
                            } elseif ($appeal->mark_type === 'unit' && $appeal->unitMark) {
                                $mark               = $appeal->unitMark;
                                $intakeUnit         = optional($mark->studentIntakeUnit)->intakeUnit;
                                $intakeSubject      = optional($intakeUnit)->intakeSubject;
                                $intakeCourse       = optional($intakeUnit)->intakeCourse;
                                $subjectName        = optional(optional($intakeSubject)->subject)->name ?: '—';
                                $unitName           = optional(optional($intakeUnit)->unit)->name ?: '—';
                                $courseName         = optional(optional($intakeCourse)->course)->course_name ?: '—';
                                $intakeName         = optional(optional($intakeCourse)->intake)->name ?: '—';
                                $markName           = $mark->name;
                            }

                            $statusBadge = ['pending' => 'warning', 'trainer_reviewed' => 'info', 'resolved' => 'success', 'rejected' => 'danger'][$appeal->status] ?? 'secondary';
                            $statusLabel = ucfirst(str_replace('_', ' ', $appeal->status));
                        @endphp
                        <tr>
                            <td style="color:#94a3b8; font-weight:700; font-size:12px;">{{ $appeal->id }}</td>

                            <td style="font-weight:700; font-size:12px; color:#111827;">
                                {{ optional($appeal->student)->first_name }}
                                {{ optional($appeal->student)->last_name }}
                                <span class="appeal-muted">#{{ optional($appeal->student)->id }}</span>
                            </td>

                            <td><div class="appeal-text">{{ $courseName }}</div></td>

                            <td style="font-size:12px; color:#374151;">{{ $intakeName }}</td>

                            <td>
                                <div style="font-size:12px; font-weight:700; color:#111827;">{{ $subjectName }}</div>
                                @if($unitName)
                                <span class="appeal-muted"><i class="fas fa-layer-group" style="font-size:10px;"></i> {{ $unitName }}</span>
                                @endif
                                @if($markName)
                                <span class="appeal-muted"><i class="fas fa-tag" style="font-size:10px;"></i> {{ $markName }}</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge badge-{{ $appeal->mark_type === 'subject' ? 'primary' : 'success' }}"
                                      style="font-size:10px; font-weight:800; padding:0.25rem 0.6rem; border-radius:999px;">
                                    <i class="fas fa-{{ $appeal->mark_type === 'subject' ? 'book-open' : 'layer-group' }} mr-1" style="font-size:9px;"></i>
                                    {{ ucfirst($appeal->mark_type) }}
                                </span>
                            </td>

                            <td><div class="appeal-text">{{ $appeal->reason }}</div></td>

                            <td class="text-center">
                                <span class="badge badge-{{ $statusBadge }}"
                                      style="font-size:11px; font-weight:800; padding:0.3rem 0.65rem; border-radius:999px; white-space:nowrap;">
                                    {{ $statusLabel }}
                                </span>
                                @if($appeal->updated_at && $appeal->status !== 'pending')
                                <span class="appeal-muted" style="text-align:center;">
                                    {{ $appeal->updated_at->format('d M Y') }}
                                </span>
                                @endif
                            </td>

                            <td>
                                <div class="appeal-text" style="color:{{ $appeal->trainer_response ? '#374151' : '#94a3b8' }};">
                                    {{ $appeal->trainer_response ?? '—' }}
                                </div>
                            </td>

                            <td>
                                <div class="appeal-text" style="color:{{ $appeal->admin_notes ? '#374151' : '#94a3b8' }};">
                                    {{ $appeal->admin_notes ?? '—' }}
                                </div>
                            </td>

                            <td>
                                <form method="POST"
                                      action="{{ route('admin.gradebook.appeal.resolve', $appeal->id) }}"
                                      class="appeal-action-form">
                                    @csrf
                                    <select name="status" class="form-control form-control-sm">
                                        @foreach(['pending','trainer_reviewed','resolved','rejected'] as $s)
                                        <option value="{{ $s }}" {{ $appeal->status === $s ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="admin_notes"
                                           class="form-control form-control-sm"
                                           placeholder="Admin notes..."
                                           value="{{ $appeal->admin_notes }}">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        <i class="fas fa-save mr-1"></i> Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($appeals, 'links'))
            <div class="card-footer" style="background:#f8fafc; border-top:1px solid #e5e7eb; padding:12px 16px;">
                {{ $appeals->links() }}
            </div>
            @endif
            @endif

        </div>

        {{-- Status legend --}}
        @if(!$col->isEmpty())
        <div class="d-flex flex-wrap align-items-center mt-3" style="gap:16px;">
            <span style="font-size:11px; font-weight:800; color:#6b7280; text-transform:uppercase;">Status guide:</span>
            <span><span class="badge badge-warning" style="font-size:11px;">Pending</span><span style="font-size:11px; color:#6b7280; margin-left:5px;">Awaiting review</span></span>
            <span><span class="badge badge-info" style="font-size:11px;">Trainer Reviewed</span><span style="font-size:11px; color:#6b7280; margin-left:5px;">Trainer responded</span></span>
            <span><span class="badge badge-success" style="font-size:11px;">Resolved</span><span style="font-size:11px; color:#6b7280; margin-left:5px;">Closed</span></span>
            <span><span class="badge badge-danger" style="font-size:11px;">Rejected</span><span style="font-size:11px; color:#6b7280; margin-left:5px;">Declined</span></span>
        </div>
        @endif

    </div>
</section>
@endsection
