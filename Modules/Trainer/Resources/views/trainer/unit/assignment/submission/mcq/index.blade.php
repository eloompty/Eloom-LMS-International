@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | MCQ')

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
                <h1>MCQ</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $trainerIntake->intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.submission.index', [$submission->assignment_id, $trainerIntake->id]) }}">Assignment Submissions</a></li>
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
                        <h3 class="card-title">{{ $submission->assignment->name }}'s Answer Submissions</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('trainer.submission.mcq.remarks', [$submission->id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-body">
                                        @foreach($answers as $answer)
                                        <div class="card mb-3">
                                            <div class="card-header mcq-question">{{ $no++}}. {{ $answer->assignmentQuestion->question }}</div>

                                            <div class="card-body">
                                                @foreach($answer->assignmentQuestion->choice as $option)
                                                <div class="form-check">
                                                    <label class="form-check-label" for="option-{{ $option->id }}">
                                                        @if ($option->is_correct == 1)<span class="option right"><i class="fas fa-check-circle">@else<span class="option wrong"><i class="fas fa-circle-notch">@endif</i> {{ $option->choice }} </span>
                                                        @if ($option->id == $answer->answer) <span class="option answer"> Answer @endif</span>
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="card-body">
                                                <label for="remarks">Remarks</label>
                                                <textarea class="form-control" name="answers[{{ $answer->id }}]">{{ $answer->remarks }}</textarea>
                                            </div>
                                        </div>
                                        @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
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
@section('scripts')
<script>
    $('textarea').each(function() {
        this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
@endsection