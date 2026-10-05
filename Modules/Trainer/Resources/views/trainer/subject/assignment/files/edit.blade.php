@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Edit Files Assignment')

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
                <h1>{{ $assignment->intakeSubject->subject->name }}'s Files Assignment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $assignment->name }}'s Files Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addassignment" action="{{ route('trainer.subject.assignment.files.update', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @foreach ($files as $file)
                            @php
                            $file_path = $file->path;
                            $path = explode('/', $file_path);
                            @endphp
                            <div class="form-group">
                                <label for="file">File: {{ array_pop($path) }}</label>
                                <a href="{{asset($file->path)}}" target="_blank"> <img src="{{ asset('files/multiple_file.png') }}" id="box-image" alt="" style="width: 50px; border: #ebebeb 1px solid;"></a>
                                <input type="hidden" name="files[{{ $file->id }}]" class="form-control mb-2">
                                <button type="button" class="btn btn-danger remove-input">Remove</button>
                            </div>
                            @endforeach
                            <div id="file-inputs"></div>
                            <button type="button" class="btn btn-success mb-2" id="add-input">Add Another File</button>
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
                "file[]": {
                    required: true,
                }
            },
            messages: {
                "file[]": "Please upload file",
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

    $(document).ready(function() {
        $('#add-input').click(function() {
            $('#file-inputs').append('<div class="form-group"><input type="file" name="files[0][]" class="form-control mb-2"><button type="button" class="btn btn-danger remove-input">Remove</button></div>');
        });

        $(document).on('click', '.remove-input', function() {
            $(this).closest('.form-group').remove();
        });
    });

    $('#addassignmentfile').submit(function(e) {
        let totalSize = 0;
        $('input[type="file"]').each(function() {
            if (this.files.length > 0) {
                totalSize += this.files[0].size;
            }
        });

        // Convert bytes to MB
        totalSize = totalSize / (1024 * 1024);
        console.log('ts:', totalSize);

        if (totalSize > 10) {
            e.preventDefault();
            alert('Total file size must not exceed 10 MB');
            $('#error-message').text('Total file size must not exceed 10 MB.');
        } else {
            $('#error-message').text('');
        }
    });
</script>
@endsection