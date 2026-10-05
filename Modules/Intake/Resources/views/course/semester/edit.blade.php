@extends('user::layouts.master')
@section('title', 'Admin | Edit Intake Semester')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $intakeSemester->intakeCourse->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeSemester->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.semester.index', $intakeSemester->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit <small>{{ $intakeSemester->semester->name }}'s Unit</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editintakeUnit" action="{{ route('admin.intake.semester.update', $intakeSemester->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="starting_date">Starting Date</label>
                                <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $intakeSemester->starting_date }}">
                            </div>
                            <div class="form-group">
                                <label for="ending_date">Ending Date</label>
                                <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date" value="{{ $intakeSemester->ending_date }}">
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $intakeSemester->due_date }}">
                            </div>
                            <div class="form-group">
                                <label for="sequence">Sequence</label>
                                <input type="number" name="sequence" class="form-control" id="sequence" placeholder="Enter Sequence" value="{{ $intakeSemester->sequence }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($intakeSemester->status == '1')selected @endif value="1">Active</option>
                                    <option @if($intakeSemester->status == '0')selected @endif value="0">Inactive</option>
                                    <option @if($intakeSemester->status == '3')selected @endif value="3">Locked</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection