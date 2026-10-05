@extends('student::student.layouts.master')
@section('title', 'Student | Courses')

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
                <h1>Courses</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
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
                        <h3 class="card-title">List of Courses</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if($courses->count() > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Course Code</th>
                                    <th>Teacher</th>
                                    <th>Duration</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Semesters</th>
                                    <th>Time Table</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($courses as $index => $value)
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeCourse->intake->name }}</td>
                                    <td>{{ $value->intakeCourse->course->course_name }}</td>
                                    <td>{{ $value->intakeCourse->course->course_code }}</td>
                                    <td>@if ($value->trainer->trainer_id == NULL) - @else {{ userName('Trainer', $value->trainer->trainer_id) }} @endif</td>
                                    <td>{{ $value->duration }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>{{ dateFormat($value->starting_date) }}</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>{{ dateFormat($value->ending_date) }}</td>
                                    <td><a href="{{ route('student.semester.index', $value->id) }}" class="btn btn-info btn-sm">View</a></td>
                                    <td>
                                        @if(count($value->intakeCourseTime) > 0)
                                        <table>
                                            @foreach($value->intakeCourseTime as $index => $time)
                                            <tr>
                                                <b>{{ $time->day }} :</b> {{ timeFormat($time->from) }}-{{ timeFormat($time->to) }}@if($time->classroom != NULL)({{ $time->classroom }})@endif <br>
                                            </tr>
                                            @endforeach
                                        </table>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Course Code</th>
                                    <th>Teacher</th>
                                    <th>Duration</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Semesters</th>
                                    <th>TimeTable</th>
                                    <th>Status</th>
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
