@extends('student::student.layouts.master')
@section('title', 'Student | Assignments')

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
                <h1>Assignments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntake->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntake->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.unit.index', $studentIntake->student_intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item active">Assignments</li>
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
                        <h3 class="card-title">List of {{ $studentIntake->intakeUnit->unit->name }}'s Assignments</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($assignments) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Teacher</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <th>Submission Due Date</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ userName('Trainer', $value->trainer_id) }}</td>
                                    <td>{{ strtoupper(($value->type)) }}</td>
                                    <td>
                                        @if ($value->type == 'file' && ($value->submission_count == 0 || ($value->resubmission && $value->resubmission->status == 1)))
                                        <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'multiple files' && ($value->submission_count == 0 || ($value->resubmission && $value->resubmission->status == 1)))
                                        <a href="{{ route('student.assignment.files.index', [$value->id, $studentIntake->id]) }}"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'question' && ($value->submission_count == 0 || ($value->resubmission && $value->resubmission->status == 1)))
                                        <a href="{{ route('student.assignment.question.index', [$value->id, $studentIntake->id]) }}"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'mcq' && ($value->submission_count == 0 || ($value->resubmission && $value->resubmission->status == 1)))
                                        <a href="{{ route('student.assignment.mcq.index', [$value->id, $studentIntake->id]) }}"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                        @else
                                        <img src="{{ asset('files/submit.png') }}" alt="" width="48" />
                                        @endif
                                    </td>
                                    <td data-sort='{{ convertDate($due_date) }}'>{{ dateFormat($due_date) }} @if ($resubmission_due_date == true) (New due date) @endif</td>
                                    <td>{{ $value->grade }}</td>
                                    <td>
                                        @if ($value->type == 'file' && ($value->submission_count == 0 || ($value->resubmission && $value->resubmission->status == 1)))
                                        <a href="{{ asset($value->path) }}" class="btn btn-info btn-sm" target="_blank">View</a>
                                        @elseif ($value->type == 'multiple files' && ($value->submission_count == 0 || ($value->resubmission && $value->resubmission->status == 1)))
                                        <a href="{{ route('student.assignment.files.index', [$value->id, $studentIntake->id]) }}" class="btn btn-info btn-sm">View</a>
                                        @elseif ($value->type == 'question' && ($value->submission_count == 0 || ($value->resubmission && $value->resubmission->status == 1)))
                                        <a href="{{ route('student.assignment.question.index', [$value->id, $studentIntake->id]) }}" class="btn btn-info btn-sm">View</a>
                                        @elseif ($value->type == 'mcq' && ($value->submission_count == 0 || ($value->resubmission && $value->resubmission->status == 1)))
                                        <a href="{{ route('student.assignment.mcq.index', [$value->id, $studentIntake->id]) }}" class="btn btn-info btn-sm">View</a>
                                        @else
                                        <button type="button" class="btn btn-info btn-sm" disabled>View</button>
                                        @endif
                                        <a href="{{ route('student.submission.index', [$value->id, $studentIntake->id]) }}" class="btn btn-info btn-sm"> Submissions</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Teacher</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <th>Submission Due Date</th>
                                    <th>Grade</th>
                                    <th>Action</th>
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