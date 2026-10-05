@extends('user::layouts.master')
@section('title', 'Admin | Alumni Profile')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ optional($alumni->student)->first_name }} {{ optional($alumni->student)->last_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.alumni.index') }}">Alumni</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if($alumni->interested_in_reenrollment)
        <div class="alert alert-warning">
            <strong>Re-enrollment Interest:</strong> {{ $alumni->reenrollment_notes ?? 'Expressed interest' }}
            <a href="{{ route('admin.alumni.convert', $alumni->id) }}" class="btn btn-sm btn-success ml-3"
               onclick="return confirm('Add to CRM?')">Add to CRM</a>
        </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header"><h3 class="card-title">Academic</h3></div>
                    <div class="card-body">
                        <p><strong>Course:</strong> {{ optional(optional(optional($alumni->intakeCourse)?->intakeCourse)?->course)?->course_name ?? '—' }}</p>
                        <p><strong>Graduation Date:</strong> {{ $alumni->graduation_date ? $alumni->graduation_date->format('d M Y') : '—' }}</p>
                        <p><strong>In Alumni Directory:</strong> {{ $alumni->directory_visible ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.alumni.index') }}" class="btn btn-secondary btn-sm">Back to Alumni</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header"><h3 class="card-title">Employment Outcome</h3></div>
                    <div class="card-body">
                        <p><strong>Employer:</strong> {{ $alumni->employer_name ?? 'Not provided' }}</p>
                        <p><strong>Job Title:</strong> {{ $alumni->job_title ?? '—' }}</p>
                        <p><strong>Industry:</strong> {{ $alumni->industry ?? '—' }}</p>
                        <p><strong>Employment Start:</strong> {{ $alumni->employment_start_date ? $alumni->employment_start_date->format('d M Y') : '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
