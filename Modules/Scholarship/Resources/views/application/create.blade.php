@extends('user::layouts.master')
@section('title', 'Admin | Apply Scholarship')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Apply Scholarship</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.fee.index', $student->id) }}">Fees</a></li>
                    <li class="breadcrumb-item active">Apply Scholarship</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        {{-- Student & Fee Summary --}}
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Fee Details</h3>
            </div>
            <div class="card-body">
                <div class="personal_information">
                    <div class="personal_information_list">
                        <div class="personal_information_devide">
                            <div class="personal_information_title">Student:</div>
                            <div class="personal_information_des">{{ userName('Student', $student->id) }}</div>
                        </div>
                        <div class="personal_information_devide">
                            <div class="personal_information_title">Course:</div>
                            <div class="personal_information_des">
                                {{ $fee->intakeCourse->course->course_name ?? '-' }}
                            </div>
                        </div>
                        <div class="personal_information_devide">
                            <div class="personal_information_title">Fee Name:</div>
                            <div class="personal_information_des">{{ $fee->name }}</div>
                        </div>
                        <div class="personal_information_devide">
                            <div class="personal_information_title">Original Fee:</div>
                            <div class="personal_information_des">${{ number_format($fee->fee, 2) }}</div>
                        </div>
                        <div class="personal_information_devide">
                            <div class="personal_information_title">Existing Discount:</div>
                            <div class="personal_information_des">
                                ${{ number_format($fee->feeDiscounts->sum('discount_amount'), 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Application Form --}}
        <form id="applyscholarship" action="{{ route('admin.scholarship.application.store') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="student_intake_course_fee_id" value="{{ $fee->id }}">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Scholarship Application</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="scholarship_id">Scholarship <span class="required">*</span></label>
                                    <select name="scholarship_id" class="form-control" id="scholarship_id">
                                        <option value="" disabled selected>-- Select Scholarship --</option>
                                        @foreach($scholarships as $scholarship)
                                        <option value="{{ $scholarship->id }}"
                                            data-value-type="{{ $scholarship->value_type }}"
                                            data-value="{{ $scholarship->value }}"
                                            data-max-value="{{ $scholarship->max_value }}"
                                            data-fee="{{ $fee->fee }}">
                                            {{ $scholarship->name }} — {{ $scholarship->value_display }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Estimated Discount</label>
                                    <div class="form-control" id="discount_preview" style="background:#f4f4f4; font-weight:bold;">
                                        Select a scholarship to see discount
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="justification">Justification</label>
                                    <textarea name="justification" class="form-control" id="justification" rows="3"
                                        placeholder="Optional: reason for applying this scholarship">{{ old('justification') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                            <a href="{{ route('admin.student.fee.index', $student->id) }}" class="btn btn-default ml-2">Cancel</a>
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
<script>
    $('#scholarship_id').on('change', function () {
        var opt = $(this).find(':selected');
        var valueType = opt.data('value-type');
        var value = parseFloat(opt.data('value')) || 0;
        var maxValue = parseFloat(opt.data('max-value')) || 0;
        var fee = parseFloat(opt.data('fee')) || 0;

        var discount = 0;
        if (valueType === 'fixed') {
            discount = value;
        } else if (valueType === 'percentage') {
            discount = fee * (value / 100);
            if (maxValue > 0 && discount > maxValue) {
                discount = maxValue;
            }
        }

        if (discount > 0) {
            $('#discount_preview').text('$' + discount.toFixed(2) + ' (Net fee: $' + (fee - discount).toFixed(2) + ')');
        } else {
            $('#discount_preview').text('Select a scholarship to see discount');
        }
    });

    $(function () {
        $('#applyscholarship').validate({
            rules: {
                scholarship_id: { required: true },
            },
            messages: {
                scholarship_id: 'Please select a scholarship',
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) { $(element).addClass('is-invalid'); },
            unhighlight: function (element) { $(element).removeClass('is-invalid'); },
        });
    });
</script>
@endsection
