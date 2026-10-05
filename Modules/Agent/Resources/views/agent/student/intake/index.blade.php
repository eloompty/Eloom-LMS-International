@extends('agent::agent.layouts.master')
@section('title', 'Agent | Student Intakes')

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
                <h1>Student Intakes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('agent.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">Intakes</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $student->id) }} Intakes</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('agent.student.intake.create', $student->id) }}" class="btn btn-success">Add Intake</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($intakes) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Duration</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($intakes as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeCourse->intake->name }}</td>
                                    <td>{{ $value->intakeCourse->course->course_name }}</td>
                                    <td>{{ $value->duration }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>{{ dateFormat($value->starting_date) }}</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>{{ dateFormat($value->ending_date) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Duration</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
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