@extends('user::layouts.master')
@section('title', 'Admin | Add Student Fee')

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
                <h1>Add Student Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.fee.index', $student->id) }}">Fees</a></li>
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
        <form id="addstudentfee" action="{{ route('admin.student.fee.store', $student->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ userName('Student', $student->id) }}'s Fee</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="intake_course_id">Intake</label> <span class="required">*</span>
                                <select name="intake_course_id" id="intake_course_id" class="form-control">
                                    <option value="" selected disabled>-- Select Intake Course--</option>
                                    @foreach ($intake_courses as $intake_course)
                                    <option value="{{ $intake_course->intake_course_id }}">{{ $intake_course->intakeCourse->course->course_name }} ({{ $intake_course->intakeCourse->intake->name}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fee_id">Fee Name</label> <span class="required">*</span>
                                <select name="fee_id" id="fee_id" class="form-control">
                                </select>
                            </div>
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
        $('#addstudentfee').validate({
            rules: {
                intake_course_id: {
                    required: true,
                },
                fee_id: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                intake_course_id: "Please choose one intake course",
                fee_id: "Please choose one fee",
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