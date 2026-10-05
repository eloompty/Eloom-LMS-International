@extends('user::layouts.master')
@section('title', 'Admin | Agents')

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
                <h1>Agents</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Agents</li>
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
                        <h3 class="card-title">List of Agents</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.agent.create') }}" class="btn btn-success">Add Agent</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($agents) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Rate</th>
                                    <th>Applied Students</th>
                                    <th>Enrolled Students</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agents as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        <b>Name:</b>{{ $value->company_name }} <br>
                                        <b>Registration:</b>{{ $value->company_registration }}
                                    </td>
                                    <td>{{ $value->email }}</td>
                                    <td>
                                        <b>Mobile:</b>{{ $value->mobile }} <br>
                                        <b>Office:</b>{{ $value->office_phone }}
                                    </td>
                                    <td>
                                        <b>Country:</b>@if ($value->country_id > 0){{ $value->country->name }}@endif <br>
                                        <b>City:</b>{{ $value->city }} <br>
                                        <b>Address:</b>{{ $value->address }}
                                    </td>
                                    <td>{{ $value->rate }}</td>
                                    <td><a href="{{ route('admin.agent.student.index', $value->id) }}" class="btn btn-info btn-sm">{{ $value->applied_count }}</a></td>
                                    <td><a href="{{ route('admin.agent.student.enrolled.index', $value->id) }}" class="btn btn-info btn-sm">{{ $value->enrolled_count }}</a></td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.agent.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.agent.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                        <a href="{{ route('admin.agent.commission.index', $value->id) }}" class="btn btn-success btn-sm"><i class="fas fa-money-bill"></i> Commission</a>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                More
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li style="margin:5pt"><a href="{{ route('admin.agent.branch.index', $value->id) }}" style="width: 100%;" class="btn btn-primary btn-sm"><i class="fas fa-people-arrows"></i> Branches</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.agent.log.index', $value->id) }}" style="width: 100%;" class="btn btn-dark btn-sm"><i class="fas fa-chart-line"></i> Logs</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.agent.device.index', $value->id) }}" style="width: 100%;" class="btn btn-success btn-sm"><i class="fas fa-mobile"></i> Devices</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Company Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Rate</th>
                                    <th>Applied Students</th>
                                    <th>Enrolled Students</th>
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