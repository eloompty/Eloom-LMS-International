@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Add Intake Marking')

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
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.mark.index', $trainerIntake->intake_subject_id) }}">Markings</a></li>
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
                        <h3 class="card-title">Add <small>{{ $trainerIntake->intakeSubject->subject->name }}'s Marking</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addintakemarking" action="{{ route('trainer.subject.mark.store', $trainerIntake->intake_subject_id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @foreach($types as $mark)
                            <div class="row" id="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Name</label> <span class="required">*</span>
                                    <input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name" value="{{ $mark->name }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="full_marks">Full Marks</label> <span class="required">*</span>
                                    <input type="text" name="full_marks[]" class="form-control" id="full_marks" placeholder="Enter Full Marks" value="{{ $mark->full_marks }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pass_marks">Pass Marks</label> <span class="required">*</span>
                                    <input type="text" name="pass_marks[]" class="form-control" id="pass_marks" placeholder="Enter Pass Marks" value="{{ $mark->pass_marks }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="button"></label>
                                    <div class="input-group-prepend">
                                        <button class="btn btn-danger" id="DeleteRow" type="button">
                                            <i class="bi bi-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div id="newinput"></div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <button id="rowAdder" type="button" class="btn btn-dark">
                                        <span class="bi bi-plus-square-dotted">
                                        </span> ADD Marking
                                    </button>
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
        $('#addintakemarking').validate({
            rules: {
                "name[]": {
                    required: true,
                },
                "full_marks[]": {
                    required: true
                },
                "pass_marks[]": {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                "name[]": "Please enter name",
                "full_marks[]": "Please enter full marks",
                "pass_marks[]": "Please choose pass marks",
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

    $("#rowAdder").click(function() {
        newRowAdd =
            '<div class="row" id="row"><div class="form-group col-md-3">' +
            '<label for="name">Name</label> <span class="required">*</span>' +
            '<input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name">' +
            '</div><div class="form-group col-md-3">' +
            '<label for="full_marks">Full Marks</label> <span class="required">*</span>' +
            '<input type="text" name="full_marks[]" class="form-control" id="full_marks" placeholder="Enter Full Marks">' +
            '</div><div class="form-group col-md-3">' +
            '<label for="pass_marks">Pass Marks</label> <span class="required">*</span>' +
            '<input type="text" name="pass_marks[]" class="form-control" id="pass_marks" placeholder="Enter Pass Marks">' +
            '</div><div class="form-group col-md-3">' +
            '<label for="button"></label>' +
            '<div class="input-group-prepend">' +
            '<button class="btn btn-danger" id="DeleteRow" type="button">' +
            '<i class="bi bi-trash"></i>Delete</button></div></div></div>'
        $('#newinput').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function() {
        $(this).parents("#row").remove();
    })
</script>
@endsection
