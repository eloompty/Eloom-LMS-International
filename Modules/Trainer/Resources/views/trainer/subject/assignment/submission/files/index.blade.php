@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Files')

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
                <h1>Files</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.submission.index', [$submission->assignment_id, $trainerIntake->id]) }}">Submissions</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $submission->assignment->name }} Assignment</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editsubmission" action="{{ route('trainer.subject.submission.update', [$submission->id, $trainer_intake_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="student_id">Student Name</label> <span class="required">*</span>
                                    <input type="text" name="student_id" class="form-control" id="student_id" value="{{ userName('Student', $submission->student_id) }}" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="grade">Grade</label> <span class="required">*</span>
                                    <select class="form-control" name="assignment_grade_id">
                                        <option value="" selected disabled>-- Select Grade --</option>
                                        @foreach ($grades as $key => $value)
                                        <option value="{{ $key }}" @if ($key==$submission->assignment_grade_id) selected @endif>{{ $value }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="credits">Credits</label> <span class="required">*</span>
                                    <input type="number" step="0.01" name="credits" class="form-control" id="credits" value="{{ $submission->credits }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="remarks">Remarks</label> <span class="required">*</span>
                                    <textarea class="form-control" name="remarks">{{ $submission->remarks }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    @if(count($files) > 0)
                                    <table id="example1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>File</th>
                                                <th>Graded File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($files as $index => $value)
                                            <tr>
                                                <td>{{ $index +1 }}</td>
                                                <td><a href="{{ asset($value->file) }}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a></td>
                                                <td>@if ($value->graded_file == NULL) - @else <a href="{{ asset($value->graded_file) }}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a>@endif</td>
                                                <td>@if ($value->graded_file == NULL) <a href="{{ route('trainer.submission.files.edit', [$value->id, $trainer_intake_id]) }}" class="btn btn-info btn-sm"><i class="fas fa-upload"></i> Upload File</a>@endif</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>File</th>
                                                <th>Graded File</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    @else
                                    <h3>No Data Found</h3>
                                    @endif
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
        $('#editsubmission').validate({
            rules: {
                assignment_grade_id: {
                    required: true,
                },
                remarks: {
                    required: true,
                },
                credits: {
                    required: true,
                },
            },
            messages: {
                assignment_grade_id: "Please choose one grade",
                remarks: "Please enter remarks",
                credits: "Please enter credits",
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