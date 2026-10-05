@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Student Assignment Submissions')

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
                    <li class="breadcrumb-item"><a href="{{ route('trainer.student.index', $trainerIntake->intake_course_id) }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.student.unit.index', [$id, $trainerIntake->intake_course_id]) }}">Units</a></li>
                    <li class="breadcrumb-item active">Assignment Submissions</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $id) }}'s {{ $trainerIntake->intakeUnit->unit->name}}'s Assignment Submission</h3>
                        @if ($studentIntakeUnit->is_complete == 0)
                        <div class="col-md-12 text-right"><a href="{{ route('trainer.student.unit.complete', [$id, $trainerIntake->intake_unit_id]) }}" class="btn btn-success">Complete Unit</a></div>
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
                                    <th>Submission</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Credits</th>
                                    <th>Assigned Date</th>
                                    <th>Due Date</th>
                                    <th>Submitted Date</th>
                                    <th>Graded Date</th>
                                    @if ($studentIntakeUnit->is_complete == 0)
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->assignment->name }}</td>
                                    <td><a href="{{ asset($value->image) }}" target="_blank"><img src="{{ asset(filePath($value->image)) }}" alt="" width="48" /></a></td>
                                    <td><a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a></td>
                                    <td>@if ($value->assignment_grade_id == NULL) Not graded @else {{ $value->assignmentGrade->name }} @endif</td>
                                    <td>{{ $value->remarks }}</td>
                                    <td>{{ $value->credits }}</td>
                                    <td data-sort='{{ convertDate($value->assignment->created_at) }}'>{{ dateFormat($value->assignment->created_at) }}</td>
                                    <td data-sort='{{ convertDate($value->assignment->due_date) }}'>{{ dateFormat($value->assignment->due_date) }}</td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    @if ($value->graded_date == NULL)
                                    <td> -
                                    @else
                                    <td data-sort='{{ convertDate($value->graded_date) }}'>{{ dateFormat($value->graded_date) }}
                                    @endif
                                    @if ($studentIntakeUnit->is_complete == 0)
                                    <td>
                                        <a href="{{ route('trainer.submission.edit', [$value->id, $trainerIntake->id]) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Assignment</th>
                                    <th>Submission</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Credits</th>
                                    <th>Assigned Date</th>
                                    <th>Due Date</th>
                                    <th>Submitted Date</th>
                                    <th>Graded Date</th>
                                    @if ($studentIntakeUnit->is_complete == 0)
                                    <th>Action</th>
                                    @endif
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