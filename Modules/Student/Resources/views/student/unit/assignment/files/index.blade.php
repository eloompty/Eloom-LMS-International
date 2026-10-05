@extends('student::student.layouts.master')
@section('title', 'Student | Multiple Files')

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

<style>
    .image-container {
        text-align: center;
        width: 150px;
        margin: 16px auto;
    }

    .image-container img {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .image-container .name {
        margin-top: 10px;
        font-size: 12px;
        font-weight: bold;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Multiple Files</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntakeUnit->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntakeUnit->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.unit.index', $studentIntakeUnit->student_intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.assignment.index', $studentIntakeUnit->intake_unit_id) }}">Assignments</a></li>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of {{ $assignment->name }}'s Files</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('student.submission.files.create', [$assignment->id, $studentIntakeUnit->id]) }}" class="btn btn-success">Add Assignment</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            @foreach($files as $file)
                            @php
                            $file_path = $file->path;
                            $name = explode('/', $file_path);
                            @endphp
                            <div class="col-md-2">
                                <div class="image-container">
                                    <a href="{{asset($file->path)}}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="Image" style="width: 65px; border: #ebebeb 1px solid;"></a>
                                    <div class="name">{{ array_pop($name) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /.card-body -->
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