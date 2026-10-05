@extends('user::layouts.master')
@section('title', 'Admin | Edit Teacher Intake')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Teacher Intake</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Teachers</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.intake.index', $trainerIntake->trainer_id) }}">Intakes</a></li>
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
        <form id="addtrainerintake" action="{{ route('admin.trainer.intake.update', $trainerIntake->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit {{ $trainerIntake->trainer->salutation }} {{ $trainerIntake->trainer->first_name }} {{ $trainerIntake->trainer->family_name }}'s Intake</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="intake_id">Intake</label> <span class="required">*</span>
                                    <select id="intake_id" class="form-control">
                                        <option value="{{ $trainerIntake->intakeCourse->intake_id }}" selected disabled>{{ $trainerIntake->intakeCourse->intake->name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="course_id">Course</label> <span class="required">*</span>
                                    <select id="intake_course_id" name="intake_course_id" class="form-control">
                                        <option value="{{ $trainerIntake->intake_course_id }}" selected disabled>{{ $trainerIntake->intakeCourse->course->course_name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="unit_id">Unit</label> <span class="required">*</span>
                                    <select name="intake_unit_id" id="intake_unit_id" class="form-control">
                                        <option value="{{ $trainerIntake->intake_unit_id }}" selected disabled>{{ $trainerIntake->intakeUnit->unit->name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="starting_date">Starting Date</label> <span class="required">*</span>
                                    <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $trainerIntake->starting_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($trainerIntake->status == '1')selected @endif value="1">Active</option>
                                        <option @if($trainerIntake->status == '0')selected @endif value="0">Inactive</option>
                                    </select>
                                </div>
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
        // $.validator.setDefaults({
        //     submitHandler: function() {
        //         alert("Form successful submitted!");
        //     }
        // });
        $('#addtrainerintake').validate({
            rules: {
                intake_id: {
                    required: true,
                },
                starting_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                intake_id: "Please choose one intake",
                starting_date: "Please enter starting date",
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