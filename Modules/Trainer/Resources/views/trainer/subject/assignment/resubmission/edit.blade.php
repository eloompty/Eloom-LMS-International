@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Edit Resubmission Request')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $resubmission->assignment->name }} Assignment Submission</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $trainerIntake->intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.resubmission.index', [$resubmission->assignment_id, $trainerIntake->id]) }}">Resubmission Request</a></li>
                    <li class="breadcrumb-item active">Editt</li>
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
                        <h3 class="card-title">Edit <small>{{ $resubmission->assignment->name }} Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editresubmission" action="{{ route('trainer.resubmission.update', [$resubmission->id, $trainerIntake->id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="student_id">Student Name</label> <span class="required">*</span>
                                <input type="text" name="student_id" class="form-control" id="student_id" value="{{ userName('Student', $resubmission->student_id) }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="path">Assignment</label> <span class="required">*</span>
                            </div>
                            <div class="form-group">
                                @if ($resubmission->assignment->type == 'file')
                                <a href="{{ asset($resubmission->assignment->path) }}" target="_blank"><img src="{{ asset(filePath($resubmission->assignment->path)) }}" alt="" width="48" /></a>
                                @elseif ($resubmission->assignment->type == 'mcq')
                                <a href="{{ route('trainer.assignment.mcq.show', $resubmission->assignment->id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                @else
                                <a href="{{ route('trainer.assignment.question.show', $resubmission->assignment->id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($resubmission->status == '1')selected @endif value="1">Approve</option>
                                    <option @if($resubmission->status == '2')selected @endif value="2">Reject</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label> <span class="required">*</span>
                                <textarea class="form-control" name="remarks">{{ $resubmission->remarks }}</textarea>
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
        $('#editresubmission').validate({
            rules: {
                status: {
                    required: true,
                },
                remarks: {
                    required: true,
                },
            },
            messages: {
                status: "Please choose one status",
                remarks: "Please enter remarks",
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