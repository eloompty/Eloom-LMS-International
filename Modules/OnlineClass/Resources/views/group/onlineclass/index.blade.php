@extends('user::layouts.master')
@section('title', 'Admin | Group Online Classes')

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
                <h1>Group Online Classes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.onlineclass.group.index') }}">Student Group</a></li>
                    <li class="breadcrumb-item active">Group Online Classes</li>
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
                        <h3 class="card-title">List of Group Online Classes</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.onlineclass.group.class.create', $id) }}" class="btn btn-success">Create Zoom</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($zooms) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Meeting Id</th>
                                    <th>Topic</th>
                                    <th>Agenda</th>
                                    <th>Join Url</th>
                                    <th>Password</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zooms as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->meeting_id }}</td>
                                    <td>{{ $value->topic }}</td>
                                    <td>{{ $value->agenda }}</td>
                                    <td>{{ $value->join_url }}</td>
                                    <td>{{ $value->password }}</td>
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
                                        <a href="{{ route('admin.onlineclass.group.class.recording', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> View Recording</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Meeting Id</th>
                                    <th>Topic</th>
                                    <th>Agenda</th>
                                    <th>Join Url</th>
                                    <th>Password</th>
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