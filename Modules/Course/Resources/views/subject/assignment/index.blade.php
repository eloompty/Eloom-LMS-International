@extends('user::layouts.master')
@section('title', 'Admin | Subject Assignments')

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
                <h1>{{ $subject->name }}'s Assignments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.semester.index', $subject->course_id) }}" @else href="{{ route('admin.unregistered.semester.index', $subject->course_id) }}" @endif>Semesters</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.subject.index', $subject->semester_id) }}" @else href="{{ route('admin.unregistered.subject.index', $subject->semester_id) }}" @endif>Subjects</a></li>
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
                        <h3 class="card-title">List of {{ $subject->name }}'s assignments</h3>
                        <div class="col-md-12 text-right"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.subject.assignment.create', $subject->id) }}" @else href="{{ route('admin.unregistered.subject.assignment.create', $subject->id) }}" @endif class="btn btn-success">Add Subject Assignment</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($assignments) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <!-- <th>Due Date</th> -->
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
                                    <td>{{ strtoupper($value->type) }}</td>
                                    <td>
                                        @if ($value->type == 'file')
                                        <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'multiple files')
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.subject.assignment.files.edit', $value->id) }}" @else href="{{ route('admin.unregistered.subject.assignment.files.edit', $value->id) }}" @endif target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'mcq')
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.subject.assignment.mcq.show', $value->id) }}" @else href="{{ route('admin.unregistered.subject.assignment.mcq.show', $value->id) }}" @endif target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                        @else
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.subject.assignment.question.show', $value->id) }}" @else href="{{ route('admin.unregistered.subject.assignment.question.show', $value->id) }}" @endif target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                        @endif
                                    </td>
                                    <!-- <td data-sort='{{ convertDate($value->due_date) }}'>{{ dateFormat($value->due_date) }}</td> -->
                                    <td>{{ userName('Admin', $value->user_id) }}</td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.subject.assignment.edit', $value->id) }}" @else href="{{ route('admin.unregistered.subject.assignment.edit', $value->id) }}" @endif class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.course.subject.assignment.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Assignment</th>
                                    <!-- <th>Due Date</th> -->
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
