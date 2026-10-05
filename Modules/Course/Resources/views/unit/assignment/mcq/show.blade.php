@extends('user::layouts.master')
@section('title', 'Admin | Assignment Questions')

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
                <h1>Assignment Questions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.index', $unit_assignment->unit->course_id) }}" @else href="{{ route('admin.unregistered.unit.index', $unit_assignment->unit->course_id) }}" @endif>Semesters</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.index', $unit_assignment->unit->semester_id) }}" @else href="{{ route('admin.unregistered.unit.index', $unit_assignment->unit->semester_id) }}" @endif>Subjects</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.index', $unit_assignment->unit->subject_id) }}" @else href="{{ route('admin.unregistered.unit.index', $unit_assignment->unit->subject_id) }}" @endif>Units</a></li>
                    <li class="breadcrumb-item"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.assignment.index', $unit_assignment->unit_id) }}" @else href="{{ route('admin.unregistered.unit.assignment.index', $unit_assignment->unit_id) }}" @endif>Assignments</a></li>
                    <li class="breadcrumb-item active">MCQ</li>
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
                        <h3 class="card-title">List of {{ $unit_assignment->name }}'s Questions</h3>
                        <div class="col-md-12 text-right"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.assignment.mcq.create', $unit_assignment->id) }}" @else href="{{ route('admin.unregistered.unit.assignment.mcq.create', $unit_assignment->id) }}" @endif class="btn btn-success">Add Question</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($questions) > 0)
                        <div class="card-body">
                            @foreach($questions as $question)
                            <div class="card mb-3">
                                <div class="card-header mcq-question">
                                    <div class="row">
                                        <div class="col-sm-10">{{ $no++ }}. {{ $question->question }}</div>
                                        <div class="col-sm-2 text-right"><a @if ($unit_assignment->unit->course->registered == 1) href="{{ route('admin.course.unit.assignment.mcq.edit', $question->id) }}" @else href="{{ route('admin.unregistered.unit.assignment.mcq.edit', $question->id) }}" @endif class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit</a></div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    @foreach($question->unitAssignmentChoices as $option)
                                    <div class="form-check">
                                        <label class="form-check-label" for="option-{{ $option->id }}">
                                            @if ($option->is_correct == 1)<span class="option right"><i class="fas fa-check-circle">@else<span class="option wrong"><i class="fas fa-circle-notch">@endif</i> {{ $option->choice }} </span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
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
