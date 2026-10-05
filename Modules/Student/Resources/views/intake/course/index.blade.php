@extends('user::layouts.master')
@section('title', 'Admin | Student Intake')

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
                <h1>Student Intake</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($student->is_enrolled == 0) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif>Students</a></li>
                    <li class="breadcrumb-item active">Intake</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $student->id) }}'s Intake</h3>
                        <div class="col-md-12 text-right"><a @if ($student->is_enrolled == 1) href="{{ route('admin.student.intake.course.create', $student->id) }}" @else href="{{ route('admin.student.intake.course.offer.create', $student->id) }}" @endif class="btn btn-success">Add Student Intake</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($intakes) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Duration</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Is Enrolled</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($intakes as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeCourse->intake->name }}</td>
                                    <td>{{ $value->intakeCourse->course->course_name }}</td>
                                    <td>{{ $value->duration }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>{{ dateFormat($value->starting_date) }}</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>{{ dateFormat($value->ending_date) }}</td>
                                    <td>@if ($value->is_enrolled == 1) Enrolled @else Offered @endif</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.student.intake.semester.index', $value->id) }}" class="btn btn-warning btn-sm" title="Units"><i class="fas fa-book"></i> Semesters</a>
                                        <a @if ($student->is_enrolled == 1) href="{{ route('admin.student.intake.course.edit', $value->id) }}" @else @endif href="{{ route('admin.student.intake.course.offer.edit', $value->id) }}" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.student.intake.course.delete', $value->id) }}" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                        <!-- <a href="{{ route('admin.student.intake.competence.index', $value->id) }}" class="btn btn-primary btn-sm" title="Results"><i class="fas fa-check"></i> Results</a> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Duration</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Is Enrolled</th>
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
