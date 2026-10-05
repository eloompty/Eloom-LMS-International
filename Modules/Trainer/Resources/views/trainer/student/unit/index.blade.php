@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Student Units')

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
                <h1>Units</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.student.index', $trainerIntake->intake_course_id) }}">Students</a></li>
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
                        <h3 class="card-title">List of {{ userName('Student', $id) }}'s units</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($units) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Is Locked</th>
                                    <th>Current Status</th>
                                    <th>Assignments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeUnit->unit->code }} {{ $value->intakeUnit->unit->name }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @else <span class="status deleted">Locked</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1 && $value->is_complete == 1)
                                        <span class="status active">Completed</span>
                                        @elseif ($value->status == 1 && $value->is_complete == 0)
                                        <span class="status deleted">Running</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>@if ($value->status == 1)<a href="{{ route('trainer.student.assignment.index', [$id, $value->intake_unit_id]) }}" class="btn btn-info btn-sm"> Assignments</a>@endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Is Locked</th>
                                    <th>Current Status</th>
                                    <th>Assignments</th>
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