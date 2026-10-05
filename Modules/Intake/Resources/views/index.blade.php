@extends('user::layouts.master')
@section('title', 'Admin | Intakes')

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
                <h1>Intakes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
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
                        <h3 class="card-title">List of Intakes</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.intake.create') }}" class="btn btn-success">Add Intake</a></div>
                        <div class="col-md-12">
                            @if($status == NULL)<a  href="{{ url('admin/intake?status=deleted') }}" class="btn btn-danger">Show Deleted Intakes</a>
                            @elseif ($status == 'deleted')
                            <a href="{{ route('admin.intake.index') }}" class="btn btn-success">Show Active Intakes</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($intakes) > 0)
                        <table id="example3" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Reference Name</th>
                                    <th>Orientation Date</th>
                                    <th>Starting Date</th>
                                    <th>Courses</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($intakes as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->reference_name }}</td>
                                    <td data-sort='{{ convertDate($value->orientation_date) }}'>{{ dateFormat($value->orientation_date) }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>{{ dateFormat($value->starting_date) }}</td>
                                    <td><a href="{{ route('admin.intake.course.index', $value->id) }}" class="btn btn-info btn-sm">{{ getCourseCountByIntake($value->id) }}</a></td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.intake.course.index', $value->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-book"></i> Courses</a>
                                        <a href="{{ route('admin.intake.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.intake.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                        <a href="{{ route('admin.intake.copy', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-copy"></i> Copy</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Reference Name</th>
                                    <th>Orientation Date</th>
                                    <th>Starting Date</th>
                                    <th>Courses</th>
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