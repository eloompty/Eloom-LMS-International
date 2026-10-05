@extends('user::layouts.master')
@section('title', 'Admin | Intake Unit Teams Classes')

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
                <h1>Teams Classes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeUnit->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.index', $intakeUnit->id) }}">Units</a></li>
                    <li class="breadcrumb-item active">Teams Classes</li>
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
                        <h3 class="card-title">List of {{ $intakeUnit->unit->name }}'s Unit Teams Classes</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('login.microsoft', [$intakeUnit->id, 'intake_unit']) }}" class="btn btn-success">Add Teams Class</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($teams) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Content</th>
                                    <th>Date</th>
                                    <th>Join Url</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teams as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->subject }}</td>
                                    <td>{{ $value->content }}</td>
                                    <td>
                                        <b>Start Date:</b> {{ dateFormat($value->start_date) }} {{ timeFormat($value->start_time) }} <br>
                                        <b>End Date:</b> {{ dateFormat($value->end_date) }} {{ timeFormat($value->end_time) }}
                                    </td>
                                    <td><a href="{{ $value->join_url }}" class="btn btn-info btn-sm" target="_blank"> Join</a></td>
                                    <td>
                                        @if ($value->created_user_type == 'Trainer') {{ userName('Trainer', $value->created_user_id) }} (Trainer)
                                        @else {{ userName('Admin', $value->created_user_id) }} (Admin)
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Content</th>
                                    <th>Date</th>
                                    <th>Join Url</th>
                                    <th>Created By</th>
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