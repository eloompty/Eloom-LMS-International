@extends('user::layouts.master')
@section('title', 'Admin | Add Teams Class')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Teams Class</h1>
            </div>
            <div class="col-sm-6">
                <?php

                use Modules\Intake\Entities\IntakeUnit;

                $intake_unit_id = session('id');
                $intakeUnit = IntakeUnit::find($intake_unit_id);
                ?>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeUnit->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.index', $intakeUnit->id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.onlineclass.index', $intakeUnit->id) }}">Teams</a></li>
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
                        <h3 class="card-title">Create <small>Group Teams Class</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="teams" action="{{ route('admin.intake.unit.team.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="subject">Subject</label> <span class="required">*</span>
                                        <input type="text" name="subject" class="form-control" id="subject" placeholder="Enter Subject">
                                    </div>

                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="content">Content</label> <span class="required">*</span>
                                        <input type="text" name="content" class="form-control" id="content" placeholder="Enter Content">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="end_date">Start Date</label> <span class="required">*</span>
                                        <input type="date" name="start_date" class="form-control" id="start_date" placeholder="Enter Start Date">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="start_time">Start Time</label> <span class="required">*</span>
                                        <input type="time" name="start_time" class="form-control" id="start_time" placeholder="Enter Start Time">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label> <span class="required">*</span>
                                        <input type="date" name="end_date" class="form-control" id="end_date" placeholder="Enter End Date">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="end_time">End Time</label> <span class="required">*</span>
                                        <input type="time" name="end_time" class="form-control" id="end_time" placeholder="Enter End Time">
                                    </div>
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
        $('#teams').validate({
            rules: {
                subject: {
                    required: true,
                },
                content: {
                    required: true,
                },
                start_date: {
                    required: true,
                },
                start_time: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                end_time: {
                    required: true,
                },
            },
            messages: {
                subject: "Please enter subject",
                content: "Please enter content",
                start_date: "Please enter start date",
                start_time: "Please enter start time",
                end_date: "Please enter start date",
                end_time: "Please enter start time",
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