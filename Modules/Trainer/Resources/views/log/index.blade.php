@extends('user::layouts.master')
@section('title', 'Admin | Teacher Logs')

@section('content')
@if ($text = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $text }}</strong>
    </div>
@endif
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Teacher Logs</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Teachers</a></li>
                    <li class="breadcrumb-item active">Logs</li>
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
                        <h3 class="card-title">List of {{ userName('Trainer', $trainer->id) }}'s Logs</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    @if(count($logs) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Activity</th>
                                    <th>Date Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->action }}</td>
                                    <td>{{ dateTimeFormat($value->created_at) }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span> 
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span> 
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span> 
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Activity</th>
                                    <th>Date Time</th>
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


