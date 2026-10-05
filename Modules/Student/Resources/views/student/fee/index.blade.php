@extends('student::student.layouts.master')
@section('title', 'Student | Fee')

@section('header-script')
<style>
.fee-status-remaining { color:#d97706; background:#fef3c7; }
.fee-status-paid      { color:#065f46; background:#d1fae5; }
.fee-status-refunded  { color:#075985; background:#e0f2fe; }
.fee-amount-row { display:flex; flex-direction:column; gap:2px; }
.fee-amount-line { font-size:12px; color:#374151; }
.fee-amount-line b { font-weight:800; color:#111827; }
.fee-amount-total { font-size:12px; font-weight:800; color:#0f172a; border-top:1px dashed #e5e7eb; padding-top:4px; margin-top:2px; }
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Fees</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Fees</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @if($fees->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-file-invoice-dollar"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No fee records found.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Your fee information will appear here once it has been set up.</p>
            </div>
        </div>
        @endif

        @foreach($fees as $index => $fee)
        <div class="dashboard-panel mb-4">

            {{-- Panel header: course name + fee name --}}
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice-dollar"></i>
                    {{ $fee->intakeCourse->course->course_name }} &mdash; {{ $fee->name }}
                </h3>
            </div>

            {{-- KPI summary row --}}
            <div class="card-body" style="padding:16px 18px; border-bottom:1px solid #f1f5f9;">
                {{-- Dates row --}}
                <div class="d-flex flex-wrap mb-3" style="gap:18px;">
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        <i class="fas fa-calendar-alt mr-1" style="color:#2563eb;"></i>
                        Start: <span style="color:#374151;">{{ dateFormat($fee->intakeCourse->starting_date) }}</span>
                    </span>
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        <i class="fas fa-calendar-check mr-1" style="color:#059669;"></i>
                        End: <span style="color:#374151;">{{ dateFormat($fee->intakeCourse->ending_date) }}</span>
                    </span>
                </div>

                {{-- Metric cards --}}
                <div class="row">
                    <div class="col-6 col-sm-3 mb-3 mb-sm-0">
                        <div class="metric-card">
                            <div class="metric-icon metric-blue">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="metric-content">
                                <span class="metric-label">Total Fee</span>
                                <span class="metric-value">{{ $fee->total_fee }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 mb-3 mb-sm-0">
                        <div class="metric-card">
                            <div class="metric-icon metric-green">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="metric-content">
                                <span class="metric-label">Paid</span>
                                <span class="metric-value">{{ $fee->paid_installment }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 mb-3 mb-sm-0">
                        <div class="metric-card">
                            <div class="metric-icon metric-amber">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="metric-content">
                                <span class="metric-label">Remaining</span>
                                <span class="metric-value">{{ $fee->remaining_installment }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="metric-card">
                            <div class="metric-icon metric-cyan">
                                <i class="fas fa-undo-alt"></i>
                            </div>
                            <div class="metric-content">
                                <span class="metric-label">Refunded</span>
                                <span class="metric-value">{{ $fee->refunded_amount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Scholarship banner --}}
            @if(feeSetting('scholarship_module') == 'yes' && $fee->feeDiscounts->isNotEmpty())
            @php $totalDiscount = $fee->feeDiscounts->sum('discount_amount'); @endphp
            <div class="mx-3 mb-3 px-3 py-2" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; background:#bbf7d0; border-radius:6px; color:#059669; flex-shrink:0;">
                    <i class="fas fa-graduation-cap" style="font-size:11px;"></i>
                </span>
                <span style="font-size:12px; font-weight:800; color:#059669;">Scholarship Applied</span>
                <span style="font-size:12px; color:#374151; font-weight:700;">
                    Discount: <span style="color:#dc2626; font-weight:800;">-${{ number_format($totalDiscount, 2) }}</span>
                </span>
                <span style="color:#d1d5db; font-size:12px;">|</span>
                <span style="font-size:12px; color:#374151; font-weight:700;">
                    Net Payable: <span style="color:#059669; font-weight:800;">${{ number_format($fee->fee - $totalDiscount, 2) }}</span>
                </span>
            </div>
            @endif

            {{-- Installments table --}}
            @if($fee->installments->count() > 0)
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Installment</th>
                            <th>Amount Breakdown</th>
                            <th style="width:120px;">Due Date</th>
                            <th class="text-center" style="width:130px;">Status</th>
                            <th class="text-center" style="width:140px;">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fee->installments as $key => $value)
                        @php
                            $statusBadge    = $value->status == 2 ? 'fee-status-paid' : ($value->status == 3 ? 'fee-status-refunded' : 'fee-status-remaining');
                            $statusLabel    = studentPaymentStatus($value->status);
                            $totalAmt       = $value->studentCourseFeeInstallments->count() > 0
                                              ? $value->extra_fee + $value->amount
                                              : $value->amount;
                            $instDiscount   = $value->scholarship_discount ?? 0;
                            $netAmt         = $totalAmt - $instDiscount;
                        @endphp
                        <tr>
                            <td style="color:#94a3b8; font-weight:700;">{{ $key + 1 }}</td>
                            <td style="font-weight:700; font-size:13px;">{{ $value->name }}</td>
                            <td>
                                <div class="fee-amount-row">
                                    @if($key == 0)
                                        @foreach($fee->fee_types as $fee_type)
                                        <span class="fee-amount-line">
                                            <b>{{ ucwords(str_replace('_', ' ', $fee_type->key)) }}:</b> {{ $fee_type->value }}
                                        </span>
                                        @endforeach
                                    @endif
                                    @if($value->studentCourseFeeInstallments->count() > 0)
                                    <span class="fee-amount-line">
                                        <b>Semester Fee:</b> {{ $value->amount }}
                                    </span>
                                    @foreach($value->studentCourseFeeInstallments as $fi)
                                    <span class="fee-amount-line">
                                        <b>{{ $fi->name }}:</b> {{ $fi->amount }}
                                    </span>
                                    @endforeach
                                    @endif
                                    @if($instDiscount > 0)
                                    <span class="fee-amount-total">
                                        Total: <s style="color:#9ca3af; font-weight:400;">{{ $totalAmt }}</s>
                                        <span style="color:#059669; font-weight:800;">{{ number_format($netAmt, 2) }}</span>
                                        <span style="font-size:10px; color:#dc2626; font-weight:700;">&nbsp;(-{{ number_format($instDiscount, 2) }} scholarship)</span>
                                    </span>
                                    @else
                                    <span class="fee-amount-total">Total: {{ $totalAmt }}</span>
                                    @endif
                                </div>
                            </td>
                            <td data-sort="{{ convertDate($value->due_date) }}" style="font-size:12px; color:#374151;">
                                @if($value->due_date == null)
                                <span style="color:#94a3b8;">—</span>
                                @else
                                {{ dateFormat($value->due_date) }}
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="{{ $statusBadge }}"
                                      style="font-size:11px; font-weight:800; padding:3px 10px; border-radius:999px; display:inline-block;">
                                    {{ $statusLabel }}
                                </span>
                                @if($value->status == 2)
                                <div style="font-size:11px; color:#6b7280; margin-top:3px; font-weight:700;">
                                    {{ dateFormat($value->studentIntakeCourseFeePayment->paid_date) }}
                                </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($value->status == 2 || $value->status == 3)
                                <button type="button"
                                        class="panel-action"
                                        style="font-size:11px; padding:4px 10px; min-height:28px;"
                                        data-toggle="modal"
                                        data-target="#modal-paid-{{ $value->id }}">
                                    <i class="fas fa-receipt"></i> Receipt
                                </button>
                                @if($value->status == 3)
                                <button type="button"
                                        class="panel-action ml-1"
                                        style="font-size:11px; padding:4px 10px; min-height:28px; color:#dc2626; border-color:#fecaca;"
                                        data-toggle="modal"
                                        data-target="#modal-refunded-{{ $value->id }}">
                                    <i class="fas fa-undo-alt"></i> Refund
                                </button>
                                @endif
                                @else
                                <span style="font-size:12px; color:#94a3b8; font-weight:700;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="dashboard-empty" style="padding:24px;">
                <i class="fas fa-list-ol" style="font-size:20px;"></i>
                <p class="mb-0" style="font-weight:700; font-size:13px; color:#6b7280;">No installments found for this fee.</p>
            </div>
            @endif

        </div>{{-- /.dashboard-panel --}}

        {{-- Receipt modals (outside panel, per installment) --}}
        @foreach($fee->installments as $value)
        @if($value->status == 2 || $value->status == 3)

        {{-- Payment receipt modal --}}
        <div class="modal fade" id="modal-paid-{{ $value->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 20px 60px rgba(15,23,42,.15);">
                    <div class="modal-header" style="border-bottom:1px solid #e5e7eb; padding:16px 20px;">
                        <h5 class="modal-title" style="font-weight:800; font-size:15px; display:flex; align-items:center; gap:9px; margin:0;">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; background:#d1fae5; border-radius:6px; color:#059669;">
                                <i class="fas fa-receipt" style="font-size:12px;"></i>
                            </span>
                            {{ $value->name }} — Payment Receipt
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" style="opacity:.5;"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" style="padding:20px;">
                        <button class="btn btn-sm panel-action mb-3" onclick="printReceipt('printTable-{{ $value->id }}')">
                            <i class="fas fa-print mr-1"></i> Print / Save PDF
                        </button>
                        <table id="printTable-{{ $value->id }}" class="table dashboard-table" style="font-size:13px;">
                            <tbody>
                                <tr>
                                    <th style="width:40%; background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Total Amount</th>
                                    <td style="font-weight:700;">{{ $value->studentIntakeCourseFeePayment->total_amount }}</td>
                                </tr>
                                @if($value->enrollment_fee > 0)
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Enrollment Fee</th>
                                    <td style="font-weight:700;">{{ $value->enrollment_fee }}</td>
                                </tr>
                                @endif
                                @if($value->material_fee > 0)
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Material Fee</th>
                                    <td style="font-weight:700;">{{ $value->material_fee }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Paid Amount</th>
                                    <td style="font-weight:700; color:#059669;">{{ $value->studentIntakeCourseFeePayment->paid_amount }}</td>
                                </tr>
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Paid Date</th>
                                    <td style="font-weight:700;">{{ dateFormat($value->studentIntakeCourseFeePayment->paid_date) }}</td>
                                </tr>
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Remaining Amount</th>
                                    <td style="font-weight:700;">{{ $value->studentIntakeCourseFeePayment->remaining_amount }}</td>
                                </tr>
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Payment Type</th>
                                    <td style="font-weight:700;">{{ $value->studentIntakeCourseFeePayment->payment_type }}</td>
                                </tr>
                                @if($value->studentIntakeCourseFeePayment->receipt)
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Receipt Image</th>
                                    <td><img src="{{ asset($value->studentIntakeCourseFeePayment->receipt) }}" alt="Receipt" style="max-width:200px; border-radius:6px; border:1px solid #e5e7eb;"></td>
                                </tr>
                                @endif
                                @if($value->studentIntakeCourseFeePayment->paymentnotes->first())
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Notes</th>
                                    <td style="font-size:13px;">{{ $value->studentIntakeCourseFeePayment->paymentnotes->first()->notes }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e5e7eb; padding:14px 20px;">
                        <button type="button" class="panel-action" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Refund receipt modal --}}
        @if($value->status == 3)
        <div class="modal fade" id="modal-refunded-{{ $value->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 20px 60px rgba(15,23,42,.15);">
                    <div class="modal-header" style="border-bottom:1px solid #e5e7eb; padding:16px 20px;">
                        <h5 class="modal-title" style="font-weight:800; font-size:15px; display:flex; align-items:center; gap:9px; margin:0;">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; background:#e0f2fe; border-radius:6px; color:#0891b2;">
                                <i class="fas fa-undo-alt" style="font-size:12px;"></i>
                            </span>
                            {{ $value->name }} — Refund Receipt
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" style="opacity:.5;"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" style="padding:20px;">
                        <table class="table dashboard-table" style="font-size:13px;">
                            <tbody>
                                <tr>
                                    <th style="width:40%; background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Refunded Amount</th>
                                    <td style="font-weight:700; color:#0891b2;">{{ $value->studentIntakeCourseFeePayment->paymentRefund->refunded_amount }}</td>
                                </tr>
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Refunded Date</th>
                                    <td style="font-weight:700;">{{ dateFormat($value->studentIntakeCourseFeePayment->created_at) }}</td>
                                </tr>
                                @if($value->studentIntakeCourseFeePayment->paymentRefund->receipt)
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Receipt Image</th>
                                    <td><img src="{{ asset($value->studentIntakeCourseFeePayment->paymentRefund->receipt) }}" alt="Refund Receipt" style="max-width:200px; border-radius:6px; border:1px solid #e5e7eb;"></td>
                                </tr>
                                @endif
                                <tr>
                                    <th style="background:#f8fafc; font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Comment</th>
                                    <td style="font-size:13px;">{{ $value->studentIntakeCourseFeePayment->paymentRefund->comment }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e5e7eb; padding:14px 20px;">
                        <button type="button" class="panel-action" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @endif
        @endforeach

        @endforeach

    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    window.jsPDF = window.jspdf.jsPDF;

    function printReceipt(tableId) {
        var doc = new jsPDF();
        var el  = document.getElementById(tableId);
        if (!el) return;
        doc.html(el, {
            callback: function(d) { d.save('Receipt.pdf'); },
            x: 15, y: 15, width: 170, windowWidth: 650
        });
    }
</script>
@endsection
