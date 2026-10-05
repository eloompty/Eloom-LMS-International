@extends('user::layouts.master')
@section('title', 'Admin | Edit Unit Assignment')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 Course Unit>{{ $assignment->unit->name }}'s Assignment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($assignment->unit->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($assignment->unit->course->registered == 1) href="{{ route('admin.course.semester.index', $assignment->unit->course_id) }}" @else href="{{ route('admin.unregistered.unit.index', $assignment->unit->course_id) }}" @endif>Semesters</a></li>
                    <li class="breadcrumb-item"><a @if ($assignment->unit->course->registered == 1) href="{{ route('admin.course.subject.index', $assignment->unit->semester_id) }}" @else href="{{ route('admin.unregistered.subject.index', $assignment->unit->semester_id) }}" @endif>Subjects</a></li>
                    <li class="breadcrumb-item"><a @if ($assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.index', $assignment->unit->subject_id) }}" @else href="{{ route('admin.unregistered.unit.index', $assignment->unit->subject_id) }}" @endif>Units</a></li>
                    <li class="breadcrumb-item"><a @if ($assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.assignment.index', $assignment->unit_id) }}" @else href="{{ route('admin.unregistered.unit.assignment.index', $assignment->unit_id) }}" @endif>Assignments</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $assignment->unit->name }}'s Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editassignment" action="{{ route('admin.course.unit.assignment.update', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $assignment->name }}">
                            </div>
                            <!-- <div class="form-group">
                                <label for="due_date">Due Date</label> <span class="required">*</span>
                                <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $assignment->due_date }}">
                            </div> -->
                            @if ($assignment->type == 'file')
                            <div class="form-group">
                                <label for="path">Upload File</label> <span class="required">*</span>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="path" class="custom-file-input" id="path" onchange="readURL(this);">
                                        <label class="custom-file-label" for="path">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                @if ($assignment->type == 'file')
                                <img src="{{ asset($assignment->path) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                @elseif ($assignment->type == 'mcq')
                                <a @if ($assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.assignment.mcq.show', $assignment->id) }}" @else href="{{ route('admin.unregistered.unit.assignment.mcq.show', $assignment->id) }} @endif><img src="{{ asset('files/mcq.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @else
                                <a @if ($assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.assignment.question.show', $assignment->id) }}" @else href="{{ route('admin.unregistered.unit.assignment.question.show', $assignment->id) }} @endif><img src="{{ asset('files/qa.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;" /></a>
                                @endif
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
        $('#editassignment').validate({
            rules: {
                name: {
                    required: true,
                },
                // due_date: {
                //     required: true,
                // },
            },
            messages: {
                name: "Please enter name",
                // due_date: "Please enter due date",
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

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('editassignment').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('path');
        var file = fileInput.files[0];

        if (file && file.size > 10 * 1024 * 1024) { // 10 MB
            alert('File size exceeds 10 MB');
            event.preventDefault();
        }
    });
</script>
@endsection
