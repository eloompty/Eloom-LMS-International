@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Student Intake Marking')

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

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $intakeUnitMark->intakeUnit->unit->name }} {{ $intakeUnitMark->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $intakeUnitMark->intakeUnit->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $intakeUnitMark->intakeUnit->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $intakeUnitMark->intakeUnit->intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.mark.index', $intakeUnitMark->intake_unit_id) }}">Markings</a></li>
                    <li class="breadcrumb-item active">{{ $intakeUnitMark->name }}</li>
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
                        <h3 class="card-title">Add <small>{{ $intakeUnitMark->intakeUnit->unit->name }}'s {{ $intakeUnitMark->name }} Marking</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editintakeMarking" action="{{ route('trainer.unit.mark.student.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @foreach($studentIntakeUnitMarks as $mark)
                            <div class="row" id="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name" value="{{ userName('Student', $mark->studentIntakeUnit->studentIntakeCourse->student_id) }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="full_marks">Full Marks</label>
                                    <input type="text" name="full_marks[]" class="form-control" id="full_marks" placeholder="Enter Full Marks" value="{{ $mark->full_marks }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pass_marks">Pass Marks</label>
                                    <input type="text" name="pass_marks[]" class="form-control" id="pass_marks" placeholder="Enter Pass Marks" value="{{ $mark->pass_marks }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="obtain_marks">Obtained Marks</label>
                                    <input type="text" name="obtain_marks[{{$mark->id}}]" class="form-control" id="obtain_marks" placeholder="Enter Obtained Marks" value="{{ $mark->obtain_marks }}">
                                </div>
                            </div>
                            @endforeach
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