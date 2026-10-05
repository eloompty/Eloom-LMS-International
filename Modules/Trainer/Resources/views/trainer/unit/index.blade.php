@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Units')

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
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $intakeSubject->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $intakeSubject->intake_semester_id) }}">Subjects</a></li>
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
                        <h3 class="card-title">List of Units</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($units) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Status</th>
                                    <th>Resources</th>
                                    <th>Assignments</th>
                                    <th>Online Classes</th>
                                    <th>Attendace</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeUnit->unit->code }}</td>
                                    <td>{{ $value->intakeUnit->unit->name }}</td>
                                    @if ($value->starting_date == NULL)
                                    <td> -
                                        @else
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>{{ dateFormat($value->starting_date) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>@if ($value->status == 1)<a href="{{ route('trainer.resource.index', $value->id) }}" class="btn btn-info btn-sm"> Resources</a>@endif</td>
                                    <td>@if ($value->status == 1)<a href="{{ route('trainer.assignment.index', $value->id) }}" class="btn btn-info btn-sm"> Assignments</a>@endif</td>
                                    <td>
                                        @if ($value->status == 1)
                                        <a href="{{ route('trainer.onlineclass.index', $value->id) }}" class="btn btn-info btn-sm"> Zoom</a>
                                        <a href="{{ route('trainer.team.index', $value->id) }}" class="btn btn-info btn-sm"> Teams</a>
                                        @endif
                                    </td>
                                    <td>@if ($value->status == 1)<a href="{{ route('trainer.attendance.index', [$value->intake_unit_id, date('Y'), date('m')]) }}" class="btn btn-info btn-sm"> Attendance</a>@endif</td>
                                    <td>
                                        @if ($value->intakeCourse->marking_type == 'Unit')
                                        <a href="{{ route('trainer.unit.mark.index', $value->id) }}" class="btn btn-info btn-sm">Marking</a>
                                        <a href="{{ route('trainer.unit.edit', $value->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Starting Date</th>
                                    <th>Status</th>
                                    <th>Resources</th>
                                    <th>Assignments</th>
                                    <th>Online Classes</th>
                                    <th>Attendace</th>
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
