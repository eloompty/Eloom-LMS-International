@extends('user::layouts.master')
@section('title', 'Admin | Courses')

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
                <h1>Courses</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Courses</li>
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
                        <h3 class="card-title">List of courses</h3>
                        <div class="col-md-12 text-right"><a @if ($registered==1) href="{{ route('admin.course.create') }}" @else href="{{ route('admin.unregistered.create') }}" @endif class="btn btn-success">Add Course</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($courses) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if ($registered == 1)<th>Course Code</th>@endif
                                    <th>Course Name</th>
                                    <th>Reference Name</th>
                                    <th>Duration</th>
                                    <th>Semesters</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    @if ($registered == 1)<td>{{ $value->course_code }}</td>@endif
                                    <td>{{ $value->course_name }}</td>
                                    <td>{{ $value->reference_name }}</td>
                                    <td>
                                        <b>Years: </b>{{ $value->duration }} <br>
                                        <b>Semesters: </b>{{ $value->study_period }}<br>
                                    </td>
                                    <td><a @if ($registered==1) href="{{ route('admin.course.semester.index', $value->id) }}" @else href="{{ route('admin.unregistered.semester.index', $value->id) }}" @endif class="btn btn-info btn-sm">{{ $value->active_semesters_count }}</a></td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a @if ($registered==1) href="{{ route('admin.course.edit', $value->id) }}" @else href="{{ route('admin.unregistered.edit', $value->id) }}" @endif class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.course.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                        <a @if ($registered==1) href="{{ route('admin.course.fee.index', $value->id) }}" @else href="{{ route('admin.unregistered.fee.index', $value->id) }}" @endif class="btn btn-warning btn-sm"><i class="fas fa-money-bill"></i> Fees</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    @if ($registered == 1)<th>Course Code</th>@endif
                                    <th>Course Name</th>
                                    <th>Reference Name</th>
                                    <th>Duration</th>
                                    <th>Semesters</th>
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
