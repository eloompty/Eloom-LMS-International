@extends('user::layouts.master')
@section('title', 'Admin | Edit Scholarship')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Scholarship</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.scholarship.index') }}">Scholarships</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form id="editscholarship" action="{{ route('admin.scholarship.update', $scholarship->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Scholarship</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Name <span class="required">*</span></label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $scholarship->name) }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="type">Type <span class="required">*</span></label>
                                    <select name="type" class="form-control" id="type">
                                        @foreach(['merit' => 'Merit', 'need_based' => 'Need Based', 'staff' => 'Staff', 'early_enrollment' => 'Early Enrollment', 'agent_negotiated' => 'Agent Negotiated'] as $key => $label)
                                        <option value="{{ $key }}" {{ old('type', $scholarship->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="1" {{ old('status', $scholarship->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $scholarship->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-muted mb-2">Award Structure</h6>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="award_scope">Award Scope <span class="required">*</span></label>
                                    <select name="award_scope" class="form-control" id="award_scope">
                                        <option value="partial" {{ old('award_scope', $scholarship->award_scope) == 'partial' ? 'selected' : '' }}>Partial (set the value below)</option>
                                        <option value="full" {{ old('award_scope', $scholarship->award_scope) == 'full' ? 'selected' : '' }}>Full (100% tuition waiver)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="disbursement">Disbursement <span class="required">*</span></label>
                                    <select name="disbursement" class="form-control" id="disbursement">
                                        <option value="one_off" {{ old('disbursement', $scholarship->disbursement) == 'one_off' ? 'selected' : '' }}>One-off (applied once)</option>
                                        <option value="per_semester" {{ old('disbursement', $scholarship->disbursement) == 'per_semester' ? 'selected' : '' }}>Per Semester (each semester)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4" id="max_semesters_group">
                                    <label for="max_semesters">Max Semesters</label>
                                    <input type="number" min="1" max="20" name="max_semesters" class="form-control" id="max_semesters" placeholder="Blank = full course duration" value="{{ old('max_semesters', $scholarship->max_semesters) }}">
                                </div>
                            </div>
                            <div class="row" id="valueGroup">
                                <div class="form-group col-md-4">
                                    <label for="value_type">Value Type <span class="required">*</span></label>
                                    <select name="value_type" class="form-control" id="value_type">
                                        <option value="fixed" {{ old('value_type', $scholarship->value_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                                        <option value="percentage" {{ old('value_type', $scholarship->value_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="value">Value <span class="required">*</span></label>
                                    <input type="number" step="0.01" min="0" name="value" class="form-control" id="value" value="{{ old('value', $scholarship->value) }}">
                                </div>
                                <div class="form-group col-md-4" id="max_value_group">
                                    <label for="max_value">Max Discount Cap ($)</label>
                                    <input type="number" step="0.01" min="0" name="max_value" class="form-control" id="max_value" value="{{ old('max_value', $scholarship->max_value) }}">
                                </div>
                            </div>
                            <div class="row" id="maintenanceRow">
                                <div class="form-group col-md-4">
                                    <label class="d-block">Requires Maintenance</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="requires_maintenance" name="requires_maintenance" value="1" {{ old('requires_maintenance', $scholarship->requires_maintenance) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="requires_maintenance">Renewal depends on grades</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4" id="maintenance_min_group">
                                    <label for="maintenance_min_percentage">Min Overall % to Keep Award</label>
                                    <input type="number" step="0.01" min="0" max="100" name="maintenance_min_percentage" class="form-control" id="maintenance_min_percentage" placeholder="e.g. 60" value="{{ old('maintenance_min_percentage', $scholarship->maintenance_min_percentage) }}">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="quota">Quota (max applicants)</label>
                                    <input type="number" min="0" name="quota" class="form-control" id="quota" placeholder="Leave blank for unlimited" value="{{ old('quota', $scholarship->quota) }}">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="eligibility_criteria">Eligibility Criteria</label>
                                    <textarea name="eligibility_criteria" class="form-control" id="eligibility_criteria" rows="2">{{ old('eligibility_criteria', $scholarship->eligibility_criteria) }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="description">Description</label>
                                    <textarea name="description" class="form-control" id="description" rows="3">{{ old('description', $scholarship->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
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
    $('#value_type').on('change', function () {
        if ($(this).val() === 'percentage') {
            $('#max_value_group').show();
        } else {
            $('#max_value_group').hide();
            $('#max_value').val('');
        }
    }).trigger('change');

    $('#award_scope').on('change', function () {
        $('#valueGroup').toggle($(this).val() !== 'full');
    }).trigger('change');

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
        $('#editscholarship').validate({
            rules: {
                name: { required: true },
                type: { required: true },
                value_type: { required: true },
                value: { required: true, min: 0 },
                status: { required: true },
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
