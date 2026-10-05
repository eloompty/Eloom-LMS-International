@extends('user::layouts.master')
@section('title', 'Admin | Student Intake Marking')

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
                <h1>{{ $intakeSubjectMark->intakeSubject->subject->name }}'s {{ $intakeSubjectMark->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeSubjectMark->intakeSubject->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.semester.index', $intakeSubjectMark->intakeSubject->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.subject.index', $intakeSubjectMark->intakeSubject->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.subject.marking.index', $intakeSubjectMark->intakeSubject->id) }}">Markings</a></li>
                    <li class="breadcrumb-item active">{{ $intakeSubjectMark->name }}</li>
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
                        <h3 class="card-title">Add <small>{{ $intakeSubjectMark->intakeSubject->subject->name }}'s {{ $intakeSubjectMark->name }} Marking</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editintakeMarking" action="{{ route('admin.intake.subject.marking.student.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @foreach($studentIntakeSubjectMarks as $mark)
                            <div class="row" id="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name" value="{{ userName('Student', $mark->studentIntakeSubject->studentIntakeCourse->student_id) }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="full_marks">Full Marks</label>
                                    <input type="text" name="full_marks[]" class="form-control" id="full_marks" placeholder="Enter Full Marks" value="{{ $mark->full_marks }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pass_marks">Pass Marks</label>
                                    <input type="text" name="pass_marks[]" class="form-control" id="pass_marks" placeholder="Enter Pass Marks" value="{{ $mark->pass_marks }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="obtain_marks">Obtained Marks</label>
                                    <input type="text" name="obtain_marks[{{$mark->id}}]" class="form-control" id="obtain_marks" placeholder="Enter Obtained Marks" value="{{ $mark->obtain_marks }}">
                                </div>
                            </div>
                            @endforeach
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