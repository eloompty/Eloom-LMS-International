@extends('user::layouts.master')
@section('title', 'Admin | Fee Discounts — Revenue Impact')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif

@php
    $userTheme = Auth::guard('user')->user()->theme ?? 'light';
@endphp

@if ($userTheme === 'theme3')
<!-- Enterprise UI (Theme 3) -->
<style>
    .enterprise-discount-wrapper {
        padding: 1.5rem 0;
    }

    /* Modern Filter Bar Card */
    .ent-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }
    .ent-filter-body {
        padding: 1.25rem 1.5rem;
    }
    .ent-filter-form {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 1rem;
    }
    .ent-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        flex: 1 1 200px;
    }
    .ent-form-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .ent-form-input {
        height: 38px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: #1e293b;
        background-color: #ffffff;
        width: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .ent-form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }
    .ent-filter-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
    }
    .btn-ent-filter {
        background-color: #3b82f6;
        border: 1px solid #3b82f6;
        color: #ffffff;
        font-weight: 600;
        border-radius: 8px;
        height: 38px;
        padding: 0.5rem 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-ent-filter:hover {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }
    .btn-ent-reset {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-weight: 600;
        border-radius: 8px;
        height: 38px;
        padding: 0.5rem 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .btn-ent-reset:hover {
        background-color: #f8fafc;
        color: #0f172a;
        text-decoration: none;
    }

    /* Enterprise Metric Cards */
    .ent-metrics-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
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
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .ent-metric-value.danger { color: #dc2626; }
    .ent-metric-value.warning { color: #d97706; }
    .ent-metric-value.info { color: #2563eb; }
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
    .bar-danger { background-color: #dc2626; }
    .bar-warning { background-color: #d97706; }
    .bar-info { background-color: #2563eb; }

    /* DataTable container custom classes */
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
    }
    .ent-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
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

    .btn-ent-danger-sm {
        background-color: #ffffff;
        border: 1px solid #fecaca;
        color: #ef4444;
        font-weight: 600;
        border-radius: 6px;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s;
    }
    .btn-ent-danger-sm:hover {
        background-color: #fef2f2;
        border-color: #f87171;
        color: #dc2626;
        text-decoration: none;
    }

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
<div class="container-fluid enterprise-discount-wrapper">
    <!-- Header Row -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 font-weight-bold text-dark mb-0">Fee Discounts — Revenue Impact</h1>
        </div>
        <div class="col-md-6 text-md-right mt-2 mt-md-0">
            <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.index') }}">Scholarships</a></li>
                <li class="breadcrumb-item active text-muted">Revenue Impact</li>
            </ol>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="ent-filter-card">
        <div class="ent-filter-body">
            <form method="GET" action="{{ route('admin.scholarship.discount.index') }}" class="ent-filter-form">
                <div class="ent-form-group">
                    <label class="ent-form-label" for="type">Discount Type</label>
                    <select name="type" id="type" class="ent-form-input">
                        <option value="">All Types</option>
                        @foreach(['merit' => 'Merit', 'need_based' => 'Need Based', 'staff' => 'Staff', 'early_enrollment' => 'Early Enrollment', 'agent_negotiated' => 'Agent Negotiated', 'manual' => 'Manual'] as $key => $label)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ent-form-group">
                    <label class="ent-form-label" for="from_date">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="ent-form-input" value="{{ request('from_date') }}">
                </div>
                <div class="ent-form-group">
                    <label class="ent-form-label" for="to_date">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="ent-form-input" value="{{ request('to_date') }}">
                </div>
                <div class="ent-filter-actions">
                    <button type="submit" class="btn-ent-filter">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.scholarship.discount.index') }}" class="btn-ent-reset">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Metrics Row -->
    <div class="ent-metrics-row">
        <!-- Revenue Impact Metric Card -->
        <div class="ent-metric-card">
            <div class="ent-metric-label">Total Discounts Applied</div>
            <div class="ent-metric-value danger">${{ number_format($totalDiscount, 2) }}</div>
            <div class="ent-metric-icon text-danger"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="ent-metric-bar bar-danger"></div>
        </div>
        <!-- Beneficiaries Metric Card -->
        <div class="ent-metric-card">
            <div class="ent-metric-label">Unique Beneficiaries</div>
            <div class="ent-metric-value warning">{{ $discounts->unique('student_id')->count() }}</div>
            <div class="ent-metric-icon text-warning"><i class="fas fa-users"></i></div>
            <div class="ent-metric-bar bar-warning"></div>
        </div>
        <!-- Volume Metric Card -->
        <div class="ent-metric-card">
            <div class="ent-metric-label">Total Discount Records</div>
            <div class="ent-metric-value info">{{ $discounts->count() }}</div>
            <div class="ent-metric-icon text-primary"><i class="fas fa-clipboard-list"></i></div>
            <div class="ent-metric-bar bar-info"></div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="ent-card">
        <div class="ent-card-header">
            <h3 class="ent-card-title">Applied Discounts Listing</h3>
        </div>
        <div class="ent-card-body">
            @if(count($discounts) > 0)
            <div class="table-responsive">
                <table id="example1" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Scholarship Program</th>
                            <th>Type</th>
                            <th class="text-right">Original Fee</th>
                            <th class="text-right">Discount</th>
                            <th class="text-right">Net Fee</th>
                            <th>Approved By</th>
                            <th>Approved Date</th>
                            @if(checkRole('scholarship', 'delete'))<th class="text-center" style="width: 100px;">Action</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discounts as $discount)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="font-weight-bold">{{ userName('Student', $discount->student_id) }}</td>
                            <td>
                                {{ optional(optional(optional($discount->studentIntakeCourseFee)->intakeCourse)->course)->course_name ?? '-' }}
                            </td>
                            <td class="text-primary font-weight-bold">
                                {{ optional(optional($discount->scholarshipApplication)->scholarship)->name ?? 'Manual' }}
                            </td>
                            <td><span class="badge badge-secondary p-2">{{ $discount->type_label }}</span></td>
                            <td class="text-right font-weight-bold text-slate">${{ number_format($discount->studentIntakeCourseFee->fee ?? 0, 2) }}</td>
                            <td class="text-right font-weight-bold text-danger">-${{ number_format($discount->discount_amount, 2) }}</td>
                            <td class="text-right font-weight-bold text-success">
                                ${{ number_format(($discount->studentIntakeCourseFee->fee ?? 0) - $discount->discount_amount, 2) }}
                            </td>
                            <td>{{ userName('User', $discount->approved_by) }}</td>
                            <td data-sort="{{ $discount->approved_at ? $discount->approved_at->timestamp : 0 }}">
                                {{ $discount->approved_at ? dateFormat($discount->approved_at) : '-' }}
                            </td>
                            @if(checkRole('scholarship', 'delete'))
                            <td class="text-center">
                                <a href="{{ route('admin.scholarship.discount.delete', $discount->id) }}"
                                   class="btn-ent-danger-sm"
                                   onclick="return confirm('Revoke this discount? The student will lose the fee reduction.')">
                                    <i class="fas fa-times-circle"></i> Revoke
                                </a>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right"><strong>Total Discount:</strong></th>
                            <th class="text-right text-danger"><strong>-${{ number_format($totalDiscount, 2) }}</strong></th>
                            <th colspan="{{ checkRole('scholarship', 'delete') ? 4 : 3 }}"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-search mb-2" style="font-size: 2rem; opacity: 0.4;"></i>
                <h3 class="h5">No Discount Records Found</h3>
                <p class="mb-0">Try adjusting your filters above to find discount allocations.</p>
            </div>
            @endif
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
                <h1>Fee Discounts — Revenue Impact</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.index') }}">Scholarships</a></li>
                    <li class="breadcrumb-item active">Revenue Impact</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        {{-- Filters --}}
        <div class="card card-default">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.scholarship.discount.index') }}" class="form-inline flex-wrap">
                    <div class="form-group mr-3 mb-2">
                        <label class="mr-2">Type:</label>
                        <select name="type" class="form-control">
                            <option value="">All Types</option>
                            @foreach(['merit' => 'Merit', 'need_based' => 'Need Based', 'staff' => 'Staff', 'early_enrollment' => 'Early Enrollment', 'agent_negotiated' => 'Agent Negotiated', 'manual' => 'Manual'] as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-3 mb-2">
                        <label class="mr-2">From:</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group mr-3 mb-2">
                        <label class="mr-2">To:</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Filter</button>
                    <a href="{{ route('admin.scholarship.discount.index') }}" class="btn btn-default ml-2 mb-2">Reset</a>
                </form>
            </div>
        </div>

        {{-- Summary Card --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Discounts Applied</span>
                        <span class="info-box-number">${{ number_format($totalDiscount, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Students with Discounts</span>
                        <span class="info-box-number">{{ $discounts->unique('student_id')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Discount Records</span>
                        <span class="info-box-number">{{ $discounts->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Applied Discounts</h3>
                    </div>
                    <div class="card-body">
                        @if(count($discounts) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Scholarship</th>
                                    <th>Type</th>
                                    <th>Original Fee</th>
                                    <th>Discount</th>
                                    <th>Net Fee</th>
                                    <th>Approved By</th>
                                    <th>Date</th>
                                    @if(checkRole('scholarship', 'delete'))<th>Action</th>@endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($discounts as $discount)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ userName('Student', $discount->student_id) }}</td>
                                    <td>
                                        {{ optional(optional(optional($discount->studentIntakeCourseFee)->intakeCourse)->course)->course_name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ optional(optional($discount->scholarshipApplication)->scholarship)->name ?? 'Manual' }}
                                    </td>
                                    <td>{{ $discount->type_label }}</td>
                                    <td>${{ number_format($discount->studentIntakeCourseFee->fee ?? 0, 2) }}</td>
                                    <td class="text-danger font-weight-bold">-${{ number_format($discount->discount_amount, 2) }}</td>
                                    <td class="text-success">
                                        ${{ number_format(($discount->studentIntakeCourseFee->fee ?? 0) - $discount->discount_amount, 2) }}
                                    </td>
                                    <td>{{ userName('User', $discount->approved_by) }}</td>
                                    <td data-sort="{{ $discount->approved_at ? $discount->approved_at->timestamp : 0 }}">
                                        {{ $discount->approved_at ? dateFormat($discount->approved_at) : '-' }}
                                    </td>
                                    @if(checkRole('scholarship', 'delete'))
                                    <td>
                                        <a href="{{ route('admin.scholarship.discount.delete', $discount->id) }}"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Revoke this discount? The student will lose the fee reduction.')">
                                            <i class="fas fa-times"></i> Revoke
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-right"><strong>Total Discount:</strong></th>
                                    <th class="text-danger"><strong>-${{ number_format($totalDiscount, 2) }}</strong></th>
                                    <th colspan="{{ checkRole('scholarship', 'delete') ? 4 : 3 }}"></th>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Discount Records Found</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection

