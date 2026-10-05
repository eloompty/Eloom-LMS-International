@extends('user::layouts.master')
@section('title', 'Admin | Edit Assignment Submissions')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Assignment Submissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.assignment.index') }}">Assignment</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.assignment.submission.index', $submission->assignment_id) }}">Submissions</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $submission->assignment->name }} Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editsubmission" action="{{ route('admin.assignment.submission.update', $submission->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="student_id">Student Name</label> <span class="required">*</span>
                                <input type="text" name="student_id" class="form-control" id="student_id" value="{{ userName('Student', $submission->student_id) }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="path">Assignment</label> <span class="required">*</span>
                            </div>
                            <div class="form-group">
                                @if ($submission->assignment->type == 'file')
                                <img src="{{ asset($submission->path) }}" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                @elseif ($submission->assignment->type == 'mcq')
                                <a href="{{ route('admin.assignment.submission.mcq', $submission->id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @else
                                <a href="{{ route('admin.assignment.submission.question', $submission->id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="grade">Grade</label> <span class="required">*</span>
                                <select class="form-control" name="assignment_grade_id">
                                    <option value="" selected disabled>-- Select Grade --</option>
                                    @foreach ($grades as $key => $value)
                                    <option value="{{ $key }}" @if ($key==$submission->assignment_grade_id) selected @endif>{{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label> <span class="required">*</span>
                                <textarea class="form-control" name="remarks">{{ $submission->remarks }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="credits">Credits</label> <span class="required">*</span>
                                <input type="number" step="0.01" name="credits" class="form-control" id="credits" value="{{ $submission->credits }}">
                            </div>
                            <div class="form-group">
                                <label for="grade_path">Upload Graded File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="grade_path" class="custom-file-input" id="grade_path" onchange="readURL(this);">
                                        <label class="custom-file-label" for="grade_path">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <img src="{{ asset($submission_graded) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkboxPrimary" name="show_to_student" @if($show_to_student == 1) checked @endif value="1">
                                    <label for="checkboxPrimary" class="check view">Show Graded File to Student</label>
                                </div>
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
                assignment_grade_id: {
                    required: true,
                },
                remarks: {
                    required: true,
                },
                credits: {
                    required: true,
                },
            },
            messages: {
                assignment_grade_id: "Please choose one grade",
                remarks: "Please enter remarks",
                credits: "Please enter credits",
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