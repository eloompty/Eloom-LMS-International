@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Add Intake Time Table')

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
                <h1>{{ $trainerIntake->intakeCourse->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.time.index', $trainerIntake->intake_subject_id) }}">Time Tables</a></li>
                    <li class="breadcrumb-item active">Add</li>
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
                        <h3 class="card-title">Add <small>{{ $trainerIntake->intakeSubject->subject->name }}'s Time Table</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addintaketime" action="{{ route('trainer.time.store', $trainerIntake->intake_subject_id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="from_date">From Date</label> <span class="required">*</span>
                                    <input type="date" name="from_date" class="form-control" id="from_date" placeholder="Enter From Date" value="{{ $trainerIntake->intakeSubject->starting_date }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="to_date">To Date</label> <span class="required">*</span>
                                    <input type="date" name="to_date" class="form-control" id="to_date" placeholder="Enter To Date" value="{{ $trainerIntake->intakeSubject->ending_date }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="from">From</label> <span class="required">*</span>
                                    <input type="time" name="from" class="form-control" id="from" placeholder="Enter From Time">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="to">To</label> <span class="required">*</span>
                                    <input type="time" name="to" class="form-control" id="to" placeholder="Enter To Time">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="sunday" name="days[]" value="Sunday">
                                        <label for="sunday" class="check">Sunday</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="monday" name="days[]" value="Monday">
                                        <label for="monday" class="check">Monday</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="tuesday" name="days[]" value="Tuesday">
                                        <label for="tuesday" class="check">Tuesday</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="wednesday" name="days[]" value="Wednesday">
                                        <label for="wednesday" class="check">Wednesday</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="thursday" name="days[]" value="Thursday">
                                        <label for="thursday" class="check">Thursday</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="icheck-info d-inline">
                                        <input type="checkbox" class="checkbox" id="friday" name="days[]" value="Friday">
                                        <label for="friday" class="check">Friday</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" disabled>-- Select Status --</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
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
                "days[]": {
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