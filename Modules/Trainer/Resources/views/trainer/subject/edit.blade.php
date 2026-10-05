@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Subjects')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $subject->intakeCourse->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $subject->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $subject->intake_semester_id) }}">Subjects</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $subject->intakeSubject->subject->name }}</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editsubject" action="{{ route('trainer.subject.update', $subject->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="starting_date">Starting Date</label>
                                    <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $subject->intakeSubject->starting_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ending_date">Ending Date</label>
                                    <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date" value="{{ $subject->intakeSubject->ending_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="due_date">Due Date</label>
                                    <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $subject->intakeSubject->due_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sequence">Sequence</label>
                                    <input type="number" name="sequence" class="form-control" id="sequence" placeholder="Enter Sequence" value="{{ $subject->intakeSubject->sequence }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($subject->intakeSubject->status == '1')selected @endif value="1">Active</option>
                                        <option @if($subject->intakeSubject->status == '0')selected @endif value="0">Inactive</option>
                                        <option @if($subject->intakeSubject->status == '3')selected @endif value="3">Locked</option>
                                    </select>
                                </div>
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