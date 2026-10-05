@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Resources')

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
                <h1>Resources</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item active">Resources</li>
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
                        <h3 class="card-title">List of {{ $subject->name }} Resources</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('trainer.subject.resource.create', [$subject->id, $trainerIntake->id]) }}" class="btn btn-success">Add Resource</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($resources) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Resource Type</th>
                                    <th>File</th>
                                    <th>User Type</th>
                                    <th>Category</th>
                                    <th>Course</th>
                                    <th>Semester</th>
                                    <th>Subject</th>
                                    <th>Uploaded By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resources as $index => $value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->resource_type }}</td>
                                    <td><a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a></td>
                                    <td>{{ $value->user_type }}</td>
                                    <td>{{ $value->category->name }}</td>
                                    <td>{{ $value->course->course_name }}</td>
                                    <td>{{ $value->semester->name }}</td>
                                    <td>{{ $value->subject->name }}</td>
                                    <td>
                                        @if ($value->uploaded_by == 'Admin') Admin
                                        @elseif($value->uploaded_by == 'Trainer'){{ userName('Trainer', $value->uploaded_user_id) }} (Trainer)
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ asset($value->path) }}" class="btn btn-info btn-sm" target="_blank">View</a>
                                        <a href="{{ route('trainer.subject.resource.edit', [$value->id, $trainerIntake->id]) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Resource Type</th>
                                    <th>File</th>
                                    <th>User Type</th>
                                    <th>Category</th>
                                    <th>Course</th>
                                    <th>Unit</th>
                                    <th>Uploaded By</th>
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
