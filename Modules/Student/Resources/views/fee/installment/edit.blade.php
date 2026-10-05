@extends('user::layouts.master')
@section('title', 'Admin | Edit Installment')

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
.fee-page-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:24px; }
.fee-page-header h1 { font-size:1.5rem; font-weight:700; color:var(--fee-gray-900); margin:0; display:flex; align-items:center; gap:10px; }
.fee-page-header h1 i { color:var(--fee-primary); font-size:1.3rem; }

/* Summary Grid */
.fee-summary-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:16px; margin-bottom:28px; }
.fee-stat-card { background:#fff; border-radius:var(--fee-radius); padding:18px 16px; box-shadow:var(--fee-shadow); border:1px solid var(--fee-gray-200); transition:box-shadow .2s, transform .2s; position:relative; overflow:hidden; }
.fee-stat-card:hover { box-shadow:var(--fee-shadow-md); transform:translateY(-2px); }
.fee-stat-card::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; border-radius:4px 0 0 4px; }
.fee-stat-card.s-primary::before { background:var(--fee-primary); }
.fee-stat-card.s-success::before { background:var(--fee-success); }
.fee-stat-card.s-warning::before { background:var(--fee-warning); }
.fee-stat-card.s-danger::before { background:var(--fee-danger); }
.fee-stat-card.s-info::before { background:var(--fee-info); }
.fee-stat-card.s-purple::before { background:var(--fee-purple); }
.fee-stat-label { font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:var(--fee-gray-500); margin-bottom:6px; }
.fee-stat-value { font-size:1.25rem; font-weight:700; color:var(--fee-gray-900); }
.fee-stat-card .fee-form-group { margin-top:8px; }
.fee-stat-card .icheck-info { margin-top:8px; }

/* Installment Cards */
.fee-installments-wrapper { display:flex; flex-direction:column; gap:20px; }
.fee-installment-card { background:#fff; border-radius:var(--fee-radius); box-shadow:var(--fee-shadow); border:1px solid var(--fee-gray-200); overflow:hidden; transition:box-shadow .2s; }
.fee-installment-card:hover { box-shadow:var(--fee-shadow-md); }
.fee-installment-card-header { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; background:var(--fee-gray-50); border-bottom:1px solid var(--fee-gray-200); }
.fee-installment-card-header .inst-number { font-weight:700; font-size:.9rem; color:var(--fee-primary); display:flex; align-items:center; gap:8px; }
.fee-installment-card-header .inst-number span { background:var(--fee-primary); color:#fff; width:26px; height:26px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:.75rem; }
.fee-installment-card-header .inst-badge { font-size:.7rem; padding:3px 10px; border-radius:20px; font-weight:600; }
.inst-badge.badge-pending { background:var(--fee-warning-light); color:#b45309; }
.inst-badge.badge-paid { background:var(--fee-success-light); color:#047857; }
.inst-badge.badge-refunded { background:var(--fee-danger-light); color:#b91c1c; }
.fee-installment-card-body { padding:20px; }
.fee-field-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; }

/* Section titles */
.fee-section-card { background:#fff; border-radius:var(--fee-radius); box-shadow:var(--fee-shadow); border:1px solid var(--fee-gray-200); margin-bottom:24px; overflow:hidden; }
.fee-section-head { padding:14px 20px; background:var(--fee-gray-50); border-bottom:1px solid var(--fee-gray-200); font-size:.95rem; font-weight:700; color:var(--fee-gray-900); display:flex; align-items:center; gap:8px; }
.fee-section-head i { color:var(--fee-primary); }
.fee-section-body { padding:20px; }

/* Form Controls */
.fee-form-group { margin-bottom:0; }
.fee-form-group label { font-size:.78rem; font-weight:600; color:var(--fee-gray-700); margin-bottom:4px; display:block; }
.fee-form-group .form-control { border-radius:8px; border:1px solid var(--fee-gray-300); font-size:.88rem; padding:8px 12px; transition:border-color .15s, box-shadow .15s; }
.fee-form-group .form-control:focus { border-color:var(--fee-primary); box-shadow:0 0 0 3px rgba(67,97,238,.15); }
.fee-form-group .form-control[readonly], .fee-form-group .form-control:disabled { background:var(--fee-gray-100); color:var(--fee-gray-500); }
.fee-form-group .required { color:var(--fee-danger); }

/* Buttons */
.btn-fee-primary { background:var(--fee-primary); color:#fff; border:none; padding:10px 28px; border-radius:8px; font-weight:600; font-size:.9rem; transition:all .2s; box-shadow:var(--fee-shadow); }
.btn-fee-primary:hover { background:#3651d4; transform:translateY(-1px); box-shadow:var(--fee-shadow-md); color:#fff; }
.btn-fee-add { background:#fff; color:var(--fee-gray-700); border:2px dashed var(--fee-gray-300); padding:10px 20px; border-radius:8px; font-weight:600; font-size:.85rem; transition:all .2s; display:inline-flex; align-items:center; gap:6px; }
.btn-fee-add:hover { border-color:var(--fee-primary); color:var(--fee-primary); background:var(--fee-primary-light); }
.btn-fee-delete { background:var(--fee-danger-light); color:var(--fee-danger); border:1px solid #fecaca; padding:6px 14px; border-radius:6px; font-weight:600; font-size:.8rem; transition:all .2s; display:inline-flex; align-items:center; gap:4px; }
.btn-fee-delete:hover { background:var(--fee-danger); color:#fff; }

/* Footer */
.fee-form-footer { display:flex; align-items:center; justify-content:flex-end; gap:12px; padding:20px; background:#fff; border-radius:var(--fee-radius); box-shadow:var(--fee-shadow); border:1px solid var(--fee-gray-200); margin-top:24px; }

/* Responsive */
@media(max-width:992px) { .fee-summary-grid { grid-template-columns:repeat(auto-fill, minmax(150px,1fr)); } .fee-field-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:576px) { .fee-summary-grid { grid-template-columns:repeat(2,1fr); } .fee-field-grid { grid-template-columns:1fr; } .fee-page-header { flex-direction:column; align-items:flex-start; } }
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
        <div class="fee-page-header">
            <h1><i class="fas fa-file-invoice-dollar"></i> Edit Fee Installments</h1>
            <ol class="breadcrumb" style="margin:0;background:transparent;padding:0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.student.fee.index', $student_intake_course_fee->student_id) }}">Fees</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form id="editfee" action="{{ route('admin.student.intake.course.fee.installment.update', $student_intake_course_fee->id) }}" method="POST">
            @csrf

            {{-- ===== FINANCIAL SUMMARY ===== --}}
            <div class="fee-summary-grid">
                <div class="fee-stat-card s-primary">
                    <div class="fee-stat-label">Fee</div>
                    <div class="fee-stat-value">${{ number_format($fee, 2) }}</div>
                </div>
                <div class="fee-stat-card s-primary">
                    <div class="fee-stat-label">Initial Fee</div>
                    <div class="fee-stat-value">${{ number_format($student_intake_course_fee->initial_fee, 2) }}</div>
                </div>
                <div class="fee-stat-card s-info">
                    <div class="fee-stat-label">Enrollment Fee</div>
                    <div class="fee-stat-value"><span id="enrollment_fee_display">${{ number_format($enrollment_fee, 2) }}</span></div>
                    <input type="hidden" id="enrollment_fee" value="{{ $enrollment_fee }}">
                    <div class="icheck-info d-inline">
                        <input type="checkbox" class="checkbox" id="enrollment_fee_wavier" name="enrollment_fee_wavier" value="1" @if ($enrollment_fee==0) checked @endif @if($paid_installment> 0) disabled @endif onclick="checkEnrollment(this)">
                        <label for="enrollment_fee_wavier" class="check enrollment_fee_wavier" style="font-size:.72rem;font-weight:600;color:var(--fee-gray-500);">Enrollment Fee Wavier</label>
                    </div>
                </div>
                <div class="fee-stat-card s-info">
                    <div class="fee-stat-label">Material Fee</div>
                    <div class="fee-stat-value"><span id="material_fee_display">${{ number_format($material_fee, 2) }}</span></div>
                    <input type="hidden" id="material_fee" value="{{ $material_fee }}">
                    <div class="icheck-info d-inline">
                        <input type="checkbox" class="checkbox" id="material_fee_wavier" name="material_fee_wavier" value="1" @if ($material_fee==0) checked @endif @if($paid_installment> 0) disabled @endif onclick="checkMaterial(this)">
                        <label for="material_fee_wavier" class="check material_fee_wavier" style="font-size:.72rem;font-weight:600;color:var(--fee-gray-500);">Material Fee Wavier</label>
                    </div>
                </div>
                <div class="fee-stat-card s-success">
                    <div class="fee-stat-label">Total Fee</div>
                    <div class="fee-stat-value" id="total_fee_display">${{ number_format($total_fee, 2) }}</div>
                    <input type="hidden" id="total_fee" value="{{ $total_fee }}">
                </div>
                <div class="fee-stat-card s-warning">
                    <div class="fee-stat-label">Remaining Setup</div>
                    <div class="fee-stat-value">${{ number_format($remaining, 2) }}</div>
                </div>
                <div class="fee-stat-card s-purple">
                    <div class="fee-stat-label">Current Total</div>
                    <div class="fee-stat-value" id="total_display">$0.00</div>
                    <input type="hidden" id="total">
                </div>
                <div class="fee-stat-card s-success">
                    <div class="fee-stat-label">Paid Amount</div>
                    <div class="fee-stat-value">${{ number_format($paid_installment, 2) }}</div>
                </div>
                <div class="fee-stat-card s-danger">
                    <div class="fee-stat-label">Remaining Payment</div>
                    <div class="fee-stat-value">${{ number_format($remaining_installment, 2) }}</div>
                </div>
                <div class="fee-stat-card s-danger">
                    <div class="fee-stat-label">Refunded</div>
                    <div class="fee-stat-value">${{ number_format($refunded_amount, 2) }}</div>
                </div>
                <div class="fee-stat-card s-primary">
                    <div class="fee-stat-label">Start Date</div>
                    <div class="fee-stat-value" style="font-size:1rem;">{{ dateFormat($student_intake_course_fee->intakeCourse->starting_date) }}</div>
                </div>
                <div class="fee-stat-card s-primary">
                    <div class="fee-stat-label">End Date</div>
                    <div class="fee-stat-value" style="font-size:1rem;">{{ dateFormat($student_intake_course_fee->intakeCourse->ending_date) }}</div>
                </div>
            </div>

            {{-- ===== INSTALLMENTS ===== --}}
            <div class="fee-section-card">
                <div class="fee-section-head"><i class="fas fa-list-ol"></i> Edit Installments</div>
                <div class="fee-section-body">
                    <div class="fee-installments-wrapper">
                        @foreach($installments as $index => $value)
                        <div class="fee-installment-card" id="row">
                            <div class="fee-installment-card-header">
                                <div class="inst-number">
                                    <span>{{ $index + 1 }}</span> {{ $value->name }}
                                </div>
                                <div>
                                    @if($value->status == 1)
                                        <span class="inst-badge badge-pending">Pending</span>
                                    @elseif($value->status == 2)
                                        <span class="inst-badge badge-paid">Paid</span>
                                    @elseif($value->status == 3)
                                        <span class="inst-badge badge-refunded">Refunded</span>
                                    @endif
                                </div>
                            </div>
                            <div class="fee-installment-card-body">
                                <input type="hidden" name="installment_id[]" value="{{ $value->id }}">
                                <input type="hidden" name="installment_paid_amount[]" class="form-control" value="{{ $value->installment_paid_amount }}">
                                <div class="fee-field-grid">
                                    <div class="fee-form-group">
                                        <label>Name <span class="required">*</span></label>
                                        <input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name" value="{{ $value->name }}" @if ($value->status != 1) readonly @endif>
                                    </div>
                                    <div class="fee-form-group">
                                        <label>Amount <span class="required">*</span></label>
                                        <input type="number" step="0.01" name="amount[]" class="form-control" id="amount{{ $index }}" oninput="findTotal(this)" placeholder="Enter Amount" value="{{ $value->amount }}" @if ($value->status != 1) readonly @endif>
                                    </div>
                                    <div class="fee-form-group">
                                        <label>Due Date <span class="required">*</span></label>
                                        <input type="date" name="due_date[]" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $value->due_date }}" @if ($value->status != 1) readonly @endif>
                                    </div>
                                </div>
                                @if ($value->status == 1)
                                <div style="margin-top:16px;text-align:right;">
                                    <button class="btn-fee-delete" id="DeleteRow" type="button"><i class="fas fa-trash-alt"></i> Delete</button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div id="newinput"></div>
                    <div style="margin-top:20px;">
                        <button id="rowAdder" type="button" class="btn-fee-add"><i class="fas fa-plus-circle"></i> Add Installment</button>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="fee-form-footer">
                <a href="{{ route('admin.student.fee.index', $student_intake_course_fee->student_id) }}" class="btn btn-default" style="border-radius:8px;padding:10px 24px;">Cancel</a>
                <button type="submit" class="btn-fee-primary"><i class="fas fa-save mr-1"></i> Submit</button>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#editfee').validate({
            rules: {
                "name[]": {
                    required: true,
                },
                "amount[]": {
                    required: true,
                },
                "due_date[]": {
                    required: true,
                },
            },
            messages: {
                "name[]": "Please enter unit name",
                "amount[]": "Please enter amount",
                "due_date[]": "Please enter due date",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.fee-form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    $("#rowAdder").click(function() {
        newRowAdd =
            '<div class="fee-installment-card" id="row" style="margin-top:20px;">' +
            '<div class="fee-installment-card-header"><div class="inst-number"><span>+</span> New Installment</div>' +
            '<span class="inst-badge badge-pending">New</span></div>' +
            '<div class="fee-installment-card-body">' +
            '<input type="hidden" name="installment_id[]">' +
            '<div class="fee-field-grid">' +
            '<div class="fee-form-group"><label>Name <span class="required">*</span></label>' +
            '<input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name"></div>' +
            '<div class="fee-form-group"><label>Amount <span class="required">*</span></label>' +
            '<input type="number" step="0.01" name="amount[]" class="form-control" id="amount" placeholder="Enter Amount"></div>' +
            '<div class="fee-form-group"><label>Due Date <span class="required">*</span></label>' +
            '<input type="date" name="due_date[]" class="form-control" id="due_date" placeholder="Enter Due Date"></div></div>' +
            '<input type="hidden" name="installment_paid_amount[]" class="form-control" value="0">' +
            '<div style="margin-top:16px;text-align:right;">' +
            '<button class="btn-fee-delete" id="DeleteRow" type="button"><i class="fas fa-trash-alt"></i> Delete</button></div>' +
            '</div></div>'

        $('#newinput').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function() {
        $(this).closest("#row").remove();
    })

    function findTotal(e) {
        var arr = document.getElementsByName('amount[]');
        var rem = parseInt('{{ $remaining_amount }}');
        var tot = 0;
        for (var i = 0; i < arr.length; i++) {
            if (parseInt(arr[i].value))
                tot += parseInt(arr[i].value);
        }
        total = tot - rem;
        console.log('Current Total', total);
        document.getElementById('total').value = total;
        document.getElementById('total_display').innerText = '$' + Number(total).toFixed(2);
    }

    findTotal();

    function checkEnrollment(checkbox) {
        var ef = "{{ $enrollment_fee }}";
        var mf = document.getElementById('material_fee').value;
        var f = "{{ $fee }}";
        var i = "{{ $student_intake_course_fee->initial_fee }}";
        if (checkbox.checked) {
            document.getElementById('enrollment_fee').value = 0;
            document.getElementById('enrollment_fee_display').innerText = '$0.00';
            var nt = parseInt(f)+ 0 + parseInt(mf) ;
            document.getElementById('total_fee').value = nt;
            document.getElementById('total_fee_display').innerText = '$' + Number(nt).toFixed(2);
            var nif = parseInt(i) + 0 + parseInt(mf);
            document.getElementById('amount0').value = nif;
        } else {
            document.getElementById('enrollment_fee').value = ef;
            document.getElementById('enrollment_fee_display').innerText = '$' + Number(ef).toFixed(2);
            oot = parseInt(f)+ parseInt(ef) + parseInt(mf)
            document.getElementById('total_fee').value = oot;
            document.getElementById('total_fee_display').innerText = '$' + Number(oot).toFixed(2);
            oif = parseInt(i)+ parseInt(ef) + parseInt(mf)
            document.getElementById('amount0').value = oif;
        }
    }

    function checkMaterial(checkbox) {
        var mf = "{{ $material_fee }}";
        var ef = document.getElementById('enrollment_fee').value;
        var f = "{{ $fee }}";
        var i = "{{ $student_intake_course_fee->initial_fee }}";
        if (checkbox.checked) {
            document.getElementById('material_fee').value = 0;
            document.getElementById('material_fee_display').innerText = '$0.00';
            var nt = parseInt(f)+ 0 + parseInt(ef);
            document.getElementById('total_fee').value = nt;
            document.getElementById('total_fee_display').innerText = '$' + Number(nt).toFixed(2);
            var nif = parseInt(i) + 0 + parseInt(ef);
            document.getElementById('amount0').value = nif;
        } else {
            document.getElementById('material_fee').value = mf;
            document.getElementById('material_fee_display').innerText = '$' + Number(mf).toFixed(2);
            oot = parseInt(f)+ parseInt(ef) + parseInt(mf)
            document.getElementById('total_fee').value = oot;
            document.getElementById('total_fee_display').innerText = '$' + Number(oot).toFixed(2);
            oif = parseInt(i)+ parseInt(ef) + parseInt(mf)
            document.getElementById('amount0').value = oif;
        }
    }
</script>
@endsection
