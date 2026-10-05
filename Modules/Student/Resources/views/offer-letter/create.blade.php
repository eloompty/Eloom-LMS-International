@extends('user::layouts.master')
@section('title', 'Admin | Add Offer Letter')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Offer Letter</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.offer.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.offer.letter.index', $student->id) }}">Offer Letters</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if (count($templates) == 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            No active offer letter templates found. Please
            <a href="{{ route('admin.offer.template.index') }}">create a template</a> before generating an offer letter.
        </div>
        @endif
        <!-- form start -->
        <form id="addstudentoffer" action="{{ route('admin.student.offer.letter.store', $student->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Create {{ userName('Student', $student->id) }}'s Offer Letter</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="offer_template_id">Offer Letter Template <span class="required">*</span></label>
                                <select name="offer_template_id" class="form-control" id="offer_template_id">
                                    <option value="" selected disabled>-- Select Template --</option>
                                    @foreach ($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">The letter content is taken from the selected template and filled with this student's details.</small>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="issue_date">Issue Date</label>
                                    <input type="date" name="issue_date" class="form-control" id="issue_date" value="{{ $date }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="expiry_date">Expiry Date</label>
                                    <input type="date" name="expiry_date" class="form-control" id="expiry_date">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="intake_course_ids">Choose Intake Courses</label> <span class="required">*</span>
                                @foreach($intake_courses as $key => $value)
                                <div class="icheck-info d-block">
                                    <input type="checkbox" class="checkbox" id="course_{{ $key }}" name="intake_course_ids[]" value="{{ $value->intake_course_id }}" checked>
                                    <label for="course_{{ $key }}" class="check">{{ $value->intakeCourse->course->course_name }} ({{ $value->intakeCourse->intake->name }})</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Conditions &amp; Status</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="condition">Condition</label>
                                <select name="condition" class="form-control" id="condition">
                                    <option value="" selected disabled>-- Select Condition --</option>
                                    @foreach ($conditions as $condition)
                                    <option value="{{ $condition->id }}">{{ $condition->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="credit">Credit</label>
                                <select name="credit" class="form-control" id="credit">
                                    <option value="" selected disabled>-- Select Credit --</option>
                                    @foreach ($credits as $credit)
                                    <option value="{{ $credit->id }}">{{ $credit->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 px-0">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Generate Offer Letter</button>
                <a href="{{ route('admin.student.offer.letter.index', $student->id) }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#addstudentoffer').validate({
            rules: {
                offer_template_id: {
                    required: true,
                },
                "intake_course_ids[]": {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                offer_template_id: "Please select a template",
                status: "Please select one status",
                "intake_course_ids[]": "Please select atleast one intake course",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection
