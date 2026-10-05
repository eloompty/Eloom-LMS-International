@extends('student::student.layouts.master')
@section('title', 'Student | My Grades')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>My Grades</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">My Grades</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="mb-4">
            <a href="{{ route('student.gradebook.appeals') }}" class="panel-action">
                <i class="fas fa-flag"></i> My Grade Appeals
            </a>
        </div>

        @if($enrolledCourses->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-graduation-cap"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No enrolled courses with grade data yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Your course gradebooks will appear here once grades have been recorded.</p>
            </div>
        </div>
        @else
        <div class="row">
            @foreach($enrolledCourses as $sic)
            @php
                $course = optional(optional($sic->intakeCourse)->course)->course_name ?? '—';
                $intake = optional(optional($sic->intakeCourse)->intake)->name ?? '—';
            @endphp
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="dashboard-panel h-100" style="display:flex; flex-direction:column;">
                    {{-- Card header accent --}}
                    <div style="height:4px; background:linear-gradient(90deg,#2563eb,#0891b2); border-radius:8px 8px 0 0;"></div>

                    <div style="padding:18px 18px 14px; flex:1;">
                        <div class="d-flex align-items-start" style="gap:12px;">
                            <div style="flex:0 0 38px; width:38px; height:38px; border-radius:8px; background:#eff6ff; display:flex; align-items:center; justify-content:center; margin-top:1px;">
                                <i class="fas fa-book" style="color:#2563eb; font-size:15px;"></i>
                            </div>
                            <div style="min-width:0;">
                                <p class="mb-1" style="font-size:14px; font-weight:800; color:#111827; line-height:1.3; word-break:break-word;">{{ $course }}</p>
                                <p class="mb-0" style="font-size:12px; font-weight:700; color:#6b7280;">
                                    <i class="fas fa-users mr-1"></i>{{ $intake }}
                                </p>
                                <p class="mb-0 mt-1" style="font-size:11px; color:#94a3b8; font-weight:700;">
                                    Enrollment #{{ $sic->id }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="padding:12px 16px; border-top:1px solid #f1f5f9; display:flex; gap:8px;">
                        <a href="{{ route('student.gradebook.show', $sic->id) }}"
                           class="btn btn-primary btn-sm flex-fill"
                           style="font-weight:800; font-size:12px; border-radius:6px; text-align:center;">
                            <i class="fas fa-chart-line mr-1"></i> View Gradebook
                        </a>
                        <a href="{{ route('student.gradebook.transcript', $sic->id) }}"
                           class="panel-action"
                           style="font-size:11px; padding:4px 10px; min-height:28px;">
                            <i class="fas fa-file-pdf"></i> Transcript
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>
@endsection
