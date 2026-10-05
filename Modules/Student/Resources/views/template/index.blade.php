@extends('user::layouts.master')
@section('title', 'Admin | Student Templates')

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
                <h1>Student Templates</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">Templates</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $student->id) }}'s Templates</h3>
                        <div class="col-md-12 text-right">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Add Template
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="{{ route('admin.student.template.create',['progression', $student->id]) }}">Progression Letter</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.student.template.create',['enrollment', $student->id]) }}">Letter of Enrollment</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.student.template.create',['completion', $student->id]) }}">Completion Letter</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.student.template.create',['term_break', $student->id]) }}">Term Break Letter</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.student.template.create',['vp_request', $student->id]) }}">VP Request Letter</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.student.template.create',['leave', $student->id]) }}">Leave Approval Letter</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.student.template.create',['vp', $student->id]) }}">VP Letter</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.student.template.create',['statement', $student->id]) }}">Statement of Receipt</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($templates) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $index => $value)
                                <tr>
                                    <td>{{ $value->template_name }}</a></td>
                                    <td>{{ $value->intakeCourse->intake->name }}</td>
                                    <td>{{ $value->intakeCourse->course->course_name }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.student.template.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <a href="{{ route('admin.student.template.show', $value->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Show</a>
                                        <a href="{{ route('admin.student.template.print', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i> Print</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.student.template.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Intake</th>
                                    <th>Course</th>
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