@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Student Gradebooks')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Student Gradebooks</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Gradebooks</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @if($intakeCourses->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-graduation-cap"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No students found.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Students in your assigned intakes will appear here.</p>
            </div>
        </div>
        @else
        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-graduation-cap"></i>
                    Students
                </h3>
                <div class="card-tools">
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        {{ $intakeCourses->count() }} {{ Str::plural('student', $intakeCourses->count()) }}
                    </span>
                </div>
            </div>

            @foreach($intakeCourses as $studentId => $courses)
            @php $student = $courses->first()->student; @endphp
            <div class="d-flex align-items-center flex-wrap {{ !$loop->last ? 'gb-student-row' : '' }}"
                 style="padding:14px 20px; gap:10px;">

                <div class="d-flex align-items-center" style="gap:12px; min-width:180px;">
                    <div style="width:36px; height:36px; border-radius:50%; background:#eff6ff; border:1.5px solid #bfdbfe; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-user-graduate" style="color:#2563eb; font-size:14px;"></i>
                    </div>
                    <div>
                        <p class="mb-0" style="font-size:13px; font-weight:800; color:#111827; line-height:1.2;">
                            {{ optional($student)->first_name }} {{ optional($student)->last_name }}
                        </p>
                        <p class="mb-0" style="font-size:11px; font-weight:700; color:#6b7280;">
                            ID #{{ $studentId }}
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-wrap" style="gap:6px; flex:1;">
                    @foreach($courses as $ic)
                    <a href="{{ route('trainer.gradebook.show', [$studentId, $ic->id]) }}"
                       class="panel-action"
                       style="font-size:12px; padding:5px 12px; min-height:30px;">
                        <i class="fas fa-book mr-1" style="font-size:10px;"></i>
                        {{ optional(optional($ic->intakeCourse)->course)->course_name ?? 'Course' }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>
@endsection

@section('header-script')
<style>
.gb-student-row { border-bottom: 1px solid #f1f5f9; }
</style>
@endsection
