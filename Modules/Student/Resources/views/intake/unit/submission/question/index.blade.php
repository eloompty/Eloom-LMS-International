@extends('user::layouts.master')
@section('title', 'Admin | Student Intake Unit Question Submissions')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student Intake Unit Question Submissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeUnit->studentIntakeCourse->student_id) }}">Intake</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.unit.index', $studentIntakeUnit->student_intake_course_id) }}">Unit</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.unit.submission.index', $studentIntakeUnit->id) }}">Submission</a></li>
                    <li class="breadcrumb-item active">MCQ Submission</li>
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
                    <form action="#" method="POST">
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
                                            <textarea class="form-control" name="answers[{{ $answer->id }}]" readonly>{{ $answer->remarks }}</textarea>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
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