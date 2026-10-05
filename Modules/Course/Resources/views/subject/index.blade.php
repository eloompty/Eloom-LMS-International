@extends('user::layouts.master')
@section('title', 'Admin | Course Subjects')

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
                <h1>{{ $semester->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($semester->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($semester->course->registered == 1) href="{{ route('admin.course.semester.index', $semester->course_id) }}" @else href="{{ route('admin.unregistered.semester.index', $semester->course_id) }}" @endif>Semesters</a></li>
                    <li class="breadcrumb-item active">Subjects</li>
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
                        <h3 class="card-title">List of {{ $semester->name }}'s Subjects</h3>
                        <div class="col-md-12 text-right"><a @if ($semester->course->registered == 1) href="{{ route('admin.course.subject.create', $semester->id) }}" @else href="{{ route('admin.unregistered.subject.create', $semester->id) }}" @endif class="btn btn-success">Add Subject</a></div>
                    </div>
                    <div class="card-body">
                        @if(count($subjects) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Credit Hours</th>
                                    <th>Teaching Hours</th>
                                    <th>Marks</th>
                                    <th>Type</th>
                                    @if (getSettingValue('teaching_system') == 'Unit')<th>Number of Units</th>@endif
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->code }}</td>
                                    <td>{{ $value->credits }}</td>
                                    <td>{{ $value->teaching_hours }}</td>
                                    <td>
                                        <b>Full Marks: </b>{{ $value->full_marks }} <br>
                                        <b>Theory: </b>{{ $value->theory }} <br>
                                        <b>Practical: </b>{{ $value->practical }} <br>
                                        <b>Internal: </b>{{ $value->internal }} <br>
                                    </td>
                                    <td>{{ $value->type }}</td>
                                    @if (getSettingValue('teaching_system') == 'Unit')
                                    <td><a @if ($semester->course->registered==1) href="{{ route('admin.course.unit.index', $value->id) }}" @else href="{{ route('admin.unregistered.unit.index', $value->id) }}" @endif class="btn btn-info btn-sm">{{ $value->units()->count() }}</a></td>
                                    @endif
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a @if ($semester->course->registered == 1) href="{{ route('admin.course.subject.edit', $value->id) }}" @else href="{{ route('admin.unregistered.subject.edit', $value->id) }}" @endif class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.course.subject.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                        <a @if ($semester->course->registered == 1) href="{{ route('admin.course.subject.resource.index', $value->id) }}" @else href="{{ route('admin.unregistered.subject.resource.index', $value->id) }}" @endif class="btn btn-warning btn-sm"><i class="fas fa-clipboard"></i> Resources</a>
                                        <a @if ($semester->course->registered == 1) href="{{ route('admin.course.subject.assignment.index', $value->id) }}" @else href="{{ route('admin.unregistered.subject.assignment.index', $value->id) }}" @endif class="btn btn-primary btn-sm"><i class="fas fa-chart-pie"></i> Assignments</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Credit Hours</th>
                                    <th>Teaching Hours</th>
                                    <th>Marks</th>
                                    <th>Type</th>
                                    @if (getSettingValue('teaching_system') == 'Unit')<th>Number of Units</th>@endif
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
