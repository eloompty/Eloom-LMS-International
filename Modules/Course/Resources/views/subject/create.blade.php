@extends('user::layouts.master')
@section('title', 'Admin | Add Subject')

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
                    <li class="breadcrumb-item"><a @if ($semester->course->registered == 1) href="{{ route('admin.course.subject.index', $semester->id) }}" @else href="{{ route('admin.unregistered.subject.index', $semester->id) }}" @endif>Subjects</a></li>
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
        <form id="addsubject" @if ($semester->course->registered == 1) action="{{ route('admin.course.subject.store', $semester->id) }}" @else action="{{ route('admin.unregistered.subject.store', $semester->id) }}" @endif method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Subject of {{ $semester->name }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Subject Name</label> <span class="required">*</span>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Subject Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="code">Subject Code</label> <span class="required">*</span>
                                    <input type="text" name="code" class="form-control" id="code" placeholder="Enter Subject Code">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="credits">Subject Credits Hours</label> <span class="required">*</span>
                                    <input type="text" name="credits" class="form-control" id="credits" placeholder="Enter Subject Credits Hours">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="teaching_hours">Subject Teaching Hours</label>
                                    <input type="text" name="teaching_hours" class="form-control" id="teaching_hours" placeholder="Enter Teaching Hours">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="full_marks">Subject Full Marks</label>
                                    <input type="text" name="full_marks" class="form-control" id="full_marks" placeholder="Enter Subject Full Marks">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="theory">Subject Theory Marks</label>
                                    <input type="text" name="theory" class="form-control" id="theory" placeholder="Enter Subject Theory Marks">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="practical">Subject Practical Marks</label>
                                    <input type="text" name="practical" class="form-control" id="practical" placeholder="Enter Subject Practical Marks">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="internal">Subject Internal Marks</label> 
                                    <input type="text" name="internal" class="form-control" id="internal" placeholder="Enter Subject Internal Marks">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="type">Subject Type</label> <span class="required">*</span>
                                    <select name="type" class="form-control" id="type">
                                        <option value="" disabled>-- Select type --</option>
                                        <option value="Core" selected>Core</option>
                                        <option value="Elective">Elective</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
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
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
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
        $('#addsubject').validate({
            rules: {
                credits: {
                    required: true,
                },
                code: {
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
                credits: "Please enter credits",
                code: "Please enter code",
                name: "Please enter name",
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