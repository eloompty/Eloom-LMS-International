@extends('student::student.layouts.master')
@section('title', 'Student | Add Assignment Submission')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Assignment Submissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntakeSubject->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntakeSubject->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.assignment.index', $studentIntakeSubject->intake_subject_id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.submission.index', [$assignment->id, $studentIntakeSubject->id]) }}">Submissions</a></li>
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
        <form id="addassignmentsubmission" action="{{ route('student.subject.submission.store', [$assignment->id, $studentIntakeSubject->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Assignment Submission</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <input type="hidden" name="assignment_resubmission_id" value="{{ $resubmission_id }}">
                            <div class="form-group">
                                <label for="path">Assignment</label> <span class="required">*</span>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="path" class="custom-file-input" id="path" onchange="readURL(this);">
                                        <label class="custom-file-label" for="path">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
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
                <button type="submit" class="btn btn-primary" onclick="return confirmation();">Submit</button>
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
        $('#addassignmentsubmission').validate({
            rules: {
                path: {
                    required: true,
                },
            },
            messages: {
                path: "Please upload assignment",
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

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('addassignmentsubmission').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('path');
        var file = fileInput.files[0];

        if (file && file.size > 10 * 1024 * 1024) { // 10 MB
            alert('File size exceeds 10 MB');
            event.preventDefault();
        } else {
            let x = "Course: {{ $assignment->intakeSubject->intakeCourse->course->course_name }}\nSubject: {{ $assignment->intakeSubject->subject->name }}\nAssignment: {{ $assignment->name }}\nAre your sure, you want to submit?";
            function confirmation() {
                var text = x;
                if (confirm(text)) {
                    document.getElementById('addassignmentsubmission').submit();
                } else {
                    return false;
                }
            }
        }
    });
</script>
@endsection
