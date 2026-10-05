@extends('user::layouts.master')
@section('title', 'Admin | Course Units')

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
                <h1>{{ $subject->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.semester.index', $subject->course_id) }}" @else href="{{ route('admin.unregistered.semester.index', $subject->course_id) }}" @endif>Semesters</a></li>
                    <li class="breadcrumb-item"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.subject.index', $subject->semester_id) }}" @else href="{{ route('admin.unregistered.subject.index', $subject->semester_id) }}" @endif>Subjects</a></li>
                    <li class="breadcrumb-item active">Units</li>
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
                        <h3 class="card-title">List of {{ $subject->name }}'s units</h3>
                        <div class="col-md-12 text-right"><a @if ($subject->course->registered == 1) href="{{ route('admin.course.unit.create', $subject->id) }}" @else href="{{ route('admin.unregistered.unit.create', $subject->id) }}" @endif class="btn btn-success">Add Unit</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($units) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Semester</th>
                                    <th>Subject</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Teaching Hours</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $index => $value)
                                <tr>
                                    <td>{{ $no++ }} <input type="hidden" name="{{$value->id}}[id]" value="{{ $value->id }}"></td>
                                    <td>{{ $value->semester->name }}</td>
                                    <td>{{ $value->subject->name }}</td>
                                    <td>{{ $value->code }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->hours }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (getSettingValue('unit_wise_fee') == 'yes')
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.unit.fee.index', $value->id) }}" @else href="{{ route('admin.unregistered.unit.fee.index', $value->id) }}" @endif class="btn btn-success btn-sm"><i class="fas fa-money-bill"></i> Fees</a>
                                        @endif
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.unit.resource.index', $value->id) }}" @else href="{{ route('admin.unregistered.unit.resource.index', $value->id) }}" @endif class="btn btn-warning btn-sm"><i class="fas fa-clipboard"></i> Resources</a>
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.unit.assignment.index', $value->id) }}" @else href="{{ route('admin.unregistered.unit.assignment.index', $value->id) }}" @endif class="btn btn-primary btn-sm"><i class="fas fa-chart-pie"></i> Assignment</a>
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.unit.edit', $value->id) }}" @else href="{{ route('admin.unregistered.unit.edit', $value->id) }}" @endif class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <a @if ($subject->course->registered == 1) href="{{ route('admin.course.unit.missing.index', $value->id) }}" @else href="{{ route('admin.unregistered.unit.missing.index', $value->id) }}" @endif class="btn btn-success btn-sm"><i class="fas fa-plus-circle"></i> Add Missing</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.course.unit.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Semester</th>
                                    <th>Subject</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Teaching Hours</th>
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