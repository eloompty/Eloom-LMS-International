@extends('user::layouts.master')
@section('title', 'Admin | Intake Units')

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
                <h1>{{ $intakeSubject->intakeCourse->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeSubject->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.semester.index', $intakeSubject->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.subject.index', $intakeSubject->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item active">Units</li>
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
                        <h3 class="card-title">List of {{ $intakeSubject->intakeCourse->course->course_name }}'s Units</h3>
                        <br>
                        <br>
                        @if($status == NULL)<a href="{{ route('admin.intake.unit.index', $intakeSubject->id) }}?status=deleted" class="btn btn-danger">Show Deleted Units</a>
                        @elseif ($status == 'deleted')
                        <a href="{{ route('admin.intake.unit.index', $intakeSubject->id) }}" class="btn btn-success">Show Active Units</a>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($units) > 0)
                        <table id="example3" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Teaching Hours</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->unit->code }}</td>
                                    <td>{{ $value->unit->name }}</td>
                                    <td>{{ $value->unit->hours }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>@if ($value->starting_date == NULL) - @else {{ dateFormat($value->starting_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>@if ($value->ending_date == NULL) - @else {{ dateFormat($value->ending_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>{{ userName('Trainer', $value->trainer_id) }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.intake.unit.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.intake.unit.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                        <a href="{{ route('admin.intake.unit.attendance.index', [$value->id, date('Y'), date('m')]) }}" class="btn btn-warning btn-sm"><i class="fas fa-check"></i> Attendance</a>
                                        <a href="{{ route('admin.intake.unit.assignment.index', [$value->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-chart-pie"></i> Assignments</a>
                                        <a href="{{ route('admin.intake.unit.onlineclass.index', [$value->id]) }}" class="btn btn-secondary btn-sm"><i class="fas fa-laptop"></i> Zoom Class</a>
                                        <a href="{{ route('admin.intake.unit.team.index', [$value->id]) }}" class="btn btn-secondary btn-sm"><i class="fas fa-laptop"></i> Teams Class</a>
                                        @if ($intakeSubject->intakeCourse->marking_type == 'Unit')
                                        <a href="{{ route('admin.intake.unit.marking.index', $value->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-check"></i> Marking</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Teaching Hours</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
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