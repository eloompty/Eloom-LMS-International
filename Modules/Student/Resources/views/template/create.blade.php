@extends('user::layouts.master')
@section('title', 'Admin | Add Student Template')

@section('content')
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Student Template</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.template.index', $student->id) }}">Templates</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addstudenttemplate" action="{{ route('admin.student.template.store', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Create {{ userName('Student', $student->id) }}'s {{ $template->name }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <input type="hidden" name="template_name" value="{{ $template->name }}">
                            <div class="form-group">
                                <label for="intake_course_id">Intake</label> <span class="required">*</span>
                                <select name="intake_course_id" id="intake_course_id" class="form-control">
                                    <option value="" selected disabled>-- Select Intake Course--</option>
                                    @foreach ($intake_courses as $intake_course)
                                    <option value="{{ $intake_course->intake_course_id }}">{{ $intake_course->intakeCourse->course->course_name }} ({{ $intake_course->intakeCourse->intake->name}})</option>
                                    @endforeach
                                </select>
                            </div>
                            @foreach ($template->templateData as $index => $value)
                            <div class="form-group">
                                <label>{{ ucwords(str_replace("_", " ", $value->key )) }}</label><span class="required"> *</span>
                                @if ($value->key == 'content' || $value->key == 'footer')
                                <textarea name="{{ $value->key }}" class="form-control" id="{{ $value->key }}" placeholder="{{ ucwords(str_replace("_", " ", $value->key )) }}"">{{ $value->value }}</textarea>
                                <script>
                                    CKEDITOR.replace('{{ $value->key }}');
                                </script>
                                @else
                                <input @if($value->key == 'date' || $value->key == 'term_break_from' || $value->key == 'term_break_to' || $value->key == 'leave_from' || $value->key == 'leave_to' || $value->key == 'certificate_issue_date') type='date' @elseif ($value->key == 'logo' || $value->key == 'regards_signature') type='file' @else type=" text" @endif name="{{ $value->key }}" class="form-control" id="{{ $value->key }}" placeholder="{{ ucwords(str_replace("_", " ", $value->key )) }}" value="{{ $value->value }}">
                                @endif
                            </div>
                            @if ($value->key == 'logo' || $value->key == 'regards_signature')
                            <div class="form-group">
                                <img src="{{ asset($value->value) }}" id="box-image{{ $value->id }}" alt="" style="width: 80px; border: #ebebeb 1px solid;">
                            </div>
                            @endif
                            @endforeach
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
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
        $('#addstudenttemplate').validate({
            rules: {
                intake_course_id: {
                    required: true,
                },
                date: {
                    required:true,
                },
                completion_percent: {
                    required: true,
                },
                term_break_from: {
                    required: true,
                },
                term_break_to: {
                    required: true,
                },
                leave_from: {
                    required: true,
                },
                leave_to: {
                    required: true,
                },
                work_hours: {
                    required: true,
                    digits: true,
                },
                required_work_placement_hours: {
                    required: true,
                    digits: true,
                },
                certificate_issue_date: {
                    required: true,
                },
                account_name: {
                    required: true,
                },
                bsb: {
                    required: true,
                },
                bank_name: {
                    required: true,
                },
                account_number: {
                    required: true,
                },
                regards_name: {
                    required: true,
                },
                regards_position: {
                    required: true,
                },
                regards_college_name: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                intake_course_id: "Please choose one student intake",
                date: "Please enter date",
                term_break_from: "Please enter date",
                term_break_to: "Please enter date",
                leave_from: "Please enter date",
                leave_to: "Please enter date",
                completion_percent: "Please enter completion percent",
                work_hours: "Please enter work hours",
                required_work_placement_hours: "Please enter required work placement hours",
                account_name: "Please enter account name",
                bsb: "Please enter bsb",
                bank_name: "Please enter bank name",
                account_number: "Please enter account number",
                regards_name: "Please enter regards name",
                regards_position: "Please enter regards position",
                regards_college_name: "Please enter regards college name",
                status: "Please select one status",

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
@foreach ($template->templateData as $index => $value)
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image{{ $value->id }}')
                    .attr('src', e.target.result)
                    .width(80)
                    .height(80);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endforeach
@endsection