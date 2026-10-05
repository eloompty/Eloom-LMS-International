@extends('user::layouts.master')
@section('title', 'Admin | Assignment Question Submissions')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Assignment Question Submissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $submission->assignment->intakeUnit->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.semester.index', $submission->assignment->intakeUnit->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.subject.index', $submission->assignment->intakeUnit->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.index', $submission->assignment->intakeUnit->intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.assignment.index', $submission->assignment->intake_unit_id) }}">Assignments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.assignment.submission.index', $submission->assignment_id) }}">Submissions</a></li>
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
                    <!-- form start -->
                    <form action="{{ route('admin.intake.unit.assignment.submission.question.remarks', $submission->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-body">
                                    @foreach($answers as $answer)
                                    <div class="card mb-3">
                                        <div class="card-header mcq-question">{{ $no++}}. {{ $answer->assignmentQuestion->question }}</div>

                                        <div class="card-body">
                                            <label for="answer">Answer</label>
                                            <textarea class="form-control" readonly>{{ $answer->answer }}</textarea>
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