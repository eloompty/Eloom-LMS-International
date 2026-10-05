@extends('user::layouts.master')
@section('title', 'Admin | Issue Certificate')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Issue Certificate</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.certificate.issued.index') }}">Issued Certificates</a></li>
                    <li class="breadcrumb-item active">Issue</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
                     style="border-radius:8px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; font-size:13px; font-weight:700;">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
                    <button type="button" class="close" data-dismiss="alert" style="color:#991b1b;"><span>&times;</span></button>
                </div>
                @endif

                <div class="dashboard-panel">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus-circle"></i>
                            Issue New Certificate
                        </h3>
                    </div>
                    <div class="card-body" style="padding:22px;">
                        <form method="POST" action="{{ route('admin.certificate.issued.store') }}">
                            @csrf

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Student <span style="color:#dc2626;">*</span>
                                </label>
                                <select name="student_id" id="student_id" class="form-control select2" required
                                        style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    <option value="">— Select Student —</option>
                                    @foreach($students as $s)
                                    <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                                        {{ trim($s->first_name . ' ' . $s->family_name) }} (ID: {{ $s->id }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Certificate Template <span style="color:#dc2626;">*</span>
                                </label>
                                <select name="certificate_template_id" class="form-control" required
                                        style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    <option value="">— Select Template —</option>
                                    @foreach($templates as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }} ({{ ucfirst($t->type) }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Intake Course
                                    <span style="font-weight:700; text-transform:none; color:#6b7280;">— optional, for course completion certificates</span>
                                </label>
                                <select name="student_intake_course_id" id="student_intake_course_id" class="form-control select2"
                                        style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    <option value="">— Select Student First —</option>
                                </select>
                            </div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap mt-4" style="gap:8px;">
                                <a href="{{ route('admin.certificate.issued.index') }}" class="panel-action">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary"
                                        style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 22px;">
                                    <i class="fas fa-certificate mr-1"></i> Issue Certificate
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

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

    $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

    function renderStudentIntakeCourses(studentId, selectedId) {
        var courses = studentCourses[studentId] || [];
        $studentIntakeCourse.empty().append(new Option('— No Intake Course —', ''));
        if (!studentId) { $studentIntakeCourse.prop('disabled', true).trigger('change.select2'); return; }
        courses.forEach(function (course) {
            $studentIntakeCourse.append(new Option(course.text, course.id, false, String(course.id) === String(selectedId)));
        });
        $studentIntakeCourse.prop('disabled', !courses.length).trigger('change.select2');
    }

    $student.on('change', function () { renderStudentIntakeCourses($(this).val(), null); });
    renderStudentIntakeCourses(oldStudentId || $student.val(), oldStudentIntakeCourseId);
});
</script>
@endsection
