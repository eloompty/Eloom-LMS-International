@extends('user::layouts.master')
@section('title', 'Admin | Teacher Work Placements')

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
                <h1>Teacher Work Placements</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Teachers</a></li>
                    <li class="breadcrumb-item active">Work Placements</li>
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
                        <h3 class="card-title">List of {{ $trainer->salutation }} {{ $trainer->first_name }} {{ $trainer->family_name }}'s Work Placements</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.trainer.workplacement.create', $trainer->id) }}" class="btn btn-success">Add Work Placement</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($works) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Placement Company Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact Person Mobile</th>
                                    <th>Position</th>
                                    <th>Placement Hours</th>
                                    <th>Placement Description</th>
                                    <th>Site Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($works as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->placement_company_name }}</td>
                                    <td>{{ $value->contact_person }}</td>
                                    <td>{{ $value->contact_person_mobile }}</td>
                                    <td>{{ $value->position }}</td>
                                    <td>{{ $value->placement_hours }}</td>
                                    <td>{{ $value->placement_description }}</td>
                                    <td>{{ $value->site_name }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>{{ dateFormat($value->starting_date) }}</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>{{ dateFormat($value->ending_date) }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.trainer.workplacement.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.trainer.workplacement.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Placement Company Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact Person Mobile</th>
                                    <th>Position</th>
                                    <th>Placement Hours</th>
                                    <th>Placement Description</th>
                                    <th>Site Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
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