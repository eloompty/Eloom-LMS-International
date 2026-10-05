@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Edit Intake Marking')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $intakeSubjectMark->intakeSubject->intakeCourse->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $intakeSubjectMark->intakeSubject->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $intakeSubjectMark->intakeSubject->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.mark.index', $intakeSubjectMark->intake_subject_id) }}">Markings</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $intakeSubjectMark->intakeSubject->subject->name }}'s Marking</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editintakeMarking" action="{{ route('trainer.subject.mark.update', $intakeSubjectMark->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Name</label> <span class="required">*</span>
                                    <input type="text" name="name" class="form-control" id="date" value="{{ $intakeSubjectMark->name }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="full_marks">Full marks</label> <span class="required">*</span>
                                    <input type="text" name="full_marks" class="form-control" id="full_marks" value="{{ $intakeSubjectMark->full_marks }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pass_marks">Pass marks</label> <span class="required">*</span>
                                    <input type="text" name="pass_marks" class="form-control" id="pass_marks" value="{{ $intakeSubjectMark->pass_marks }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($intakeSubjectMark->status == '1')selected @endif value="1">Active</option>
                                        <option @if($intakeSubjectMark->status == '0')selected @endif value="0">Inactive</option>
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
        $('#editintakeMarking').validate({
            rules: {
                "name": {
                    required: true,
                },
                "full_marks": {
                    required: true
                },
                "pass_marks": {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                "name": "Please enter name",
                "full_marks": "Please enter full marks",
                "pass_marks": "Please choose pass marks",
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
