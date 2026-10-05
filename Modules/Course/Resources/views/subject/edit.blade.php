@extends('user::layouts.master')
@section('title', 'Admin | Edit Subject')

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
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="editsubject" action="{{ route('admin.course.subject.update', $subject->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit Subject</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Subject Name</label> <span class="required">*</span>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Subject Name" value="{{ $subject->name }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="code">Subject Code</label> <span class="required">*</span>
                                    <input type="text" name="code" class="form-control" id="code" placeholder="Enter Subject Code" value="{{ $subject->code }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="credits">Subject Credits</label> <span class="required">*</span>
                                    <input type="text" name="credits" class="form-control" id="credits" placeholder="Enter Subject Credits" value="{{ $subject->credits }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="teaching_hours">Subject Teaching Hours</label>
                                    <input type="text" name="teaching_hours" class="form-control" id="teaching_hours" placeholder="Enter Subject Credits" value="{{ $subject->teaching_hours }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="full_marks">Subject Full Marks</label>
                                    <input type="text" name="full_marks" class="form-control" id="full_marks" placeholder="Enter Subject Full Marks" value="{{ $subject->full_marks }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="theory">Subject Theory Marks</label>
                                    <input type="text" name="theory" class="form-control" id="theory" placeholder="Enter Subject Theory Marks" value="{{ $subject->theory }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="practical">Subject Practical Marks</label>
                                    <input type="text" name="practical" class="form-control" id="practical" placeholder="Enter Subject Practical Marks" value="{{ $subject->practical }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="internal">Subject Internal Marks</label>
                                    <input type="text" name="internal" class="form-control" id="internal" placeholder="Enter Subject Internal Marks" value="{{ $subject->internal }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="type">Type</label> <span class="required">*</span>
                                    <select name="type" class="form-control" id="type">
                                        <option value="" disabled>-- Select Type --</option>
                                        <option @if($subject->type == 'Core')selected @endif value="Core">Active</option>
                                        <option @if($subject->type == 'Elective')selected @endif value="Elective">Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" disabled>-- Select Status --</option>
                                        <option @if($subject->status == '1')selected @endif value="1">Active</option>
                                        <option @if($subject->status == '0')selected @endif value="0">Inactive</option>
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
        $('#editsubject').validate({
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