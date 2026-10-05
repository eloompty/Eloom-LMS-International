@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Edit Intake Time Table')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $intakeTime->intakeCourse->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $intakeTime->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $intakeTime->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.time.index', $intakeTime->intake_subject_id) }}">Time Tables</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $intakeTime->intakeSubject->subject->name }}'s Time Table</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addintaketime" action="{{ route('trainer.time.update', $intakeTime->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="date">Date</label> <span class="required">*</span>
                                    <input type="date" name="date" class="form-control" id="date" value="{{ $intakeTime->date }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="day">Day</label> <span class="required">*</span>
                                    <input type="day" name="day" class="form-control" id="date" value="{{ $intakeTime->day }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="from">From</label> <span class="required">*</span>
                                    <input type="time" name="from" class="form-control" id="from" placeholder="Enter From Time" value="{{ $intakeTime->from }}" >
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="to">To</label> <span class="required">*</span>
                                    <input type="time" name="to" class="form-control" id="to" placeholder="Enter To Time" value="{{ $intakeTime->to }}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($intakeTime->status == '1')selected @endif value="1">Active</option>
                                    <option @if($intakeTime->status == '0')selected @endif value="0">Inactive</option>
                                </select>
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
        $('#addintaketime').validate({
            rules: {
                from_date: {
                    required: true,
                },
                to_date: {
                    required: true
                },
                "days": {
                    required: true,
                },
                from: {
                    required: true,
                },
                to: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                from_date: "Please enter from date",
                to_date: "Please enter to date",
                day: "Please choose one day",
                from: "Please enter from time",
                to: "Please enter to time",
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
@endsection