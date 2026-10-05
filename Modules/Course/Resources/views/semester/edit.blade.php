@extends('user::layouts.master')
@section('title', 'Admin | Edit Semester')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $semester->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($semester->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($semester->course->registered == 1) href="{{ route('admin.course.semester.index', $semester->course_id) }}" @else href="{{ route('admin.unregistered.semester.index', $semester->course_id) }}" @endif>Semesters</a></li>
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
        <form id="editsemester" action="{{ route('admin.course.semester.update', $semester->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit Semester</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Semester Name</label> <span class="required">*</span>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Semester Name" value="{{ $semester->name }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="credits">Semester Credits</label> <span class="required">*</span>
                                    <input type="text" name="credits" class="form-control" id="credits" placeholder="Enter Semester Credits" value="{{ $semester->credits }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($semester->status == '1')selected @endif value="1">Active</option>
                                        <option @if($semester->status == '0')selected @endif value="0">Inactive</option>
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
        $('#editsemester').validate({
            rules: {
                credits: {
                    required: true,
                },
                name: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                credits: "Please enter semester credits",
                name: "Please enter semester name",
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