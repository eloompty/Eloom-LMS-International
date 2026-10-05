@extends('user::layouts.master')
@section('title', 'Admin | Add Alumni')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Manual Alumni Entry</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.alumni.index') }}">Alumni</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <div class="card">
            <div class="card-header"><h3 class="card-title">Create Alumni Profile</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.alumni.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Student <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-control select2" required>
                            <option value="">— Select Student —</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ trim($student->first_name . ' ' . $student->family_name) }} (ID: {{ $student->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Student Intake Course <span class="text-danger">*</span></label>
                        <select name="student_intake_course_id" id="student_intake_course_id" class="form-control select2" required>
                            <option value="">— Select Student First —</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Graduation Date</label>
                        <input type="date" name="graduation_date" class="form-control" value="{{ old('graduation_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Alumni Profile</button>
                    <a href="{{ route('admin.alumni.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(function () {
    var studentCourses = @json($studentCourses);
    var oldStudentId = @json(old('student_id'));
    var oldStudentIntakeCourseId = @json(old('student_intake_course_id'));
    var $student = $('#student_id');
    var $studentIntakeCourse = $('#student_intake_course_id');

    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    function renderStudentIntakeCourses(studentId, selectedId) {
        var courses = studentCourses[studentId] || [];
        $studentIntakeCourse.empty();

        if (!studentId) {
            $studentIntakeCourse.append(new Option('— Select Student First —', ''));
            $studentIntakeCourse.prop('disabled', true).trigger('change.select2');
            return;
        }

        if (!courses.length) {
            $studentIntakeCourse.append(new Option('No enrolled intake courses found', ''));
            $studentIntakeCourse.prop('disabled', true).trigger('change.select2');
            return;
        }

        $studentIntakeCourse.append(new Option('— Select Intake Course —', ''));
        courses.forEach(function (course) {
            var option = new Option(course.text, course.id, false, String(course.id) === String(selectedId));
            $studentIntakeCourse.append(option);
        });
        $studentIntakeCourse.prop('disabled', false).trigger('change.select2');
    }

    $student.on('change', function () {
        renderStudentIntakeCourses($(this).val(), null);
    });

    renderStudentIntakeCourses(oldStudentId || $student.val(), oldStudentIntakeCourseId);
});
</script>
@endsection
