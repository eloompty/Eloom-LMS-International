@extends('user::layouts.master')
@section('title', 'Admin | Scholarship Disbursement Schedule')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif

@php
    $disbursements = $application->disbursements;
    $releasedTotal = $disbursements->where('state', 'released')->sum('actual_amount');
    $plannedTotal = $disbursements->sum('planned_amount');
    $scheduledCount = $disbursements->where('state', 'scheduled')->count();
    $withheldCount = $disbursements->where('state', 'withheld')->count();
    $cancelledCount = $disbursements->where('state', 'cancelled')->count();
    $fee = $application->studentIntakeCourseFee;
    $userTheme = Auth::guard('user')->user()->theme ?? 'light';
@endphp

@if ($userTheme === 'theme3')
<!-- Enterprise UI (Theme 3) -->
<style>
    .enterprise-schedule-wrapper {
        padding: 1.5rem 0;
    }
    .ent-banner-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .ent-banner-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    }
    .ent-student-name {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .ent-student-meta {
        font-size: 0.9rem;
        color: #475569;
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        margin-top: 0.75rem;
    }
    .ent-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .ent-meta-item i {
        color: #3b82f6;
    }

    /* Dynamic Badges */
    .ent-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }
    .ent-badge-approved { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .ent-badge-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .ent-badge-rejected { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .ent-badge-revoked { background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }

    /* State Pills for Disbursements Table */
    .badge-state-released { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-state-scheduled { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-state-withheld { background-color: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-state-cancelled { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    /* Financial metrics sidebar widgets */
    .ent-financial-metrics {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .ent-financial-metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .ent-financial-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .ent-fin-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .ent-fin-value {
        font-size: 1.4rem;
        font-weight: 700;
    }
    .ent-fin-value.released { color: #10b981; }
    .ent-fin-value.planned { color: #475569; }

    /* State counter pills section */
    .ent-state-counters {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: space-between;
    }
    .ent-counter-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Detail blocks */
    .ent-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .ent-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    /* Tableless Info List */
    .ent-info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.15rem;
    }
    .ent-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 0.65rem;
    }
    .ent-info-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }
    .ent-info-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    .ent-info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
        text-align: right;
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

    .btn-ent-back {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        text-decoration: none;
    }
    .btn-ent-back:hover {
        background-color: #f8fafc;
        color: #0f172a;
        text-decoration: none;
    }

    @media (max-width: 991.98px) {
        .ent-table th, .ent-table td {
            padding: 0.75rem 1rem;
        }
    }
</style>

<section class="content-header">
<div class="container-fluid enterprise-schedule-wrapper">
    <!-- Header Row -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 font-weight-bold text-dark mb-0">Disbursement Schedule</h1>
        </div>
        <div class="col-md-6 text-md-right mt-2 mt-md-0">
            <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.application.index') }}">Applications</a></li>
                <li class="breadcrumb-item active text-muted">Schedule</li>
            </ol>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="row">
        <!-- Sidebar Summary (4 cols) -->
        <div class="col-lg-4">
            <!-- Student Banner -->
            <div class="ent-banner-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="ent-student-name">{{ userName('Student', $application->student_id) }}</div>
                        <div class="text-primary font-weight-bold" style="font-size: 0.95rem;">
                            {{ optional(optional($application->studentIntakeCourseFee)->intakeCourse)->course->course_name ?? '—' }}
                        </div>
                    </div>
                    <div>
                        @if($application->status === 1)
                            <span class="ent-badge ent-badge-approved">Approved</span>
                        @elseif($application->status === 0)
                            <span class="ent-badge ent-badge-pending">Pending</span>
                        @elseif($application->status === 2)
                            <span class="ent-badge ent-badge-rejected">Rejected</span>
                        @elseif($application->status === 3)
                            <span class="ent-badge ent-badge-revoked">Revoked</span>
                        @endif
                    </div>
                </div>

                <div class="ent-student-meta">
                    @if($application->student)
                        <div class="ent-meta-item">
                            <i class="far fa-id-badge"></i>
                            <span>ID: <strong>{{ $application->student->id_no ?: 'N/A' }}</strong></span>
                        </div>
                        <div class="ent-meta-item">
                            <i class="far fa-envelope"></i>
                            <span>{{ $application->student->email }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Card Widgets -->
            <div class="ent-financial-metrics">
                <div class="ent-financial-metric-card">
                    <span class="ent-fin-label">Released to Date</span>
                    <span class="ent-fin-value released">${{ number_format($releasedTotal, 2) }}</span>
                </div>
                <div class="ent-financial-metric-card">
                    <span class="ent-fin-label">Planned Total</span>
                    <span class="ent-fin-value planned">${{ number_format($plannedTotal, 2) }}</span>
                </div>

                <!-- State Counters box -->
                <div class="ent-state-counters">
                    <span class="ent-counter-pill text-info">
                        <i class="fas fa-dot-circle"></i> {{ $scheduledCount }} Scheduled
                    </span>
                    <span class="ent-counter-pill text-warning">
                        <i class="fas fa-exclamation-circle"></i> {{ $withheldCount }} Withheld
                    </span>
                    <span class="ent-counter-pill text-secondary">
                        <i class="fas fa-times-circle"></i> {{ $cancelledCount }} Cancelled
                    </span>
                </div>
            </div>

            <!-- Scholarship profile parameters -->
            <div class="ent-card">
                <div class="ent-card-header">
                    <h3 class="ent-card-title"><i class="fas fa-award"></i> Application Parameters</h3>
                </div>
                <div class="ent-card-body">
                    <div class="ent-info-grid">
                        <div class="ent-info-item">
                            <span class="ent-info-label">Scholarship</span>
                            <span class="ent-info-value">{{ $application->scholarship->name ?? '—' }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Rate / Value</span>
                            <span class="ent-info-value text-primary">{{ $application->scholarship->value_display ?? '—' }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Award Scope</span>
                            <span class="ent-info-value">{{ $application->scholarship->award_scope_label ?? '—' }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Disbursement</span>
                            <span class="ent-info-value">{{ $application->scholarship->disbursement_label ?? '—' }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Total Fee</span>
                            <span class="ent-info-value">${{ number_format($fee->fee ?? 0, 2) }}</span>
                        </div>
                        @if($application->scholarship && $application->scholarship->requires_maintenance)
                        <div class="ent-info-item">
                            <span class="ent-info-label">Maintenance</span>
                            <span class="ent-info-value text-warning">
                                Keep &ge; {{ rtrim(rtrim($application->scholarship->maintenance_min_percentage, '0'), '.') }}%
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Disbursement Timeline Table (8 cols) -->
        <div class="col-lg-8">
            <div class="ent-card">
                <div class="ent-card-header">
                    <h3 class="ent-card-title"><i class="fas fa-stream"></i> Disbursements Timeline</h3>
                </div>
                <div class="ent-card-body p-0">
                    <div class="table-responsive">
                        <table class="ent-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">Seq</th>
                                    <th>Disbursement Target</th>
                                    <th class="text-right">Planned Amount</th>
                                    <th class="text-right">Released Amount</th>
                                    <th class="text-center">State Status</th>
                                    <th>Maintenance / Details</th>
                                    <th>Evaluated Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($disbursements as $d)
                                <tr>
                                    <td class="text-center font-weight-bold">{{ $d->sequence ?? '—' }}</td>
                                    <td>
                                        @if($d->intakeSemester)
                                            {{ optional($d->intakeSemester->semester)->name ?? ('Semester ' . $d->sequence) }}
                                        @else
                                            <span class="text-muted">One-off</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-weight-bold text-slate">${{ number_format($d->planned_amount, 2) }}</td>
                                    <td class="text-right font-weight-bold text-success">
                                        {{ $d->actual_amount !== null ? '$' . number_format($d->actual_amount, 2) : '—' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="ent-badge badge-state-{{ $d->state }}">
                                            {{ $d->state_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($d->maintenance_percentage_achieved !== null)
                                            <span class="font-weight-bold">{{ rtrim(rtrim($d->maintenance_percentage_achieved, '0'), '.') }}% achieved</span>
                                        @endif
                                        @if($d->state_reason)
                                            <div class="small text-muted font-italic">{{ $d->state_reason }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $d->evaluated_at ? dateFormat($d->evaluated_at) : '—' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-info-circle mb-2" style="font-size: 1.5rem; opacity: 0.5;"></i>
                                        <p class="mb-0">No disbursements generated yet. Disbursements are created automatically once this application is approved.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="ent-card-footer p-3 bg-white" style="border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('admin.scholarship.application.index') }}" class="btn-ent-back">
                        <i class="fas fa-arrow-left"></i> Back to Applications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@else
<!-- Standard AdminLTE UI (Theme 1 & 2) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Disbursement Schedule</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.application.index') }}">Applications</a></li>
                    <li class="breadcrumb-item active">Schedule</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- Application summary --}}
            <div class="col-md-4">
                <div class="card card-outline card-primary">
                    <div class="card-header"><h3 class="card-title mb-0">Application</h3></div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-5">Student</dt>
                            <dd class="col-7">{{ userName('Student', $application->student_id) }}</dd>
                            <dt class="col-5">Scholarship</dt>
                            <dd class="col-7">{{ $application->scholarship->name ?? '-' }}</dd>
                            <dt class="col-5">Scope</dt>
                            <dd class="col-7">{{ $application->scholarship->award_scope_label ?? '-' }}</dd>
                            <dt class="col-5">Disbursement</dt>
                            <dd class="col-7">{{ $application->scholarship->disbursement_label ?? '-' }}</dd>
                            <dt class="col-5">Value</dt>
                            <dd class="col-7">{{ $application->scholarship->value_display ?? '-' }}</dd>
                            @if($application->scholarship && $application->scholarship->requires_maintenance)
                            <dt class="col-5">Maintenance</dt>
                            <dd class="col-7">Keep ≥ {{ rtrim(rtrim($application->scholarship->maintenance_min_percentage, '0'), '.') }}%</dd>
                            @endif
                            <dt class="col-5">Course Fee</dt>
                            <dd class="col-7">${{ number_format($fee->fee ?? 0, 2) }}</dd>
                            <dt class="col-5">Status</dt>
                            <dd class="col-7"><span class="badge badge-{{ $application->status_class }}">{{ $application->status_label }}</span></dd>
                        </dl>
                    </div>
                </div>

                <div class="card card-outline card-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between"><span class="text-muted">Released to date</span><strong class="text-success">${{ number_format($releasedTotal, 2) }}</strong></div>
                        <div class="d-flex justify-content-between"><span class="text-muted">Planned total</span><strong>${{ number_format($plannedTotal, 2) }}</strong></div>
                        <hr class="my-2">
                        <div>
                            <span class="badge badge-info">{{ $scheduledCount }} scheduled</span>
                            <span class="badge badge-warning">{{ $withheldCount }} withheld</span>
                            <span class="badge badge-secondary">{{ $cancelledCount }} cancelled</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Disbursement schedule --}}
            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-stream mr-1"></i> Disbursements</h3></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Semester</th>
                                        <th class="text-right">Planned</th>
                                        <th class="text-right">Released</th>
                                        <th class="text-center">State</th>
                                        <th>Maintenance</th>
                                        <th>Evaluated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($disbursements as $d)
                                    <tr>
                                        <td>{{ $d->sequence ?? '—' }}</td>
                                        <td>
                                            @if($d->intakeSemester)
                                                {{ optional($d->intakeSemester->semester)->name ?? ('Semester ' . $d->sequence) }}
                                            @else
                                                <span class="text-muted">One-off</span>
                                            @endif
                                        </td>
                                        <td class="text-right">${{ number_format($d->planned_amount, 2) }}</td>
                                        <td class="text-right">{{ $d->actual_amount !== null ? '$' . number_format($d->actual_amount, 2) : '—' }}</td>
                                        <td class="text-center"><span class="badge badge-{{ $d->state_class }}">{{ $d->state_label }}</span></td>
                                        <td>
                                            @if($d->maintenance_percentage_achieved !== null)
                                                {{ rtrim(rtrim($d->maintenance_percentage_achieved, '0'), '.') }}%
                                            @endif
                                            @if($d->state_reason)
                                                <div class="small text-muted">{{ $d->state_reason }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $d->evaluated_at ? dateFormat($d->evaluated_at) : '—' }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">No disbursements yet. They are generated when the application is approved.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.scholarship.application.index') }}" class="btn btn-default">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection

