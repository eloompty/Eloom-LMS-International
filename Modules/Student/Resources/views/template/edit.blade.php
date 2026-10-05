@extends('user::layouts.master')
@section('title', 'Admin | Edit Student Template')

@section('content')
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Student Template</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.template.index', $student_template->student_id) }}">Templates</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addstudenttemplate" action="{{ route('admin.student.template.update', $student_template->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit {{ userName('Student', $student_template->student_id) }}'s Template</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <input type="hidden" name="template_name" value="{{ $student_template->template_name }}">
                            <div class="form-group">
                                <label for="intake_course_id">Intake</label> <span class="required">*</span>
                                <select name="intake_course_id" id="intake_course_id" class="form-control" disabled>
                                    <option value="{{ $student_template->intake_course_id }}">{{ $student_template->intakeCourse->course->course_name }} ({{ $student_template->intakeCourse->intake->name}})</option>
                                </select>
                            </div>
                            @foreach ($student_template->studentTemplateData as $index => $value)
                            <div class="form-group">
                                <label>{{ ucwords(str_replace("_", " ", $value->key )) }}</label> <span class="required">*</span>
                                @if ($value->key == 'content' || $value->key == 'footer')
                                <textarea name="{{ $value->key }}" class="form-control" id="{{ $value->key }}" placeholder="{{ ucwords(str_replace("_", " ", $value->key )) }}"">{{ $value->value }}</textarea>
                                <script>
                                    CKEDITOR.replace('{{ $value->key }}');
                                </script>
                                @else
                                <input @if($value->key == 'date') type='date' @elseif ($value->key == 'logo' || $value->key == 'regards_signature') type='file' @else type=" text" @endif name="{{ $value->key }}" class="form-control" id="{{ $value->key }}" placeholder="{{ ucwords(str_replace("_", " ", $value->key )) }}" value="{{ $value->value }}">
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
                                    <option @if($student_template->status == '1')selected @endif value="1">Active</option>
                                    <option @if($student_template->status == '0')selected @endif value="0">Inactive</option>
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
                completion_percent: "Please enter completion percent",
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
@foreach ($student_template->studentTemplateData as $index => $value)
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