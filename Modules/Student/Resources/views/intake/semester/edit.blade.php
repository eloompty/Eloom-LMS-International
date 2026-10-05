@extends('user::layouts.master')
@section('title', 'Admin | Student Intake Semester')

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
                <h1>Student Intake Semester</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeSemester->studentIntakeCourse->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.semester.index', $studentIntakeSemester->student_intake_course_id) }}">Semesters</a></li>
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
                        <h3 class="card-title">Edit <small>Student Intake Semester</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editIntakeSemester" action="{{ route('admin.student.intake.semester.update', $studentIntakeSemester->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="semester">Semester</label>
                                    <input type="text" class="form-control" id="semester" disabled value="{{ $studentIntakeSemester->intakeSemester->semester->name }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="duration">Duration</label>
                                    <input type="number" name="duration" class="form-control" id="duration" value="{{ $studentIntakeSemester->duration }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="starting_date">Starting Date</label>
                                    <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $studentIntakeSemester->starting_date }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="ending_date">Ending Date</label>
                                    <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date" value="{{ $studentIntakeSemester->ending_date }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="due_date">Due Date</label>
                                    <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $studentIntakeSemester->due_date }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($studentIntakeSemester->status == '1')selected @endif value="1">Active</option>
                                        <option @if($studentIntakeSemester->status == '0')selected @endif value="0">Inactive</option>
                                        <option @if($studentIntakeSemester->status == '3')selected @endif value="3">Locked</option>
                                    </select>
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
        $('#editIntakeSemester').validate({
            rules: {
                status: {
                    required: true
                },
            },
            messages: {
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
