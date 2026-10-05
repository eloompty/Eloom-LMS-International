@extends('student::student.layouts.master')
@section('title', 'Student | Assignment Question Submissions')

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
                <h1>Assignment Question Submissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntakeSubject->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntakeSubject->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.assignment.index', $studentIntakeSubject->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.submission.index', [$submission->assignment->id, $studentIntakeSubject->id]) }}">Submissions</a></li>
                    <li class="breadcrumb-item active">Questions</li>
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
                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-body">
                                @foreach($answers as $answer)
                                <div class="card mb-3">
                                    <div class="card-header">{{ $no++}}. {{ $answer->assignmentQuestion->question }}</div>

                                    <div class="card-body">
                                        <label for="answer">Answer</label>
                                        <textarea class="form-control" readonly>{{ $answer->answer }}</textarea>
                                    </div>
                                    @if ($answer->remarks != NULL)
                                    <div class="card-body">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" readonly>{{ $answer->remarks }}</textarea>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
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
