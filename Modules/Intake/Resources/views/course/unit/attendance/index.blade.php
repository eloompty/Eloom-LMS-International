@extends('user::layouts.master')
@section('title', 'Admin | Unit Attendance')

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
                <h1>Attendance <small class="text-muted">— {{ $intakeUnit->unit->name }}</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeUnit->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.semester.index', $intakeUnit->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.subject.index', $intakeUnit->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.index', $intakeUnit->intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('partials.attendance-marking')
    </div>
</section>
@endsection

@section('scripts')
@include('partials.attendance-marking-scripts')
@endsection
