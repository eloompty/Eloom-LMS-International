@extends('user::layouts.master')
@section('title', 'Admin | Assignments')

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
                <h1>Assignments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Assignments</li>
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
                        <h3 class="card-title">List of assignments</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($assignments) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <th>Due Date</th>
                                    <th>Uploaded By</th>
                                    <th>Assigned Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->intakeSubject->subject->name }}</td>
                                    <td>@if ($value->trainer_id == NULL) Not Assigned @else {{ userName('Trainer', $value->trainer_id) }} @endif</td>
                                    <td>{{ strtoupper($value->type) }}</td>
                                    <td>
                                        @if ($value->type == 'file')
                                        <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'multiple files')
                                        <a href="{{ route('admin.assignment.files', $value->id) }}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'mcq')
                                        <a href="{{ route('admin.assignment.mcq', $value->id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                        @else
                                        <a href="{{ route('admin.assignment.question', $value->id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                        @endif
                                    </td>
                                    <!-- @if ($value->due_date == NULL) <td>-</td>
                                    @else <td data-sort='{{ convertDate($value->due_date) }}'>{{ dateFormat($value->due_date) }}</td>
                                    @endif -->
                                    @if ($value->intakeSubject->due_date == NULL)
                                    <td data-sort='{{ convertDate($value->due_date) }}'>{{ dateFormat($value->due_date) }}</td>
                                    @else
                                    <td data-sort='{{ convertDate($value->intakeSubject->due_date) }}'>{{ dateFormat($value->intakeSubject->due_date) }}</td>
                                    @endif
                                    <td>
                                        @if ($value->uploaded_by == 'Admin'){{ userName('Admin', $value->uploaded_user_id) }} (Admin)
                                        @elseif($value->uploaded_by == 'Trainer'){{ userName('Trainer', $value->uploaded_user_id) }} (Trainer)
                                        @endif
                                    </td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.assignment.submission.index', $value->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-clipboard-list"></i> Submissions</a>
                                        <a href="{{ route('admin.assignment.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <th>Due Date</th>
                                    <th>Uploaded By</th>
                                    <th>Assigned Date</th>
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
