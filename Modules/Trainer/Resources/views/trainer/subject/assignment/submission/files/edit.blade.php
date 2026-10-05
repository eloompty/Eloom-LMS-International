@extends('user::layouts.master')
@section('title', 'Admin | Upload Graded File')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Upload Graded File</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.submission.index', [$file->assignmentSubmission->assignment_id, $trainerIntake->id]) }}">Submissions</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.submission.files.index', [$file->assignmentSubmission->id, $trainerIntake->id]) }}">Files</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit <small>{{ $file->assignmentSubmission->assignment->name }} Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editsubmission" action="{{ route('trainer.subject.submission.files.update', [$file->id, $trainerIntake->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="grade_path">Upload Graded File</label> <span class="required">*</span>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="grade_path" class="custom-file-input" id="grade_path" onchange="readURL(this);">
                                        <label class="custom-file-label" for="grade_path">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
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
        $('#editsubmission').validate({
            rules: {
                grade_path: {
                    required: true,
                },
            },
            messages: {
                grade_path: "Please upload one file",
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

    document.getElementById('editsubmission').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('grade_path');
        var file = fileInput.files[0];

        if (file && file.size > 10 * 1024 * 1024) { // 10 MB
            alert('File size exceeds 10 MB');
            event.preventDefault();
        }
    });
</script>
@endsection