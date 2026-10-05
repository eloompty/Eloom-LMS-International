@extends('student::student.layouts.master')
@section('title', 'Student | Assignment Submission Files')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Assignment Submission Files</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntakeUnit->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntakeUnit->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.unit.index', $studentIntakeUnit->student_intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.assignment.index', $studentIntakeUnit->intake_unit_id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.submission.index', [$submission->assignment->id, $studentIntakeUnit->id]) }}">Submissions</a></li>
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
                        <h3 class="card-title">Assignment Submissions Files</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($files) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>File</th>
                                    <th>Graded File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($files as $index => $value)
                                <tr>
                                    <td>{{ $no ++ }}</td>
                                    <td><a href="{{ asset($value->file) }}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a></td>
                                    <td>@if ( $value->graded_file == NULL) - @else <a href="{{ asset($value->graded_file) }}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a>@endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>File</th>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Data Found</h3>
                        @endif
                    </div>
                    <!-- /.card-body -->
                    <!-- /.card-body -->
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