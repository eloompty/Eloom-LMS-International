@extends('user::layouts.master')
@section('title', 'Admin | Student Intake Unit Submission')

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
                <h1>Student Intake Unit Submission</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeUnit->studentIntakeCourse->student_id) }}">Student Intake</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.unit.index', $studentIntakeUnit->student_intake_course_id) }}">Student Intake Unit</a></li>
                    <li class="breadcrumb-item active">Student Intake Unit Submission</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $studentIntakeUnit->studentIntakeCourse->student_id) }}'s Intake Unit Submission</h3>
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
                                    <th>Action</th>
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
                                        @elseif ($value->assignment->type == 'mcq')
                                        <a href="{{ route('admin.student.intake.unit.submission.mcq', $value->id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                        @else
                                        <a href="{{ route('admin.student.intake.unit.submission.question', $value->id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                        @endif
                                    </td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    <td>@if ($value->assignment_grade_id == NULL) Not graded @else {{ $value->assignmentGrade->name }} @endif</td>
                                    <td>{{ $value->remarks }}</td>
                                    <td>{{ $value->credits }}</td>
                                    @if ($value->graded_date == NULL)
                                    <td> -
                                    @else
                                    <td data-sort='{{ convertDate($value->graded_date) }}'>{{ dateFormat($value->graded_date) }}
                                    @endif
                                    <td>
                                        <a href="{{ route('admin.student.intake.unit.submission.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    </td>
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