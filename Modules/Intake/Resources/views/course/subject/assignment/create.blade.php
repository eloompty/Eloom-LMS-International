@extends('user::layouts.master')
@section('title', 'Admin | Add Assignment')

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
                <h1>Add Assignment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeSubject->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.semester.index', $intakeSubject->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.subject.index', $intakeSubject->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.subject.assignment.index', $intakeSubject->id) }}">Assignments</a></li>
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
                        <h3 class="card-title">Add <small>{{ $intakeSubject->subject->name }}'s Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addassignment" action="{{ route('admin.intake.subject.assignment.store', $intakeSubject->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">Name</label> <span class="required">*</span>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="due_date">Due Date</label> <span class="required">*</span>
                                    <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="type">Assignment Type</label> <span class="required">*</span>
                                    <select name="type" class="form-control" id="type">
                                        <option value="" selected disabled>-- Select Assignment Type --</option>
                                        <option value="file">File</option>
                                        <option value="multiple files">Multiple Files</option>
                                        <option value="mcq">Multiple Choice Question</option>
                                        <option value="question">Question</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" disabled>-- Select Status --</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
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
        $('#addassignment').validate({
            rules: {
                name: {
                    required: true,
                },
                due_date: {
                    required: true,
                },
                type: {
                    required: true,
                },
                type: {
                    required: true,
                },
            },
            messages: {
                name: "Please enter name",
                due_date: "Please enter due date",
                type: "Please choose one type",
                status: "Please choose one status",
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