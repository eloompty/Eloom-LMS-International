@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Assignment Submissions')

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
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $trainerIntake->intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
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
                        <h3 class="card-title">List of submissions</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($submissions) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Student</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Credits</th>
                                    <th>Assigned Date</th>
                                    <th>Submitted Date</th>
                                    <th>Graded Date</th>
                                    <th>Graded File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->assignment->name }}</td>
                                    <td><a href="{{ route('trainer.student.unit.index', [$value->student_id, $trainerIntake->intake_course_id]) }}">{{ userName('Student', $value->student_id) }}</a></td>
                                    <td>{{ strtoupper($value->assignment->type) }}</td>
                                    <td>
                                        @if ($value->assignment->type == 'file')
                                        <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                        @elseif ($value->assignment->type == 'multiple files')
                                        <a href="{{ route('trainer.submission.files.index', [$value->id, $trainerIntake->id]) }}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a>
                                        @elseif ($value->assignment->type == 'question')
                                        <a href="{{ route('trainer.submission.question.index', [$value->id, $trainerIntake->id]) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                        @else
                                        <a href="{{ route('trainer.submission.mcq.index', [$value->id, $trainerIntake->id]) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                        @endif
                                    </td>
                                    <td>@if ($value->assignment_grade_id == NULL) Not graded @else {{ $value->assignmentGrade->name }} @endif</td>
                                    <td>{{ $value->remarks }}</td>
                                    <td>{{ $value->credits }}</td>
                                    <td data-sort='{{ convertDate($value->assignment->created_at) }}'>{{ dateFormat($value->assignment->created_at) }}</td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    @if ($value->graded_date == NULL)
                                    <td> -
                                    @else
                                    <td data-sort='{{ convertDate($value->graded_date) }}'>{{ dateFormat($value->graded_date) }}
                                    @endif
                                    <td>
                                        @if ($value->assignmentSubmissionGrade == NULL) -
                                        @else <a href="{{ asset($value->assignmentSubmissionGrade->path) }}" target="_blank"><img src="{{ asset(filePath($value->assignmentSubmissionGrade->path)) }}" alt="" width="48" /></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->assignment->type == 'file' && filePath($value->path) == 'files/pdf.png')
                                        <a href="{{ route('trainer.submission.pdf', [$value->id, 'student']) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                        @elseif ($value->assignment->type == 'file')
                                        <a href="{{ asset($value->path) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                        @elseif ($value->assignment->type == 'multiple files')
                                        <a href="{{ route('trainer.submission.files.index', [$value->id, $trainerIntake->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                        @elseif ($value->assignment->type == 'question')
                                        <a href="{{ route('trainer.submission.question.index', [$value->id, $trainerIntake->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                        @else
                                        <a href="{{ route('trainer.submission.mcq.index', [$value->id, $trainerIntake->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                        @endif

                                        @if (filePath($value->path) == 'files/pdf.png')
                                        <a href="{{ route('trainer.submission.pdf', [$value->id, 'student']) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @elseif ($value->assignment->type == 'multiple files')
                                        <a href="{{ route('trainer.submission.files.index', [$value->id, $trainerIntake->id]) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @else
                                        <a href="{{ route('trainer.submission.edit', [$value->id, $trainer_intake_id]) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                <th>#</th>
                                    <th>Name</th>
                                    <th>Student</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Credits</th>
                                    <th>Assigned Date</th>
                                    <th>Submitted Date</th>
                                    <th>Graded Date</th>
                                    <th>Graded File</th>
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
