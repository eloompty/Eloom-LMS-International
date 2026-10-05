@extends('user::layouts.master')
@section('title', 'Admin | Student Fees')

@section('header-script')
<style>
:root {
    --fee-primary: #4361ee;
    --fee-primary-light: #eef1ff;
    --fee-success: #10b981;
    --fee-success-light: #ecfdf5;
    --fee-warning: #f59e0b;
    --fee-warning-light: #fffbeb;
    --fee-danger: #ef4444;
    --fee-danger-light: #fef2f2;
    --fee-info: #06b6d4;
    --fee-info-light: #ecfeff;
    --fee-purple: #8b5cf6;
    --fee-purple-light: #f5f3ff;
    --fee-gray-50: #f9fafb;
    --fee-gray-100: #f3f4f6;
    --fee-gray-200: #e5e7eb;
    --fee-gray-300: #d1d5db;
    --fee-gray-500: #6b7280;
    --fee-gray-700: #374151;
    --fee-gray-900: #111827;
    --fee-radius: 12px;
    --fee-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --fee-shadow-md: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -1px rgba(0,0,0,.04);
}
/* Page Header */
.sfee-page-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:24px; }
.sfee-page-header h1 { font-size:1.5rem; font-weight:700; color:var(--fee-gray-900); margin:0; display:flex; align-items:center; gap:10px; }
.sfee-page-header h1 i { color:var(--fee-primary); font-size:1.3rem; }
.sfee-btn-add { background:var(--fee-success); color:#fff; border:none; padding:9px 22px; border-radius:8px; font-weight:600; font-size:.88rem; transition:all .2s; box-shadow:var(--fee-shadow); display:inline-flex; align-items:center; gap:6px; text-decoration:none; }
.sfee-btn-add:hover { background:#059669; color:#fff; transform:translateY(-1px); box-shadow:var(--fee-shadow-md); text-decoration:none; }

/* Fee Course Card */
.sfee-course-card { background:#fff; border-radius:var(--fee-radius); box-shadow:var(--fee-shadow); border:1px solid var(--fee-gray-200); margin-bottom:24px; overflow:hidden; transition:box-shadow .2s; }
.sfee-course-card:hover { box-shadow:var(--fee-shadow-md); }
.sfee-course-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; padding:16px 20px; background:linear-gradient(135deg, var(--fee-primary) 0%, #6366f1 100%); color:#fff; }
.sfee-course-header .course-title { font-size:1.1rem; font-weight:700; display:flex; align-items:center; gap:8px; }
.sfee-course-header .course-title i { font-size:1rem; opacity:.85; }
.sfee-course-actions { display:flex; gap:8px; flex-wrap:wrap; }
.sfee-course-actions .btn-action { background:rgba(255,255,255,.2); color:#fff; border:1px solid rgba(255,255,255,.3); padding:6px 14px; border-radius:6px; font-size:.8rem; font-weight:600; transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; gap:4px; }
.sfee-course-actions .btn-action:hover { background:rgba(255,255,255,.35); color:#fff; text-decoration:none; }

/* Summary Stats */
.sfee-stats { display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:12px; padding:20px; border-bottom:1px solid var(--fee-gray-200); }
.sfee-stat { display:flex; flex-direction:column; gap:2px; }
.sfee-stat-label { font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:var(--fee-gray-500); }
.sfee-stat-value { font-size:1.05rem; font-weight:700; color:var(--fee-gray-900); }
.sfee-stat-value.text-success { color:var(--fee-success) !important; }
.sfee-stat-value.text-danger { color:var(--fee-danger) !important; }
.sfee-stat-value.text-warning { color:var(--fee-warning) !important; }
.sfee-stat-value.text-purple { color:var(--fee-purple) !important; }

/* Scholarship Action Bar */
.sfee-scholarship-bar { padding:14px 20px; border-bottom:1px solid var(--fee-gray-200); display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.sfee-btn-scholarship { background:var(--fee-warning-light); color:#b45309; border:1px solid #fcd34d; padding:6px 14px; border-radius:6px; font-size:.8rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all .2s; }
.sfee-btn-scholarship:hover { background:var(--fee-warning); color:#fff; text-decoration:none; }
.sfee-scholarship-applied { display:inline-flex; align-items:center; gap:6px; font-size:.8rem; font-weight:600; padding:5px 12px; border-radius:20px; background:var(--fee-success-light); color:#047857; }

/* Installment Table */
.sfee-table-wrap { padding:0; }
.sfee-table { width:100%; border-collapse:collapse; }
.sfee-table thead th { background:var(--fee-gray-50); padding:12px 16px; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--fee-gray-500); border-bottom:2px solid var(--fee-gray-200); text-align:left; white-space:nowrap; }
.sfee-table tbody td { padding:14px 16px; border-bottom:1px solid var(--fee-gray-100); font-size:.88rem; color:var(--fee-gray-700); vertical-align:top; }
.sfee-table tbody tr:hover { background:var(--fee-gray-50); }
.sfee-table tbody tr:last-child td { border-bottom:none; }

/* Installment Number */
.sfee-inst-num { display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; border-radius:50%; background:var(--fee-primary-light); color:var(--fee-primary); font-weight:700; font-size:.78rem; }

/* Amount Details */
.sfee-amount-detail { line-height:1.7; }
.sfee-amount-detail .fee-line { display:flex; gap:6px; align-items:baseline; flex-wrap:wrap; }
.sfee-amount-detail .fee-line b { color:var(--fee-gray-700); font-weight:600; font-size:.82rem; }
.sfee-amount-detail .fee-total { margin-top:4px; padding-top:4px; border-top:1px dashed var(--fee-gray-300); font-weight:700; color:var(--fee-gray-900); }
.sfee-amount-detail .fee-total .orig { text-decoration:line-through; color:var(--fee-gray-500); font-weight:400; margin-right:6px; }
.sfee-amount-detail .fee-total .net { color:var(--fee-success); }

/* Status Badges */
.sfee-badge { display:inline-flex; align-items:center; gap:4px; font-size:.75rem; font-weight:600; padding:4px 12px; border-radius:20px; }
.sfee-badge.pending { background:var(--fee-warning-light); color:#b45309; }
.sfee-badge.paid { background:var(--fee-success-light); color:#047857; }
.sfee-badge.refunded { background:var(--fee-danger-light); color:#b91c1c; }

/* Action Buttons */
.sfee-action-btns { display:flex; flex-wrap:wrap; gap:6px; }
.sfee-btn { padding:5px 12px; border-radius:6px; font-size:.78rem; font-weight:600; border:none; transition:all .15s; display:inline-flex; align-items:center; gap:4px; cursor:pointer; text-decoration:none; }
.sfee-btn-pay { background:var(--fee-success); color:#fff; }
.sfee-btn-pay:hover { background:#059669; color:#fff; text-decoration:none; }
.sfee-btn-receipt { background:var(--fee-primary-light); color:var(--fee-primary); }
.sfee-btn-receipt:hover { background:var(--fee-primary); color:#fff; text-decoration:none; }
.sfee-btn-edit { background:var(--fee-warning-light); color:#b45309; }
.sfee-btn-edit:hover { background:var(--fee-warning); color:#fff; text-decoration:none; }
.sfee-btn-refund { background:var(--fee-danger-light); color:var(--fee-danger); }
.sfee-btn-refund:hover { background:var(--fee-danger); color:#fff; }
.sfee-btn-view { background:var(--fee-info-light); color:#0e7490; }
.sfee-btn-view:hover { background:var(--fee-info); color:#fff; text-decoration:none; }

/* Empty State */
.sfee-empty { padding:40px 20px; text-align:center; color:var(--fee-gray-500); }
.sfee-empty i { font-size:2.5rem; margin-bottom:12px; opacity:.4; display:block; }
.sfee-empty p { font-size:.95rem; font-weight:500; }

/* Modal Overrides */
.sfee-modal .modal-content { border-radius:var(--fee-radius); border:none; box-shadow:0 20px 40px rgba(0,0,0,.15); }
.sfee-modal .modal-header { border-bottom:1px solid var(--fee-gray-200); padding:16px 20px; }
.sfee-modal .modal-header h4 { font-size:1.05rem; font-weight:700; color:var(--fee-gray-900); }
.sfee-modal .modal-body { padding:20px; }
.sfee-modal .modal-footer { border-top:1px solid var(--fee-gray-200); padding:12px 20px; }
.sfee-modal .form-group label { font-size:.82rem; font-weight:600; color:var(--fee-gray-700); }
.sfee-modal .form-control { border-radius:8px; border:1px solid var(--fee-gray-300); }
.sfee-modal .form-control:focus { border-color:var(--fee-primary); box-shadow:0 0 0 3px rgba(67,97,238,.15); }

/* Refund Receipt Table */
.sfee-refund-table { width:100%; }
.sfee-refund-table th { text-align:left; padding:10px 14px; font-size:.82rem; font-weight:600; color:var(--fee-gray-700); background:var(--fee-gray-50); border-bottom:1px solid var(--fee-gray-200); width:35%; }
.sfee-refund-table td { padding:10px 14px; font-size:.88rem; color:var(--fee-gray-700); border-bottom:1px solid var(--fee-gray-100); }

/* Responsive */
@media(max-width:992px) { .sfee-stats { grid-template-columns:repeat(3,1fr); } }
@media(max-width:768px) { .sfee-stats { grid-template-columns:repeat(2,1fr); } .sfee-course-header { flex-direction:column; align-items:flex-start; } .sfee-table-wrap { overflow-x:auto; } }
@media(max-width:576px) { .sfee-stats { grid-template-columns:1fr 1fr; gap:10px; } .sfee-page-header { flex-direction:column; align-items:flex-start; } }
</style>
@endsection

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" style="border-radius:8px;border-left:4px solid var(--fee-success);">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong><i class="fas fa-check-circle mr-1"></i>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-dismissible fade show" style="border-radius:8px;border-left:4px solid var(--fee-danger);">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong><i class="fas fa-exclamation-circle mr-1"></i>{{ $text }}</strong>
</div>
@endif

<section class="content-header">
    <div class="container-fluid">
        <div class="sfee-page-header">
            <h1><i class="fas fa-file-invoice-dollar"></i> {{ userName('Student', $student->id) }}'s Fees</h1>
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('admin.student.fee.create', $student->id) }}" class="sfee-btn-add"><i class="fas fa-plus"></i> Add Fee</a>
                <ol class="breadcrumb" style="margin:0;background:transparent;padding:0;font-size:.82rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">Fees</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @forelse ($fees as $index => $fee)
        <div class="sfee-course-card">
            {{-- Course Header --}}
            <div class="sfee-course-header">
                <div class="course-title">
                    <i class="fas fa-graduation-cap"></i>
                    {{ $fee->intakeCourse->course->course_name }} — {{ $fee->name }}
                </div>
                <div class="sfee-course-actions">
                    <a href="{{ route('admin.student.fee.installment.edit', $fee->id) }}" class="btn-action"><i class="fas fa-pencil-alt"></i> Edit</a>
                    <a href="{{ route('admin.student.fee.statement', $fee->id) }}" class="btn-action"><i class="fas fa-file-alt"></i> Statement</a>
                    <a href="{{ route('admin.student.fee.statement.print', $fee->id) }}" class="btn-action"><i class="fas fa-print"></i> Print Statement</a>
                </div>
            </div>

            {{-- Summary Stats --}}
            <div class="sfee-stats">
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Fee</span>
                    <span class="sfee-stat-value">${{ number_format($fee->fee, 2) }}</span>
                </div>
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Total Fee</span>
                    <span class="sfee-stat-value">${{ number_format($fee->total_fee, 2) }}</span>
                </div>
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Remaining Setup</span>
                    <span class="sfee-stat-value text-warning">${{ number_format($fee->remaining, 2) }}</span>
                </div>
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Start Date</span>
                    <span class="sfee-stat-value" style="font-size:.92rem;">{{ dateFormat($fee->intakeCourse->starting_date) }}</span>
                </div>
                <div class="sfee-stat">
                    <span class="sfee-stat-label">End Date</span>
                    <span class="sfee-stat-value" style="font-size:.92rem;">{{ dateFormat($fee->intakeCourse->ending_date) }}</span>
                </div>
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Paid Amount</span>
                    <span class="sfee-stat-value text-success">${{ number_format($fee->paid_installment, 2) }}</span>
                </div>
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Remaining Payment</span>
                    <span class="sfee-stat-value text-danger">${{ number_format($fee->remaining_installment, 2) }}</span>
                </div>
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Refunded</span>
                    <span class="sfee-stat-value text-danger">${{ number_format($fee->refunded_amount, 2) }}</span>
                </div>
                @if(feeSetting('scholarship_module') == 'yes' && $fee->feeDiscounts->isNotEmpty())
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Scholarship Discount</span>
                    <span class="sfee-stat-value text-danger">-${{ number_format($fee->feeDiscounts->sum('discount_amount'), 2) }}</span>
                </div>
                <div class="sfee-stat">
                    <span class="sfee-stat-label">Net Fee</span>
                    <span class="sfee-stat-value text-success">${{ number_format($fee->fee - $fee->feeDiscounts->sum('discount_amount'), 2) }}</span>
                </div>
                @endif
            </div>

            {{-- Scholarship Action Bar --}}
            @if(feeSetting('scholarship_module') == 'yes' && checkRole('scholarship_application', 'add'))
            <div class="sfee-scholarship-bar">
                @if($fee->feeDiscounts->isEmpty())
                <a href="{{ route('admin.scholarship.application.create', [$student->id, $fee->id]) }}" class="sfee-btn-scholarship">
                    <i class="fas fa-graduation-cap"></i> Apply Scholarship
                </a>
                @else
                <span class="sfee-scholarship-applied"><i class="fas fa-check-circle"></i> Scholarship Applied</span>
                <a href="{{ route('admin.scholarship.application.index') }}" class="sfee-btn sfee-btn-view"><i class="fas fa-eye"></i> View Application</a>
                @endif
            </div>
            @endif

            {{-- Installments Table --}}
            @if($fee->fee_installments->count() > 0)
            <div class="sfee-table-wrap">
                <table class="sfee-table">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Name</th>
                            <th>Amount Breakdown</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fee->installments as $key => $value)
                        <tr>
                            <td><span class="sfee-inst-num">{{ $key + 1 }}</span></td>
                            <td style="font-weight:600;color:var(--fee-gray-900);">{{ $value->name }}</td>
                            <td>
                                <div class="sfee-amount-detail">
                                    @if ($key == 0)
                                        @foreach ($fee->fee_types as $fee_type)
                                        <div class="fee-line">
                                            <b>{{ ucwords(str_replace('_', ' ', $fee_type->key)) }}:</b> {{ $fee_type->value }}
                                        </div>
                                        @endforeach
                                    @endif
                                    @if ($value->studentCourseFeeInstallments->count() > 0)
                                        <div class="fee-line"><b>Semester Fee:</b> {{ $value->amount }}</div>
                                        @foreach($value->studentCourseFeeInstallments as $fee_installment)
                                        <div class="fee-line"><b>{{ $fee_installment->name }}:</b> {{ $fee_installment->amount }}</div>
                                        @endforeach
                                    @endif
                                    @php
                                        $raw_total = $value->studentCourseFeeInstallments->count() > 0 ? $value->extra_fee + $value->amount : $value->amount;
                                        $inst_discount = $value->scholarship_discount ?? 0;
                                        $net_total = $raw_total - $inst_discount;
                                    @endphp
                                    <div class="fee-total">
                                        @if($inst_discount > 0)
                                        <span class="orig">{{ $raw_total }}</span><span class="net">{{ number_format($net_total, 2) }}</span>
                                        @else
                                        {{ $raw_total }}
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td data-sort='{{ convertDate($value->due_date) }}'>
                                @if ($value->due_date == NULL)
                                    <span style="color:var(--fee-gray-500);">—</span>
                                @else
                                    {{ dateFormat($value->due_date) }}
                                    @if($value->status == 1 && $value->due_date < $today)
                                    <br><span style="font-size:.72rem;color:var(--fee-danger);font-weight:600;">Overdue</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($value->status == 1)
                                    <span class="sfee-badge pending"><i class="fas fa-clock" style="font-size:.65rem;"></i> Pending</span>
                                @elseif($value->status == 2)
                                    <span class="sfee-badge paid"><i class="fas fa-check-circle" style="font-size:.65rem;"></i> Paid</span>
                                    <br><span style="font-size:.72rem;color:var(--fee-gray-500);margin-top:2px;display:inline-block;">{{ dateFormat($value->studentIntakeCourseFeePayment->paid_date) }}</span>
                                @elseif($value->status == 3)
                                    <span class="sfee-badge refunded"><i class="fas fa-undo" style="font-size:.65rem;"></i> Refunded</span>
                                @endif
                            </td>
                            <td>
                                <div class="sfee-action-btns">
                                    @if ($value->status == 1)
                                        <a href="{{ route('admin.student.fee.installment.pay', $value->id) }}" class="sfee-btn sfee-btn-pay"><i class="fas fa-dollar-sign"></i> Pay</a>
                                    @elseif ($value->status == 2 || $value->status == 3)
                                        <a href="{{ route('admin.student.fee.installment.receipt', $value->id) }}" class="sfee-btn sfee-btn-receipt"><i class="fas fa-receipt"></i> Receipt</a>
                                        <a href="{{ route('admin.student.fee.installment.receipt.print', $value->id) }}" class="sfee-btn sfee-btn-receipt"><i class="fas fa-print"></i></a>
                                        <a href="{{ route('admin.student.fee.installment.pay.edit', $value->id) }}" class="sfee-btn sfee-btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                        @if ($value->status == 2)
                                        <button type="button" class="sfee-btn sfee-btn-refund" data-toggle="modal" data-target="#modal-refund{{ $value->id }}"><i class="fas fa-undo"></i> Refund</button>

                                        {{-- Refund Modal --}}
                                        <div class="modal fade sfee-modal" id="modal-refund{{ $value->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><i class="fas fa-undo mr-2" style="color:var(--fee-danger);"></i>{{ $value->name }} — Refund</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form id="payrefund" action="{{ route('admin.student.fee.installment.payment.refund', $value->studentIntakeCourseFeePayment->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Refund Amount <span class="text-danger">*</span></label>
                                                                <input type="text" name="refunded_amount" class="form-control" value="{{ $value->studentIntakeCourseFeePayment->paid_amount }}" readonly required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Comments</label>
                                                                <textarea name="comment" class="form-control" rows="3" placeholder="Optional comments..."></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Upload Receipt</label>
                                                                <input type="file" name="receipt" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Reinstate Payment</label>
                                                                <div><input type="checkbox" name="reinstate" value="1" style="width:18px;height:18px;cursor:pointer;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px;">Cancel</button>
                                                            <button type="submit" class="sfee-btn sfee-btn-refund" style="padding:8px 20px;"><i class="fas fa-undo"></i> Process Refund</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @elseif ($value->status == 3)
                                        <button type="button" class="sfee-btn sfee-btn-view" data-toggle="modal" data-target="#modal-refunded{{ $value->id }}"><i class="fas fa-eye"></i> Refund Info</button>

                                        {{-- Refund Receipt Modal --}}
                                        <div class="modal fade sfee-modal" id="modal-refunded{{ $value->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><i class="fas fa-receipt mr-2" style="color:var(--fee-info);"></i>{{ $value->name }} — Refund Receipt</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="sfee-refund-table">
                                                            <tr>
                                                                <th>Refunded Amount</th>
                                                                <td><span style="font-weight:700;color:var(--fee-danger);">${{ number_format($value->studentIntakeCourseFeePayment->paymentRefund->refunded_amount, 2) }}</span></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Refunded Date</th>
                                                                <td>{{ dateFormat($value->studentIntakeCourseFeePayment->created_at) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Receipt</th>
                                                                <td><img src="{{ asset($value->studentIntakeCourseFeePayment->paymentRefund->receipt) }}" alt="Refund Receipt" style="max-width:250px;border-radius:8px;border:1px solid var(--fee-gray-200);" /></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Comment</th>
                                                                <td>{{ $value->studentIntakeCourseFeePayment->paymentRefund->comment }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px;">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="sfee-empty">
                <i class="fas fa-inbox"></i>
                <p>No installments found for this fee</p>
            </div>
            @endif
        </div>
        @empty
        <div class="sfee-course-card">
            <div class="sfee-empty">
                <i class="fas fa-folder-open"></i>
                <p>No fees assigned to this student yet</p>
                <a href="{{ route('admin.student.fee.create', $student->id) }}" class="sfee-btn-add" style="margin-top:12px;"><i class="fas fa-plus"></i> Add First Fee</a>
            </div>
        </div>
        @endforelse
    </div>
</section>
@endsection
