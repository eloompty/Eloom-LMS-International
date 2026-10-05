@extends('user::layouts.master')
@section('title', 'Admin | Edit Payment')

@section('content')
<style>
    .fee-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }
    .fee-summary-row:last-child { border-bottom: none; }
    .fee-summary-row .label-col { color: #6c757d; }
    .fee-summary-row .value-col { font-weight: 600; color: #343a40; }
    .fee-summary-row.discount .value-col { color: #dc3545; }
    .fee-summary-row.total-row {
        padding-top: 12px;
        margin-top: 4px;
        border-top: 2px solid #dee2e6;
        border-bottom: none;
    }
    .fee-summary-row.total-row .label-col { font-weight: 700; color: #343a40; font-size: 15px; }
    .fee-summary-row.total-row .value-col { font-size: 18px; color: #343a40; }
    .student-meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 13px;
        color: #495057;
        margin-right: 8px;
        margin-bottom: 6px;
    }
    .student-meta-badge i { color: #6c757d; font-size: 12px; }
    .student-banner {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .student-banner .student-name {
        font-size: 18px;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 8px;
    }
    .form-section-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #6c757d;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f0f0f0;
    }
    .pay-input-group .input-group-text {
        background: #f8f9fa;
        border-color: #ced4da;
        color: #6c757d;
        font-size: 13px;
    }
    .pay-input-group .form-control { font-size: 14px; }
    .paid-amount-field { font-size: 16px !important; font-weight: 600; }
    .form-label-pay {
        font-size: 12px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .required-star { color: #dc3545; }
    .card-pay { border: 1px solid #e9ecef; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
    .card-pay .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        border-radius: 8px 8px 0 0;
        padding: 14px 20px;
    }
    .card-pay .card-header h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #343a40;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .card-pay .card-header h5 i { margin-right: 8px; }
    .action-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 20px;
        border-top: 1px solid #f0f0f0;
        background: #fff;
        border-radius: 0 0 8px 8px;
    }
    .btn-record-payment {
        padding: 9px 28px;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.3px;
        border-radius: 6px;
    }
    .file-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 6px;
        padding: 12px 16px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s;
        position: relative;
    }
    .file-upload-area:hover { border-color: #007bff; }
    .file-upload-area input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .file-upload-area .upload-icon { font-size: 20px; color: #adb5bd; display: block; margin-bottom: 4px; }
    .file-upload-area .upload-text { font-size: 12px; color: #6c757d; }
    .file-upload-area .upload-text span { color: #007bff; font-weight: 600; }
    #file-name-display { font-size: 12px; color: #495057; margin-top: 6px; }
    .recorded-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
</style>

@if ($text = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i><strong>{{ $text }}</strong>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i><strong>{{ $text }}</strong>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<section class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:22px;font-weight:700;">Edit Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.fee.index', $student->id) }}">Fees</a></li>
                    <li class="breadcrumb-item active">Edit Payment</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        {{-- Student + Installment Context Banner --}}
        <div class="student-banner">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="margin-bottom:8px;">
                <div class="student-name" style="margin-bottom:0;">
                    <i class="fas fa-user-graduate mr-2" style="color:#007bff;"></i>{{ userName('Student', $student->id) }}
                </div>
                <span class="recorded-badge">
                    <i class="fas fa-check-circle"></i> Payment Recorded
                </span>
            </div>
            <div>
                <span class="student-meta-badge">
                    <i class="fas fa-file-invoice-dollar"></i>
                    {{ $installment->studentIntakeCourseFee->name }}
                </span>
                <span class="student-meta-badge">
                    <i class="fas fa-layer-group"></i>
                    {{ $installment->name }}
                </span>
                @if($installment->due_date)
                <span class="student-meta-badge">
                    <i class="fas fa-calendar-alt"></i>
                    Due: {{ dateFormat($installment->due_date) }}
                </span>
                @endif
                <span class="student-meta-badge">
                    <i class="fas fa-university"></i>
                    {{ $installment->studentIntakeCourseFee->intakeCourse->course->course_name ?? '' }}
                </span>
                @if($installment->studentIntakeCourseFeePayment->paid_date)
                <span class="student-meta-badge">
                    <i class="fas fa-calendar-check"></i>
                    Paid: {{ dateFormat($installment->studentIntakeCourseFeePayment->paid_date) }}
                </span>
                @endif
            </div>
        </div>

        <form id="editpayment" action="{{ route('admin.student.fee.installment.payment.update', $installment->studentIntakeCourseFeePayment->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                {{-- LEFT: Fee Breakdown (historical) --}}
                <div class="col-lg-4 col-md-5">
                    <div class="card card-pay mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-receipt text-primary"></i>Fee Breakdown</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">

                            @if(count($fee_types) > 0)
                                @foreach($fee_types as $fee_type)
                                <div class="fee-summary-row">
                                    <span class="label-col">{{ ucwords(str_replace('_', ' ', $fee_type->key)) }}</span>
                                    <span class="value-col">${{ number_format($fee_type->value, 2) }}</span>
                                </div>
                                @endforeach
                            @else
                                <div class="fee-summary-row">
                                    <span class="label-col">Semester Fee</span>
                                    <span class="value-col">${{ number_format($installment->amount, 2) }}</span>
                                </div>
                            @endif

                            @if($extra_fee > 0)
                                @foreach($installment->studentCourseFeeInstallments as $fee_installment)
                                <div class="fee-summary-row">
                                    <span class="label-col">{{ $fee_installment->name }}</span>
                                    <span class="value-col">${{ number_format($fee_installment->amount, 2) }}</span>
                                </div>
                                @endforeach
                            @endif

                            @if(isset($scholarship_discount) && $scholarship_discount > 0)
                            <div class="fee-summary-row discount">
                                <span class="label-col">
                                    <i class="fas fa-tag mr-1" style="color:#dc3545;font-size:11px;"></i>Scholarship Discount
                                </span>
                                <span class="value-col">-${{ number_format($scholarship_discount, 2) }}</span>
                            </div>
                            @endif

                            <div class="fee-summary-row total-row">
                                <span class="label-col">Recorded Total</span>
                                <span class="value-col">${{ number_format($installment->studentIntakeCourseFeePayment->total_amount, 2) }}</span>
                            </div>

                            <input type="hidden" name="total_amount" value="{{ $installment->studentIntakeCourseFeePayment->total_amount }}">
                        </div>
                    </div>

                    {{-- Existing receipt preview --}}
                    @if($installment->studentIntakeCourseFeePayment->receipt)
                    <div class="card card-pay mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-image text-info"></i>Current Receipt</h5>
                        </div>
                        <div class="card-body text-center" style="padding:16px;">
                            <img src="{{ asset($installment->studentIntakeCourseFeePayment->receipt) }}"
                                 alt="Receipt" class="img-fluid rounded"
                                 style="max-height:200px;object-fit:contain;border:1px solid #e9ecef;padding:4px;">
                            <p class="mt-2 mb-0" style="font-size:11px;color:#6c757d;">Upload a new file below to replace</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- RIGHT: Edit Payment Form --}}
                <div class="col-lg-8 col-md-7">
                    <div class="card card-pay mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-edit text-warning"></i>Payment Details</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">

                            <p class="form-section-title">Amount</p>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-pay">Paid Amount <span class="required-star">*</span></label>
                                    <div class="input-group pay-input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0.01" name="paid_amount" id="paid_amount"
                                               class="form-control paid-amount-field"
                                               value="{{ $installment->studentIntakeCourseFeePayment->paid_amount }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-pay">Taxable Amount</label>
                                    <div class="input-group pay-input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" name="taxable_amount" id="taxable_amount"
                                               class="form-control"
                                               value="{{ $installment->studentIntakeCourseFeePayment->taxable_amount }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-pay">Remaining Amount</label>
                                    <div class="input-group pay-input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" name="remaining_amount" id="remaining_amount"
                                               class="form-control"
                                               value="{{ $installment->studentIntakeCourseFeePayment->remaining_amount }}">
                                    </div>
                                </div>
                            </div>

                            <p class="form-section-title mt-3">Dates</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-pay">Received Date <span class="required-star">*</span></label>
                                    <input type="date" name="received_date" id="received_date"
                                           class="form-control"
                                           value="{{ $installment->studentIntakeCourseFeePayment->received_date }}"
                                           required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-pay">Actual Paid Date <span class="required-star">*</span></label>
                                    <input type="date" name="paid_date" id="paid_date"
                                           class="form-control"
                                           value="{{ $installment->studentIntakeCourseFeePayment->paid_date }}"
                                           required>
                                </div>
                            </div>

                            <p class="form-section-title mt-3">Payment Method &amp; Docs</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-pay">Payment Type <span class="required-star">*</span></label>
                                    <select class="form-control select2-payment" name="payment_type" id="payment_type" required style="width:100%;">
                                        <option value="">Select payment type…</option>
                                        @foreach($payments as $payment)
                                        <option value="{{ $payment->name }}"
                                            @if($installment->studentIntakeCourseFeePayment->payment_type == $payment->name) selected @endif>
                                            {{ $payment->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-pay">Upload New Receipt</label>
                                    <div class="file-upload-area" id="upload-area">
                                        <input type="file" name="receipt" id="receipt" accept="image/*,.pdf">
                                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                        <div class="upload-text"><span>Browse</span> or drag &amp; drop</div>
                                    </div>
                                    <div id="file-name-display"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label class="form-label-pay">Notes / Comment</label>
                                    <textarea name="comment" id="comment" rows="3"
                                              class="form-control"
                                              placeholder="Optional notes about this payment…"
                                              style="font-size:14px;resize:vertical;">@if($installment->studentIntakeCourseFeePayment->paymentnotes->first()){{ $installment->studentIntakeCourseFeePayment->paymentnotes->first()->notes }}@endif</textarea>
                                </div>
                            </div>

                        </div>
                        <div class="action-footer">
                            <a href="{{ route('admin.student.fee.index', $student->id) }}"
                               class="btn btn-outline-secondary btn-record-payment">
                                <i class="fas fa-times mr-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning btn-record-payment" style="color:#fff;">
                                <i class="fas fa-save mr-1"></i>Save Changes
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    $('.select2-payment').select2({
        theme: 'bootstrap4',
        placeholder: 'Select payment type…',
        allowClear: true
    });

    document.getElementById('receipt').addEventListener('change', function () {
        var name = this.files.length ? this.files[0].name : '';
        document.getElementById('file-name-display').textContent = name;
    });

    $('#editpayment').validate({
        rules: {
            paid_amount: { required: true, min: 0.01 },
            received_date: { required: true },
            paid_date: { required: true },
            payment_type: { required: true },
        },
        messages: {
            paid_amount: 'Please enter the paid amount',
            received_date: 'Please select the received date',
            paid_date: 'Please select the actual paid date',
            payment_type: 'Please select a payment type',
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element) { $(element).addClass('is-invalid'); },
        unhighlight: function (element) { $(element).removeClass('is-invalid'); }
    });
</script>
@endsection
