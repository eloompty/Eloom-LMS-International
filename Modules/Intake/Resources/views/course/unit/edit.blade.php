@extends('user::layouts.master')
@section('title', 'Admin | Edit Intake Unit')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Intake Unit</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeUnit->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.semester.index', $intakeUnit->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.subject.index', $intakeUnit->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.index', $intakeUnit->intake_subject_id) }}">Units</a></li>
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
                        <h3 class="card-title">Edit <small>{{ $intakeUnit->unit->name }}'s Unit</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editintakeUnit" action="{{ route('admin.intake.unit.update', $intakeUnit->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="unit_code">Code</label>
                                    <input type="text" class="form-control" id="unit_code" value="{{ $intakeUnit->unit->code }}" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="unit_name">Name</label>
                                    <input type="text" class="form-control" id="unit_name" value="{{ $intakeUnit->unit->name }}" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="trainer_id">Teacher</label>
                                    <select class="form-control" name="trainer_id" id="trainer" required>
                                        <option value="">-- Select Teacher --</option>
                                        @foreach($trainers as $trainer)
                                        <option @if($trainer_id==$trainer->id)selected @endif value="{{ $trainer->id }}">{{ userName('Trainer', $trainer->id) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="starting_date">Starting Date</label>
                                    <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $intakeUnit->starting_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ending_date">Ending Date</label>
                                    <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date" value="{{ $intakeUnit->ending_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="due_date">Due Date</label>
                                    <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $intakeUnit->due_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sequence">Sequence</label>
                                    <input type="number" name="sequence" class="form-control" id="sequence" placeholder="Enter Sequence" value="{{ $intakeUnit->sequence }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($intakeUnit->status == '1')selected @endif value="1">Active</option>
                                        <option @if($intakeUnit->status == '0')selected @endif value="0">Inactive</option>
                                        <option @if($intakeUnit->status == '3')selected @endif value="3">Locked</option>
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