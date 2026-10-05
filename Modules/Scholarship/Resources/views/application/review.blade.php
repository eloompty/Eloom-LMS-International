@extends('user::layouts.master')
@section('title', 'Admin | Review Scholarship Application')

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
    $fee = optional($application->studentIntakeCourseFee)->fee ?? 0;
    $netFee = $fee - $discountAmount;
    $userTheme = Auth::guard('user')->user()->theme ?? 'light';
@endphp

@if ($userTheme === 'theme3')
<!-- Enterprise UI (Theme 3) -->
<style>
    .enterprise-review-wrapper {
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
    .ent-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .ent-badge-pending {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    /* Financial Metric Cards */
    .ent-financial-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .ent-metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        position: relative;
        overflow: hidden;
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
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .ent-metric-value.original {
        color: #1e293b;
    }
    .ent-metric-value.discount {
        color: #ef4444;
    }
    .ent-metric-value.net {
        color: #10b981;
    }
    .ent-metric-icon {
        position: absolute;
        right: 1.25rem;
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
    .bar-original { background-color: #3b82f6; }
    .bar-discount { background-color: #ef4444; }
    .bar-net { background-color: #10b981; }

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
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.5rem;
    }
    .ent-info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
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
        font-weight: 500;
        color: #0f172a;
    }

    /* Justification block */
    .ent-justification-block {
        background-color: #f0f9ff;
        border-left: 4px solid #0284c7;
        border-radius: 4px;
        padding: 1.25rem;
        margin-top: 1rem;
        position: relative;
    }
    .ent-justification-quote {
        position: absolute;
        right: 1.25rem;
        top: 0.75rem;
        font-size: 2rem;
        color: #bae6fd;
        pointer-events: none;
    }
    .ent-justification-text {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #0369a1;
        font-style: italic;
        margin: 0;
        padding-right: 2rem;
    }

    /* Decision Panel (Sticky on Desktop) */
    .sticky-decision-panel {
        position: sticky;
        top: 20px;
    }
    .decision-card {
        border: 1px solid #cbd5e1;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.08);
    }
    .decision-notes-label {
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.5rem;
        display: block;
    }
    .decision-notes-textarea {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.75rem;
        font-size: 0.9rem;
        width: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .decision-notes-textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }
    .btn-action-group {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 1.25rem;
    }
    .btn-ent-primary {
        background-color: #10b981;
        border-color: #10b981;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: 0.025em;
        transition: all 0.2s;
    }
    .btn-ent-primary:hover {
        background-color: #059669;
        border-color: #059669;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    .btn-ent-danger {
        background-color: #ffffff;
        border-color: #f87171;
        color: #ef4444;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-ent-danger:hover {
        background-color: #fef2f2;
        border-color: #ef4444;
        color: #dc2626;
        transform: translateY(-1px);
    }
    .btn-ent-back {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-ent-back:hover {
        background-color: #f8fafc;
        color: #0f172a;
    }

    @media (max-width: 991.98px) {
        .ent-financial-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .ent-info-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        .sticky-decision-panel {
            position: relative;
            top: 0;
            margin-top: 1.5rem;
        }
    }
</style>
<section class="content-header">
<div class="container-fluid enterprise-review-wrapper">
    <!-- Header/Breadcrumb row -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 font-weight-bold text-dark mb-0">Review Scholarship Application</h1>
        </div>
        <div class="col-md-6 text-md-right mt-2 mt-md-0">
            <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.application.index') }}">Applications</a></li>
                <li class="breadcrumb-item active text-muted">Review</li>
            </ol>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="row">
        <!-- Details Column -->
        <div class="col-lg-8">
            <!-- Banner summary -->
            <div class="ent-banner-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="ent-student-name">{{ userName('Student', $application->student_id) }}</div>
                        <div class="text-primary font-weight-bold" style="font-size: 0.95rem;">
                            {{ optional(optional($application->studentIntakeCourseFee)->intakeCourse)->course->course_name ?? '—' }}
                        </div>
                        <div class="text-muted small mt-1">
                            Fee Bracket: <strong>{{ $application->studentIntakeCourseFee->name ?? '—' }}</strong>
                        </div>
                    </div>
                    <div>
                        <span class="ent-badge ent-badge-pending">
                            {{ $application->status_label }}
                        </span>
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
                        @if($application->student->mobile || $application->student->phone)
                            <div class="ent-meta-item">
                                <i class="fas fa-phone-alt"></i>
                                <span>{{ $application->student->mobile ?: $application->student->phone }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Financial Flow Grid -->
            <div class="ent-financial-grid">
                <!-- Original Fee Card -->
                <div class="ent-metric-card">
                    <div class="ent-metric-label">Original Course Fee</div>
                    <div class="ent-metric-value original">${{ number_format($fee, 2) }}</div>
                    <div class="ent-metric-icon text-primary"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="ent-metric-bar bar-original"></div>
                </div>
                <!-- Discount Card -->
                <div class="ent-metric-card">
                    <div class="ent-metric-label">Scholarship Discount</div>
                    <div class="ent-metric-value discount">−${{ number_format($discountAmount, 2) }}</div>
                    <div class="ent-metric-icon text-danger"><i class="fas fa-percentage"></i></div>
                    <div class="ent-metric-bar bar-discount"></div>
                </div>
                <!-- Net Payable Card -->
                <div class="ent-metric-card">
                    <div class="ent-metric-label">Net Payable Fee</div>
                    <div class="ent-metric-value net">${{ number_format($netFee, 2) }}</div>
                    <div class="ent-metric-icon text-success"><i class="fas fa-wallet"></i></div>
                    <div class="ent-metric-bar bar-net"></div>
                </div>
            </div>

            <!-- Scholarship Details Card -->
            <div class="ent-card">
                <div class="ent-card-header">
                    <h3 class="ent-card-title">
                        <i class="fas fa-graduation-cap"></i> Scholarship Program Details
                    </h3>
                </div>
                <div class="ent-card-body">
                    <div class="ent-info-grid">
                        <div class="ent-info-item">
                            <span class="ent-info-label">Program Name</span>
                            <span class="ent-info-value font-weight-bold">{{ $application->scholarship->name }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Award Value / Rate</span>
                            <span class="ent-info-value text-primary font-weight-bold">{{ $application->scholarship->value_display }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Scholarship Type</span>
                            <span class="ent-info-value">{{ $application->scholarship->type_label }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Scope</span>
                            <span class="ent-info-value">{{ $application->scholarship->award_scope_label }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Disbursement Scheme</span>
                            <span class="ent-info-value">{{ $application->scholarship->disbursement_label }}</span>
                        </div>
                        <div class="ent-info-item">
                            <span class="ent-info-label">Applied By (Staff)</span>
                            <span class="ent-info-value">{{ userName('User', $application->applied_by) }}</span>
                        </div>
                        @if($application->scholarship->requires_maintenance)
                        <div class="ent-info-item">
                            <span class="ent-info-label">Academic Maintenance Req.</span>
                            <span class="ent-info-value text-warning font-weight-bold">
                                Keep GPA/Percentage &ge; {{ rtrim(rtrim($application->scholarship->maintenance_min_percentage, '0'), '.') }}%
                            </span>
                        </div>
                        @endif
                        @if($application->scholarship->eligibility_criteria)
                        <div class="ent-info-item col-span-2" style="grid-column: span 2;">
                            <span class="ent-info-label">Eligibility Criteria</span>
                            <span class="ent-info-value">{{ $application->scholarship->eligibility_criteria }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Justification Block -->
            <div class="ent-card">
                <div class="ent-card-header">
                    <h3 class="ent-card-title">
                        <i class="fas fa-file-alt"></i> Applicant Justification / Statement of Need
                    </h3>
                </div>
                <div class="ent-card-body">
                    @if($application->justification)
                        <div class="ent-justification-block">
                            <div class="ent-justification-quote"><i class="fas fa-quote-right"></i></div>
                            <p class="ent-justification-text">"{{ $application->justification }}"</p>
                        </div>
                    @else
                        <span class="text-muted italic">No justification statement was submitted with this application.</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sticky Form/Decision Action Column -->
        <div class="col-lg-4">
            <div class="sticky-decision-panel">
                <div class="ent-card decision-card">
                    <div class="ent-card-header" style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <h3 class="ent-card-title" style="color: #0f172a;">
                            <i class="fas fa-gavel"></i> Evaluation Portal
                        </h3>
                    </div>
                    <form method="POST" id="decisionForm">
                        @csrf
                        <div class="ent-card-body">
                            <label for="review_notes" class="decision-notes-label">Decision & Review Notes</label>
                            <textarea name="review_notes" id="review_notes" class="decision-notes-textarea" rows="5"
                                placeholder="Enter evaluation notes here. Rejection notes are required. Notes will be archived for audit tracking."></textarea>

                            <div class="btn-action-group">
                                <button type="submit" class="btn btn-ent-primary py-3"
                                    formaction="{{ route('admin.scholarship.application.approve', $application->id) }}"
                                    onclick="return submitDecision('approve')">
                                    <i class="fas fa-check-circle mr-1"></i> Approve &amp; Release
                                </button>

                                <button type="submit" class="btn btn-ent-danger py-2"
                                    formaction="{{ route('admin.scholarship.application.reject', $application->id) }}"
                                    onclick="return submitDecision('reject')">
                                    <i class="fas fa-times-circle mr-1"></i> Reject Application
                                </button>

                                <a href="{{ route('admin.scholarship.application.index') }}" class="btn btn-ent-back py-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Return to List
                                </a>
                            </div>
                        </div>
                    </form>
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
                <h1>Review Scholarship Application</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.application.index') }}">Applications</a></li>
                    <li class="breadcrumb-item active">Review</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">

                {{-- Hero summary --}}
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-start">
                            <div class="mr-3 mb-2">
                                <h4 class="mb-1">{{ userName('Student', $application->student_id) }}</h4>
                                <div class="text-muted">
                                    {{ optional(optional($application->studentIntakeCourseFee)->intakeCourse)->course->course_name ?? '-' }}
                                    <span class="mx-1">·</span>
                                    {{ $application->studentIntakeCourseFee->name ?? '-' }}
                                </div>
                                <div class="mt-1">
                                    <i class="fas fa-graduation-cap text-primary mr-1"></i>
                                    <strong>{{ $application->scholarship->name }}</strong>
                                    <span class="text-muted">— {{ $application->scholarship->value_display }}</span>
                                </div>
                            </div>
                            <span class="badge badge-{{ $application->status_class }} p-2" style="font-size:.95rem;">
                                {{ $application->status_label }}
                            </span>
                        </div>

                        {{-- Financial flow --}}
                        <div class="row text-center mt-3 pt-3" style="border-top:1px solid #e9ecef;">
                            <div class="col">
                                <div class="text-muted text-uppercase small">Original Fee</div>
                                <div class="h4 mb-0">${{ number_format($fee, 2) }}</div>
                            </div>
                            <div class="col-auto align-self-center text-muted">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="col">
                                <div class="text-muted text-uppercase small">Discount</div>
                                <div class="h4 mb-0 text-danger">−${{ number_format($discountAmount, 2) }}</div>
                            </div>
                            <div class="col-auto align-self-center text-muted">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="col">
                                <div class="text-muted text-uppercase small">Net Fee</div>
                                <div class="h4 mb-0 text-success">${{ number_format($netFee, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-info-circle mr-1"></i> Application Details</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row mb-0">
                                    <dt class="col-5 text-muted">Type</dt>
                                    <dd class="col-7">{{ $application->scholarship->type_label }}</dd>
                                    <dt class="col-5 text-muted">Value</dt>
                                    <dd class="col-7">{{ $application->scholarship->value_display }}</dd>
                                    <dt class="col-5 text-muted">Applied By</dt>
                                    <dd class="col-7">{{ userName('User', $application->applied_by) }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row mb-0">
                                    <dt class="col-5 text-muted">Justification</dt>
                                    <dd class="col-7">{{ $application->justification ?: '-' }}</dd>
                                    @if($application->scholarship->eligibility_criteria)
                                    <dt class="col-5 text-muted">Eligibility</dt>
                                    <dd class="col-7">{{ $application->scholarship->eligibility_criteria }}</dd>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Decision --}}
                <div class="card card-outline card-primary">
                    <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-gavel mr-1"></i> Decision</h3></div>
                    <form method="POST" id="decisionForm">
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label for="review_notes">Decision Notes</label>
                                <textarea name="review_notes" id="review_notes" class="form-control" rows="3"
                                    placeholder="Optional for approval — required when rejecting"></textarea>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.scholarship.application.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <div>
                                <button type="submit" class="btn btn-danger mr-2"
                                    formaction="{{ route('admin.scholarship.application.reject', $application->id) }}"
                                    onclick="return submitDecision('reject')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                <button type="submit" class="btn btn-success"
                                    formaction="{{ route('admin.scholarship.application.approve', $application->id) }}"
                                    onclick="return submitDecision('approve')">
                                    <i class="fas fa-check"></i> Approve &amp; Apply Discount
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
@endif
@endsection

@section('scripts')
<script>
    function submitDecision(action) {
        var notes = document.getElementById('review_notes').value.trim();
        if (action === 'reject') {
            if (!notes) {
                alert('Please provide a rejection reason in Decision Notes.');
                document.getElementById('review_notes').focus();
                return false;
            }
            return confirm('Reject this scholarship application?');
        }
        return confirm('Approve this scholarship? A fee discount of ${{ number_format($discountAmount, 2) }} will be applied.');
    }
</script>
@endsection

