@extends('student::student.layouts.master')
@section('title', 'Student | Surveys')

@section('header-script')
<style>
.survey-row-border { border-bottom: 1px solid #f1f5f9; }
</style>
@endsection

@section('content')
{{-- Page Header --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Surveys</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Surveys</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
            style="border-radius:8px; border:1px solid #a7f3d0; background:#f0fdf4; color:#065f46; font-size:13px; font-weight:700;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" style="color:#065f46;"><span>&times;</span></button>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert"
            style="border-radius:8px; border:1px solid #bae6fd; background:#f0f9ff; color:#0c4a6e; font-size:13px; font-weight:700;">
            <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
            <button type="button" class="close" data-dismiss="alert" style="color:#0c4a6e;"><span>&times;</span></button>
        </div>
        @endif

        @if($surveys->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-poll"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No open surveys at this time.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Check back later — surveys assigned to you will appear here.</p>
            </div>
        </div>
        @else
        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-poll"></i>
                    Available Surveys
                </h3>
                <div class="card-tools">
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        {{ $surveys->count() }} open
                    </span>
                </div>
            </div>

            @foreach($surveys as $survey)
            @php $completed = in_array($survey->id, $answeredIds); @endphp
            <div class="d-flex align-items-center justify-content-between flex-wrap survey-row {{ $loop->last ? '' : 'survey-row-border' }}"
                 style="padding:16px 20px; gap:12px;">

                {{-- Icon + Info --}}
                <div class="d-flex align-items-start" style="gap:14px; min-width:0; flex:1;">
                    @if($completed)
                    <div style="flex:0 0 40px; width:40px; height:40px; border-radius:8px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; margin-top:2px;">
                        <i class="fas fa-check-circle" style="font-size:16px; color:#059669;"></i>
                    </div>
                    @else
                    <div style="flex:0 0 40px; width:40px; height:40px; border-radius:8px; background:#eff6ff; display:flex; align-items:center; justify-content:center; margin-top:2px;">
                        <i class="fas fa-clipboard-list" style="font-size:16px; color:#2563eb;"></i>
                    </div>
                    @endif
                    <div style="min-width:0;">
                        <p class="mb-0" style="font-size:14px; font-weight:800; color:#111827; line-height:1.3;">
                            {{ optional($survey->template)->name ?? 'Untitled Survey' }}
                        </p>
                        @if(optional($survey->template)->description)
                        <p class="mb-0 mt-1" style="font-size:12px; color:#6b7280; font-weight:700;">
                            {{ $survey->template->description }}
                        </p>
                        @endif
                        @if($survey->closes_at)
                        <p class="mb-0 mt-1" style="font-size:11px; font-weight:800; color:#d97706;">
                            <i class="fas fa-clock mr-1"></i>Closes {{ $survey->closes_at->format('d M Y') }}
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Action --}}
                <div style="flex-shrink:0;">
                    @if($completed)
                    <span class="badge badge-success"
                          style="font-size:12px; font-weight:800; padding:0.45rem 0.85rem; border-radius:999px;">
                        <i class="fas fa-check mr-1"></i> Completed
                    </span>
                    @else
                    <a href="{{ route('student.survey.take', $survey->id) }}"
                       class="btn btn-primary btn-sm"
                       style="font-weight:800; font-size:13px; border-radius:6px; padding:7px 16px;">
                        <i class="fas fa-pen-alt mr-1"></i> Take Survey
                    </a>
                    @endif
                </div>
            </div>
            @endforeach

        </div>
        @endif

    </div>
</section>
@endsection
