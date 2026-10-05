@extends('user::layouts.master')
@section('title', 'Admin | Student Intake Subject Markings')

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
                <h1>Student Intake Subject Markings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($studentIntakeSubject->studentIntakeCourse->student->is_enrolled == 0) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif>Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeSubject->studentIntakeCourse->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.semester.index', $studentIntakeSubject->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.subject.index', $studentIntakeSubject->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item active">Markings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of {{ userName('Student', $studentIntakeSubject->studentIntakeCourse->student_id) }}'s Intake Markings</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addintakemarking" action="{{ route('admin.student.intake.subject.mark.update', $studentIntakeSubject->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @foreach($marks as $mark)
                            <div class="row" id="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name" value="{{ $mark->name }}" disabled>
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
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
