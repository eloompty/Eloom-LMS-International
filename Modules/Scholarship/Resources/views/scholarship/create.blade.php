@extends('user::layouts.master')
@section('title', 'Admin | Add Scholarship')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Scholarship</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.index') }}">Scholarships</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form id="addscholarship" action="{{ route('admin.scholarship.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Add Scholarship</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Name <span class="required">*</span></label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="e.g. Merit Scholarship 2024" value="{{ old('name') }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="type">Type <span class="required">*</span></label>
                                    <select name="type" class="form-control" id="type">
                                        <option value="" disabled selected>-- Select Type --</option>
                                        <option value="merit" {{ old('type') == 'merit' ? 'selected' : '' }}>Merit</option>
                                        <option value="need_based" {{ old('type') == 'need_based' ? 'selected' : '' }}>Need Based</option>
                                        <option value="staff" {{ old('type') == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="early_enrollment" {{ old('type') == 'early_enrollment' ? 'selected' : '' }}>Early Enrollment</option>
                                        <option value="agent_negotiated" {{ old('type') == 'agent_negotiated' ? 'selected' : '' }}>Agent Negotiated</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-muted mb-2">Award Structure</h6>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="award_scope">Award Scope <span class="required">*</span></label>
                                    <select name="award_scope" class="form-control" id="award_scope">
                                        <option value="partial" {{ old('award_scope', 'partial') == 'partial' ? 'selected' : '' }}>Partial (set the value below)</option>
                                        <option value="full" {{ old('award_scope') == 'full' ? 'selected' : '' }}>Full (100% tuition waiver)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="disbursement">Disbursement <span class="required">*</span></label>
                                    <select name="disbursement" class="form-control" id="disbursement">
                                        <option value="one_off" {{ old('disbursement', 'one_off') == 'one_off' ? 'selected' : '' }}>One-off (applied once)</option>
                                        <option value="per_semester" {{ old('disbursement') == 'per_semester' ? 'selected' : '' }}>Per Semester (each semester)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4" id="max_semesters_group">
                                    <label for="max_semesters">Max Semesters</label>
                                    <input type="number" min="1" max="20" name="max_semesters" class="form-control" id="max_semesters" placeholder="Blank = full course duration" value="{{ old('max_semesters') }}">
                                </div>
                            </div>
                            <div class="row" id="valueGroup">
                                <div class="form-group col-md-4">
                                    <label for="value_type">Value Type <span class="required">*</span></label>
                                    <select name="value_type" class="form-control" id="value_type">
                                        <option value="" disabled selected>-- Select Value Type --</option>
                                        <option value="fixed" {{ old('value_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                                        <option value="percentage" {{ old('value_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="value">Value <span class="required">*</span></label>
                                    <input type="number" step="0.01" min="0" name="value" class="form-control" id="value" placeholder="e.g. 500 or 10" value="{{ old('value') }}">
                                </div>
                                <div class="form-group col-md-4" id="max_value_group">
                                    <label for="max_value">Max Discount Cap ($)</label>
                                    <input type="number" step="0.01" min="0" name="max_value" class="form-control" id="max_value" placeholder="Optional cap for % discount" value="{{ old('max_value') }}">
                                </div>
                            </div>
                            <div class="row" id="maintenanceRow">
                                <div class="form-group col-md-4">
                                    <label class="d-block">Requires Maintenance</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="requires_maintenance" name="requires_maintenance" value="1" {{ old('requires_maintenance') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="requires_maintenance">Renewal depends on grades</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4" id="maintenance_min_group">
                                    <label for="maintenance_min_percentage">Min Overall % to Keep Award</label>
                                    <input type="number" step="0.01" min="0" max="100" name="maintenance_min_percentage" class="form-control" id="maintenance_min_percentage" placeholder="e.g. 60" value="{{ old('maintenance_min_percentage') }}">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="quota">Quota (max applicants)</label>
                                    <input type="number" min="0" name="quota" class="form-control" id="quota" placeholder="Leave blank for unlimited" value="{{ old('quota') }}">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="eligibility_criteria">Eligibility Criteria</label>
                                    <textarea name="eligibility_criteria" class="form-control" id="eligibility_criteria" rows="2" placeholder="Optional eligibility notes">{{ old('eligibility_criteria') }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="description">Description</label>
                                    <textarea name="description" class="form-control" id="description" rows="3" placeholder="Optional description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.scholarship.index') }}" class="btn btn-default ml-2">Cancel</a>
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
    // Show/hide max_value field based on value_type
    $('#value_type').on('change', function () {
        if ($(this).val() === 'percentage') {
            $('#max_value_group').show();
        } else {
            $('#max_value_group').hide();
            $('#max_value').val('');
        }
    }).trigger('change');

    // Full award = 100% waiver: hide the value amount group (controller forces value_type/value).
    $('#award_scope').on('change', function () {
        $('#valueGroup').toggle($(this).val() !== 'full');
    }).trigger('change');

    // Maintenance + semester cap only apply to per-semester awards.
    $('#disbursement').on('change', function () {
        var perSemester = $(this).val() === 'per_semester';
        $('#max_semesters_group').toggle(perSemester);
        $('#maintenanceRow').toggle(perSemester);
        if (!perSemester) {
            $('#requires_maintenance').prop('checked', false).trigger('change');
            $('#max_semesters').val('');
        }
    }).trigger('change');

    $('#requires_maintenance').on('change', function () {
        $('#maintenance_min_group').toggle(this.checked);
        if (!this.checked) $('#maintenance_min_percentage').val('');
    }).trigger('change');

    $(function () {
        $('#addscholarship').validate({
            rules: {
                name: { required: true },
                type: { required: true },
                value_type: { required: true },
                value: { required: true, min: 0 },
                status: { required: true },
            },
            messages: {
                name: 'Please enter a name',
                type: 'Please select a type',
                value_type: 'Please select a value type',
                value: { required: 'Please enter a value', min: 'Value must be 0 or greater' },
                status: 'Please select a status',
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
