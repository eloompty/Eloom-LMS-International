@extends('user::layouts.master')
@section('title', 'Admin | Add Student Intake')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Student Intake</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($student->is_enrolled == 0) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif>Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $student->id) }}">Student</a></li>
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
        <form id="addstudentintake" action="{{ route('admin.student.intake.course.store', $student->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ userName('Student', $student->id) }}'s Intake</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="intake_id">Intake</label> <span class="required">*</span>
                                <select id="intake_id" name="intake_id" class="form-control">
                                    <option value="" selected disabled>-- Select Intake --</option>
                                    @foreach($intakes as $key => $value)
                                    <option value="{{$key}}"> {{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="intake_course_id">Course</label> <span class="required">*</span>
                                <select name="intake_course_id" id="intake_course_id" class="form-control">
                                </select>
                            </div>
                            @if(feeSetting('fee_module')=='yes')
                            <div class="form-group">
                                <label for="fee_id">Fee Type</label> <span class="required">*</span>
                                <select name="fee_id" id="fee_id" class="form-control">
                                </select>
                            </div>
                            @endif
                            @if ($student->is_enrolled == 1)
                            <div class="form-group">
                                <label for="is_enrolled">Is Enrolled</label> <span class="required">*</span>
                                <select name="is_enrolled" class="form-control" id="is_enrolled">
                                    <option value="" selected disabled>-- Select Is Enrolled --</option>
                                    <option value="1">Enrolled </option>
                                    <option value="0">Offered</option>
                                </select>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
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
        $('#addstudentintake').validate({
            rules: {
                intake_id: {
                    required: true,
                },
                intake_course_id: {
                    required: true,
                },
                fee_id: {
                    required: true,
                },
                is_enrolled: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                intake_id: "Please choose one intake",
                intake_course_id: "Please choose one course",
                fee_id: "Please choose one fee",
                is_enrolled: "Please choose one is enrolled",
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

    $('#intake_id').change(function() {
        var courseID = $(this).val();
        if (courseID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/intake_course')}}?intake_id=" + courseID,
                success: function(res) {
                    if (res) {
                        $("#intake_course_id").empty();
                        $("#intake_course_id").append('<option value="">-- Select Course --</option>');
                        $.each(res, function(key, value) {
                            $("#intake_course_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#intake_course_id").empty();
                    }
                }
            });
        } else {
            $("#intake_course_id").empty();
        }
    });

    $('#intake_course_id').change(function() {
        var intakeCourseID = $(this).val();
        if (intakeCourseID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/intake_course_fee')}}?intake_course_id=" + intakeCourseID,
                success: function(res) {
                    if (res) {
                        $("#fee_id").empty();
                        $("#fee_id").append('<option value="">-- Select Fee Type --</option>');
                        $.each(res, function(key, value) {
                            $("#fee_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#fee_id").empty();
                    }
                }
            });
        } else {
            $("#fee_id").empty();
        }
    });
</script>
@endsection