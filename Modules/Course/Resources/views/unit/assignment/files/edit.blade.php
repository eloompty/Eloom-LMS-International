@extends('user::layouts.master')
@section('title', 'Admin | Edit Assignment Files')

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
                <h1>Edit Assignment Files</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.index', $unit_assignment->unit->course_id) }}" @else href="{{ route('admin.unregistered.unit.index', $unit_assignment->unit->course_id) }}" @endif>Semesters</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.index', $unit_assignment->unit->semester_id) }}" @else href="{{ route('admin.unregistered.unit.index', $unit_assignment->unit->semester_id) }}" @endif>Subjects</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.index', $unit_assignment->unit->subject_id) }}" @else href="{{ route('admin.unregistered.unit.index', $unit_assignment->unit->subject_id) }}" @endif>Units</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.assignment.index', $unit_assignment->unit_id) }}" @else href="{{ route('admin.unregistered.unit.assignment.index', $unit_assignment->unit_id) }}" @endif>Assignments</a></li>
                    <li class="breadcrumb-item active">Files</li>
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
                        <h3 class="card-title">Edit <small>Files</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editassignmentfile" action="{{ route('admin.course.unit.assignment.files.update', [$unit_assignment->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @foreach ($files as $file)
                            <div class="form-group">
                                <label for="file">File: {{ str_replace("images/assignments/$unit_assignment->id/", "", $file->path) }}</label>
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
        $('#editassignmentfile').validate({
            rules: {
                path: {
                    required: true,
                }
            },
            messages: {
                path: "Please upload file",
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

    $('#editassignmentfile').submit(function(e) {
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
