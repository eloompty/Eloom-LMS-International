@extends('student::student.layouts.master')
@section('title', 'Student | Attendance')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Attendance <small class="text-muted">— {{ $studentIntake->intakeUnit->unit->name }}</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntake->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntake->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.unit.index', $studentIntake->student_intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('partials.student-attendance-list', ['title' => $studentIntake->intakeUnit->unit->name])
    </div>
</section>
@endsection
