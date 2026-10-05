@extends('user::layouts.master')
@section('title', 'Admin | Intake Courses')

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
                <h1>Intake Courses</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item active">Courses</li>
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
                        <h3 class="card-title">List of {{ $intake->name }} ({{ $intake->reference_name }})'s Courses</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.intake.course.create', $intake->id) }}" class="btn btn-success">Add Course</a></div>
                        @if($status == NULL)<a href="{{ route('admin.intake.course.index', $intake->id) }}?status=deleted" class="btn btn-danger">Show Deleted Courses</a>
                        @elseif ($status == 'deleted')
                        <a href="{{ route('admin.intake.course.index', $intake->id) }}" class="btn btn-success">Show Active Courses</a>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($courses) > 0)
                        <table id="example3" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Reference</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Duration</th>
                                    <th>Semesters</th>
                                    <th>Assigned Teacher</th>
                                    <th>Marking Type</th>
                                    <th>Enrolled Students</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->course->course_name }}</td>
                                    <td>{{ $value->reference_name }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>{{ dateFormat($value->starting_date) }}</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>{{ dateFormat($value->ending_date) }}</td>
                                    <td>{{ $value->duration }}</td>
                                    <td><a href="{{ route('admin.intake.semester.index', $value->id) }}" class="btn btn-info btn-sm">{{ $value->intakeSemester->count() }}</a></td>
                                    <td>@if ($value->trainer == NULL) Unassigned @else {{ userName('Trainer', $value->trainer_id) }} @endif</td>
                                    <td>{{ $value->marking_type }}</td>
                                    <td><a href="{{ route('admin.intake.course.student.index', $value->id) }}" class="btn btn-info btn-sm">{{ $value->enrolled_student }}</a></td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.intake.course.fee.index', $value->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-money-bill"></i> Fees</a>
                                        <!-- <a href="{{ route('admin.intake.course.time.index', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-clock"></i> Time Table</a> -->
                                        <a href="{{ route('admin.intake.course.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2 && $value->studentIntake->count() == 0)
                                        <a href="{{ route('admin.intake.course.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Reference</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Duration</th>
                                    <th>Semesters</th>
                                    <th>Assigned Teacher</th>
                                    <th>Marking Type</th>
                                    <th>Enrolled Students</th>
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