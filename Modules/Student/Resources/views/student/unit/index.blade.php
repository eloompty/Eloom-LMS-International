@extends('student::student.layouts.master')
@section('title', 'Student | Units')

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
                <h1>{{ $subject->studentIntakeCourse->intakeCourse->course->course_name }}'s Units</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $subject->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $subject->student_intake_semester_id) }}">Subjects</a></li>
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
                        <h3 class="card-title">List of @if(count($units) > 0) {{ $subject->studentIntakeCourse->intakeCourse->course->course_name }}'s @endif Units</h3>
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
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Time Table</th>
                                    <th>Resources</th>
                                    <th>Submissions</th>
                                    <th>Online Classes</th>
                                    <th>Attendance</th>
                                    <th>Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeUnit->unit->code }}</td>
                                    <td>{{ $value->intakeUnit->unit->name }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>@if ($value->starting_date == NULL) - @else {{ dateFormat($value->starting_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>@if ($value->ending_date == NULL) - @else {{ dateFormat($value->ending_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (count($value->intakeUnitTime) > 0)
                                        <table>
                                            @foreach($value->intakeUnitTime as $time)
                                            <tr>
                                                <b>{{ $time->day }} :</b> {{ timeFormat($time->from) }}-{{ timeFormat($time->to) }}@if($time->classroom != NULL)({{ $time->classroom }})@endif <br>
                                            </tr>
                                            @endforeach
                                        </table>
                                        @endif
                                    </td>
                                    <td>@if ($value->status == 1)<a href="{{ route('student.resource.index', $value->id) }}" class="btn btn-info btn-sm"> Resources</a>@endif</td>
                                    <td>@if ($value->status == 1)<a href="{{ route('student.assignment.index', $value->id) }}" class="btn btn-info btn-sm"> Assignment</a>@endif</td>
                                    <td>
                                        @if ($value->status == 1)
                                        <a href="{{ route('student.onlineclass.index', $value->id) }}" class="btn btn-info btn-sm"> Zoom</a>
                                        <a href="{{ route('student.team.index', $value->id) }}" class="btn btn-info btn-sm"> Teams</a>
                                        @endif
                                    </td>
                                    <td>@if ($value->status == 1)<a href="{{ route('student.attendance.index', $value->id) }}" class="btn btn-info btn-sm"> Attendance</a>@endif</td>
                                    <td>@if ($subject->studentIntakeCourse->intakeCourse->marking_type == 'Unit')<a href="{{ route('student.unit.marks.index', $value->id) }}" class="btn btn-info btn-sm"> Marks</a>@endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Time Table</th>
                                    <th>Resources</th>
                                    <th>Submissions</th>
                                    <th>Online Classes</th>
                                    <th>Attendance</th>
                                    <th>Marks</th>
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
