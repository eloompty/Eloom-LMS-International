@extends('student::student.layouts.master')
@section('title', 'Student | MCQ Assignment')

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
                <h1>MCQ Assignment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.semester.index', $studentIntakeSubject->student_intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.index', $studentIntakeSubject->student_intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.subject.assignment.index', $studentIntakeSubject->intake_subject_id) }}">Assignments</a></li>
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
                        <h3 class="card-title">List of {{ $assignment->name }}'s Questions</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="submitMCQquestion" method="POST" action="{{ route('student.subject.assignment.mcq.submit.index', [$assignment->id, $studentIntakeSubject->id]) }}">
                            @csrf
                            <input type="hidden" name="assignment_resubmission_id" value="{{ $resubmission_id }}">
                            <div class="card-body">
                                @foreach($questions as $question)
                                <div class="card @if(!$loop->last)mb-3 @endif">
                                    <div class="card-header">{{ $no++ }}. {{ $question->question }}</div>

                                    <div class="card-body">
                                        <input type="hidden" name="questions[{{ $question->id }}]" value="">
                                        @foreach($question->choice as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="questions[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" required>
                                            <label class="form-check-label" for="option-{{ $option->id }}">
                                                {{ $option->choice }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
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
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#submitMCQquestion').validate({
            rules: {
                "questions[{{ $question->id }}]": {
                    required: true,
                },
            },
            messages: {
                "questions[{{ $question->id }}]": "Please enter answer",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection
