@extends('user::layouts.master')
@section('title', 'Admin | Faculty/Teachers')

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
                <h1>Faculty Teachers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Teachers</li>
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
                        <h3 class="card-title">List of Faculty/Teachers</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.trainer.create') }}" class="btn btn-success">Add Teacher</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($trainers) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trainers as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ userName('Trainer', $value->id)}}</td>
                                    <td><a href="{{ asset($value->image) }}" target="_blank"><img src="{{ asset($value->image) }}" alt="" width="48" /></a></td>
                                    <td>{{ $value->email }}</td>
                                    <td><b>Phone: </b>{{ $value->phone }} <br> <b>Mobile: </b>{{ $value->phone }}</td>
                                    <td>
                                        <b>Address: </b>{{ fullAddress('trainer', $value->id) }}
                                        <br><b>Country: </b>{{ addressCountryName(optional($value->address)->country_id) }}
                                    </td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.trainer.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.trainer.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                More
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li style="margin:5pt"><a href="{{ route('admin.trainer.dashboard', $value->id) }}" target="_blank" style="width: 100%;" class="btn btn-info btn-sm" title="{{ userName('Trainer', $value->id) }}'s Dashboard"><i class="fas fa-eye"></i> Dashboard</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.trainer.qualification.index', $value->id) }}" style="width: 100%;" class="btn btn-secondary btn-sm" title="{{ userName('Trainer', $value->id) }}'s Qualifications"><i class="fas fa-school"></i> Qualifications</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.trainer.profession.index', $value->id) }}" style="width: 100%;" class="btn btn-light btn-sm" title="{{ userName('Trainer', $value->id) }}'s Professional Developments"><i class="fas fa-sticky-note"></i> Professional Developments</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.trainer.workplacement.index', $value->id) }}" style="width: 100%;" class="btn btn-primary btn-sm" title="{{ userName('Trainer', $value->id) }}'s Placements"><i class="fas fa-briefcase"></i> Placements</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.trainer.intake.index', $value->id) }}" style="width: 100%;" class="btn btn-warning btn-sm" title="{{ userName('Trainer', $value->id) }}'s Intakes"><i class="fas fa-graduation-cap"></i> Intakes</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.trainer.log.index', $value->id) }}" style="width: 100%;" class="btn btn-dark btn-sm" title="{{ userName('Trainer', $value->id) }}'s Logs"><i class="fas fa-chart-line"></i> Logs</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.trainer.device.index', $value->id) }}" style="width: 100%;" class="btn btn-success btn-sm" title="{{ userName('Trainer', $value->id) }}'s Device"><i class="fas fa-mobile"></i> Devices</a></li>
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
                                    <th>Image</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
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
