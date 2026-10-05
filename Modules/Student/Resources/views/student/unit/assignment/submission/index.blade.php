@extends('student::student.layouts.master')
@section('title', 'Student | Assignment Submissions')

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
                <h1>Assignment Submissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntakeUnit->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntakeUnit->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.unit.index', $studentIntakeUnit->student_intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.assignment.index', $studentIntakeUnit->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item active">Submissions</li>
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
                        <h3 class="card-title">List of Assignment Submissions</h3>
                        @if ($assignment->type == 'file' && (count($submissions) == 0 || ($resubmission && $resubmission->status == 1 && ($resubmission->due_date == NULL || $resubmission->due_date >= date('Y-m-d')))))
                        <div class="col-md-12 text-right"><a href="{{ route('student.submission.create', [$assignment->id, $studentIntakeUnit->id]) }}" class="btn btn-success">Add Assignment</a></div>
                        @elseif ($resubmission_request == true)
                        <form action="{{ route('student.submission.resubmission.index', $assignment->id) }}" method="POST">
                            @csrf
                            <div class="col-md-12 text-right"><button type="submit" class="btn btn-success">Resubmission Request</button></div>
                        </form>
                        @elseif ($resubmission && $resubmission->status == 0)
                        <div class="col-md-12 text-right"><a href="javascript:void(0)" class="btn btn-success">Resubmission request has been sent</a></div>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($submissions) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Assignment</th>
                                    <th>Submitted Date</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Credits</th>
                                    <th>Graded Date</th>
                                    <th>Graded File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->assignment->name }}</td>
                                    <td>
                                        @if ($value->assignment->type == 'file')
                                        <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                        @elseif ($value->assignment->type == 'multiple files')
                                        <a href="{{ route('student.submission.files.index', [$value->id, $studentIntakeUnit->id]) }}" class="btn btn-info">View</a>
                                        @elseif ($value->assignment->type == 'question')
                                        <a href="{{ route('student.submission.question.index', [$value->id, $studentIntakeUnit->id]) }}" class="btn btn-info">View</a>
                                        @else
                                        <a href="{{ route('student.submission.mcq.index', [$value->id, $studentIntakeUnit->id]) }}" class="btn btn-info">View</a>
                                        @endif
                                    </td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    <td>@if ($value->assignment_grade_id == NULL) Waiting to be Graded @else {{ $value->assignmentGrade->name }} @endif</td>
                                    <td>{{ $value->remarks }}</td>
                                    <td>{{ $value->credits }}</td>
                                    @if ($value->graded_date == NULL)
                                    <td> -
                                    @else
                                    <td data-sort='{{ convertDate($value->graded_date) }}'>{{ dateFormat($value->graded_date) }}
                                    @endif
                                    <td>@if ($value->show_to_student == 1) <a href="{{ asset($value->graded_file) }}" target="_blank"><img src="{{ asset(filePath($value->graded_file)) }}" alt="" width="48" /></a> @else - @endif </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Assignment</th>
                                    <th>Submitted Date</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Credits</th>
                                    <th>Graded Date</th>
                                    <th>Graded File</th>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Data Found</h3>
                        @endif
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