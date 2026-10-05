@extends('student::student.layouts.master')
@section('title', 'Student | Subjects')

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
                <h1>{{ $semester->studentIntakeCourse->intakeCourse->course->course_name }}'s Subjects</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $semester->student_intake_course_id) }}">Semesters</a></li>
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
                        <h3 class="card-title">List of @if(count($subjects) > 0) {{ $semester->studentIntakeCourse->intakeCourse->course->course_name }}'s @endif Subjects</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($subjects) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeSubject->subject->name }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>@if ($value->starting_date == NULL) - @else {{ dateFormat($value->starting_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>@if ($value->ending_date == NULL) - @else {{ dateFormat($value->ending_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (getSettingValue('teaching_system') == 'Unit')
                                        <a href="{{ route('student.unit.index', $value->id) }}" class="btn btn-info btn-sm">Units</a>
                                        @endif
                                        @if ($semester->studentIntakeCourse->intakeCourse->marking_type == 'Subject')
                                        <a href="{{ route('student.subject.marks.index', $value->id) }}" class="btn btn-info btn-sm">Marks</a>
                                        @endif
                                        <a href="{{ route('student.subject.resource.index', $value->id) }}" class="btn btn-info btn-sm">Resources</a>
                                        <a href="{{ route('student.subject.time.index', $value->id) }}" class="btn btn-info btn-sm">Time Table</a>
                                        <a href="{{ route('student.subject.attendance.index', $value->id) }}" class="btn btn-info btn-sm">Attendance</a>
                                        <a href="{{ route('student.subject.assignment.index', $value->id) }}" class="btn btn-info btn-sm">Assignments</a>
                                        <a href="{{ route('student.subject.chat.index', $value->id) }}" class="btn btn-info btn-sm">Group Chats</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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
