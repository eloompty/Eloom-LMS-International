@extends('user::layouts.master')
@section('title', 'Admin | Edit Assignment Submission')

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
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $submission->student_id) }}">Intake</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.unit.index', $studentIntakeUnit->student_intake_course_id) }}">Unit</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.unit.submission.index', $studentIntakeUnit->id) }}">Submission</a></li>
                    <li class="breadcrumb-item active">Edit Submission</li>
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
                    <form id="editsubmission" action="{{ route('admin.student.intake.unit.submission.update', $submission->id) }}" method="POST">
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
                                <img src="{{ asset($submission->path) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                @elseif ($submission->assignment->type == 'mcq')
                                <a href="{{ route('admin.student.intake.unit.submission.mcq', $submission->id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @else
                                <a href="{{ route('admin.student.intake.unit.submission.question', $submission->id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
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
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
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
</script>
@endsection