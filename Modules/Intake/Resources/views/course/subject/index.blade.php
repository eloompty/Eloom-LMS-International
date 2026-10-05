@extends('user::layouts.master')
@section('title', 'Admin | Intake Subjects')

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
                <h1>{{ $intakeSemester->intakeCourse->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeSemester->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.semester.index', $intakeSemester->intakeCourse->id) }}">Semesters</a></li>
                    <li class="breadcrumb-item active">Subjects</li>
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
                        <h3 class="card-title">List of {{ $intakeSemester->intakeCourse->course->course_name }}'s Subjects</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($subjects) > 0)
                        <table id="example3" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Credit Hours</th>
                                    <th>Teaching Hours</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Teacher</th>
                                    <th>Sequence</th>
                                    <th>Status</th>
                                    @if (getSettingValue('teaching_system') == 'Unit')<th>Units</th>@endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->subject->name }}</td>
                                    <td>{{ $value->subject->credits }}</td>
                                    <td>{{ $value->subject->teaching_hours }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>@if ($value->starting_date == NULL) - @else {{ dateFormat($value->starting_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>@if ($value->ending_date == NULL) - @else {{ dateFormat($value->ending_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>{{ userName('Trainer', $value->trainer_id) }}</td>
                                    <td>{{ $value->sequence }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    @if (getSettingValue('teaching_system') == 'Unit')
                                    <td><a href="{{ route('admin.intake.unit.index', $value->id) }}" class="btn btn-info btn-sm">{{ $value->intakeUnit->count() }}</a></td>
                                    @endif
                                    <td>
                                        <a href="{{ route('admin.intake.subject.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <a href="{{ route('admin.intake.subject.time.index', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-clock"></i> Time Table</a>
                                        <a href="{{ route('admin.intake.subject.attendance.index', [$value->id, date('Y'), date('m')]) }}" class="btn btn-warning btn-sm"><i class="fas fa-check"></i> Attendance</a>
                                        @if ($intakeSemester->intakeCourse->marking_type == 'Subject')
                                        <a href="{{ route('admin.intake.subject.marking.index', $value->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-check"></i> Markings</a>
                                        @endif
                                        <a href="{{ route('admin.intake.subject.assignment.index', $value->id) }}"  class="btn btn-primary btn-sm"><i class="fas fa-chart-pie"></i> Assignments</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Credit Hours</th>
                                    <th>Teaching Hours</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Teacher</th>
                                    <th>Sequence</th>
                                    <th>Status</th>
                                    @if (getSettingValue('teaching_system') == 'Unit')<th>Units</th>@endif
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
