@extends('user::layouts.master')
@section('title', 'Admin | Agent Branch User Devices')

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
                <h1>Agent Branch User Devices</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.index') }}">Agents</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.branch.index', $agent_branch_user->branch_id) }}">Branches</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.branch.user.index', $agent_branch_user->id) }}">Users</a></li>
                    <li class="breadcrumb-item active">Devices</li>
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
                        <h3 class="card-title">List of {{ $agent_branch_user->name }}'s Devices</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    @if(count($devices) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Device Type</th>
                                    <th>Device Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devices as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->device_type }}</td>
                                    <td>{{ $value->device_name }}</td>
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
                                    <th>Device Type</th>
                                    <th>Device Name</th>
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


