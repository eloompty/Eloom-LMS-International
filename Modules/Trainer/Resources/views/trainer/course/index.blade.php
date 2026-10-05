@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Courses')

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
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
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
                                    <th>Course</th>
                                    <th>Intake</th>
                                    <th>Starting Date</th>
                                    <th>Duration</th>
                                    <th>Semesters</th>
                                    <th>Enrolled Student</th>
                                    <th>Time Table</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeCourse->course->course_name }}</td>
                                    <td>{{ $value->intakeCourse->intake->name }}</td>
                                    <td data-sort='{{ convertDate($value->intakeCourse->intake->starting_date) }}'>{{ dateFormat($value->intakeCourse->intake->starting_date) }}</td>
                                    <td>{{ $value->intakeCourse->duration }}</td>
                                    <td><a href="{{ route('trainer.semester.index', $value->intake_course_id) }}" class="btn btn-info btn-sm">View</a></td>
                                    <td><a href="{{ route('trainer.student.index', $value->intake_course_id) }}" class="btn btn-info btn-sm">{{ $value->enrolled_student }}</a></td>
                                    <td>
                                        @if(count($value->intakeCourseTime) > 0)
                                        <table>
                                            @foreach($value->intakeCourseTime as $index => $time)
                                            <tr>
                                                <b>{{ $time->day }} :</b> {{ timeFormat($time->from) }} - {{ timeFormat($time->to) }} <br>
                                            </tr>
                                            @endforeach
                                        </table>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Intake</th>
                                    <th>Starting Date</th>
                                    <th>Duration</th>
                                    <th>Semesters</th>
                                    <th>Enrolled Student</th>
                                    <th>Time Table</th>
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