@extends('user::layouts.master')
@section('title', 'Admin | Assignment Resubmission Request')

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
                <h1>Assignment Resubmission Request</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.assignment.index') }}">Assignment</a></li>
                    <li class="breadcrumb-item active">Resubmission Request</li>
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
                        <h3 class="card-title">List of assignment resubmission requests</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($resubmissions) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Name</th>
                                    <!-- <th>Assignment</th> -->
                                    <th>Requested Date</th>
                                    <th>Approved Date</th>
                                    <th>Approved By</th>
                                    <th>New Due Date</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resubmissions as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ userName('Student', $value->student_id) }}</td>
                                    <td>{{ $value->assignment->name }}</td>
                                    <!-- <td>
                                        @if ($value->assignment->type == 'file')
                                        <a href="{{ asset($value->assignment->path) }}" target="_blank"><img src="{{ asset(filePath($value->assignment->path)) }}" alt="" width="48" /></a>
                                        @elseif ($value->assignment->type == 'mcq')
                                        <a href="{{ route('admin.assignment.mcq', $value->assignment_id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                        @else
                                        <a href="{{ route('admin.assignment.question', $value->assignment_id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                        @endif
                                    </td> -->
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    @if ($value->approved_date == NULL)
                                    <td>-</td>
                                    @else
                                    <td data-sort='{{ convertDate($value->approved_date) }}'>{{ dateFormat($value->approved_date) }}</td>
                                    @endif
                                    <td>
                                        @if ($value->user_type == 'Admin'){{ userName('Admin', $value->user_id) }} (Admin)
                                        @elseif($value->user_type == 'Trainer'){{ userName('Trainer', $value->user_id) }} (Trainer)
                                        @else -
                                        @endif
                                    </td>
                                    @if ($value->due_date == NULL)
                                    <td>-</td>
                                    @else
                                    <td data-sort='{{ convertDate($value->due_date) }}'>{{ dateFormat($value->due_date) }}</td>
                                    @endif
                                    <td>{{ $value->remarks }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Approved</span>
                                        @elseif ($value->status == 0) <span class="status locked">Requested</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Rejected</span>
                                        @else <span class="status locked">Resubmitted</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 0)
                                        <a href="{{ route('admin.resubmission.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Name</th>
                                    <!-- <th>Assignment</th> -->
                                    <th>Requested Date</th>
                                    <th>Approved Date</th>
                                    <th>Approved By</th>
                                    <th>New Due Date</th>
                                    <th>Remarks</th>
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
