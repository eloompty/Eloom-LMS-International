@extends('user::layouts.master')
@section('title', 'Admin | Add Unit')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $subject->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.semester.index', $subject->course_id) }}" @else href="{{ route('admin.unregistered.semester.index', $subject->course_id) }}" @endif>Semesters</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.subject.index', $subject->semester_id) }}" @else href="{{ route('admin.unregistered.subject.index', $subject->semester_id) }}" @endif>Subjects</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.unit.index', $subject->id) }}" @else href="{{ route('admin.unregistered.unit.index', $subject->id) }}" @endif>Units</a></li>
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
        <form id="addunit" @if ($subject->course->registered == 1) action="{{ route('admin.course.unit.store', $subject->id) }}" @else action="{{ route('admin.unregistered.unit.store', $subject->id) }}" @endif method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Unit</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="code">Unit Code</label> <span class="required">*</span>
                                    <input type="text" name="code" class="form-control" id="code" placeholder="Enter Unit Code">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name">Unit Name</label> <span class="required">*</span>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Unit Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="hours">Teaching Hours</label> <span class="required">*</span>
                                    <input type="number" name="hours" class="form-control" id="hours" placeholder="Enter Teaching Hours">
                                </div>
                                <div class="form-group col-md-4">
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
        $('#addunit').validate({
            rules: {
                semester: {
                    required: true,
                },
                code: {
                    required: true,
                },
                name: {
                    required: true,
                },
                type: {
                    required: true,
                },
                education_field: {
                    required: true,
                },
                hours: {
                    required: true,
                    digits: true,
                    maxlength: 4
                },
                duration: {
                    required: true,
                },
                due_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                semester: "Please choose one semester",
                code: "Please enter unit code",
                name: "Please enter unit name",
                type: "Please select unit type",
                education_field: "Please select education field",
                hours: {
                    required: "Please enter hours",
                    maxlength: "Hours cannot be more than 4 digits"
                },
                duration: "Please enter unit duration",
                due_date: "Please enter due date",
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