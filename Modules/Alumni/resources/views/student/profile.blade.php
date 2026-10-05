@extends('student::student.layouts.master')
@section('title', 'Student | Alumni Profile')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>My Alumni Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Alumni Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
                     style="border-radius:8px; border:1px solid #a7f3d0; background:#f0fdf4; color:#065f46; font-size:13px; font-weight:700;">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" style="color:#065f46;"><span>&times;</span></button>
                </div>
                @endif

                @if(!$alumni)
                <div class="dashboard-panel">
                    <div class="dashboard-empty">
                        <i class="fas fa-user-graduate"></i>
                        <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No alumni profile yet.</p>
                        <p class="mb-0" style="font-size:13px; color:#6b7280;">Your alumni profile will be created automatically when you complete your course.</p>
                    </div>
                </div>
                @else

                {{-- Employment details --}}
                <div class="dashboard-panel mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-briefcase"></i>
                            Employment Outcome
                        </h3>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <form method="POST" action="{{ route('student.alumni.employment') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase;">Employer Name</label>
                                        <input type="text" name="employer_name" class="form-control"
                                               value="{{ $alumni->employer_name }}"
                                               placeholder="e.g. Acme Corp"
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase;">Job Title</label>
                                        <input type="text" name="job_title" class="form-control"
                                               value="{{ $alumni->job_title }}"
                                               placeholder="e.g. Software Engineer"
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase;">Industry</label>
                                        <input type="text" name="industry" class="form-control"
                                               value="{{ $alumni->industry }}"
                                               placeholder="e.g. Technology"
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase;">Employment Start Date</label>
                                <input type="date" name="employment_start_date" class="form-control"
                                       value="{{ $alumni->employment_start_date?->format('Y-m-d') }}"
                                       style="border-radius:6px; border-color:#d1d5db; font-size:13px; max-width:220px;">
                            </div>
                            <div style="padding:14px 16px; background:#f8fafc; border-radius:7px; border:1px solid #e5e7eb; margin-bottom:18px;">
                                <div class="d-flex align-items-center" style="gap:10px;">
                                    <input type="checkbox" name="directory_visible" id="dirVis" value="1"
                                           {{ $alumni->directory_visible ? 'checked' : '' }}
                                           style="width:16px; height:16px; accent-color:#2563eb; cursor:pointer; flex-shrink:0;">
                                    <label for="dirVis" class="mb-0" style="font-size:13px; font-weight:700; color:#374151; cursor:pointer;">
                                        Show me in the Alumni Directory
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"
                                    style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 20px;">
                                <i class="fas fa-save mr-1"></i> Save Employment Details
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Re-enrollment --}}
                @unless($alumni->interested_in_reenrollment)
                <div class="dashboard-panel">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-graduation-cap"></i>
                            Interested in Further Study?
                        </h3>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <p style="font-size:13px; color:#6b7280; margin-bottom:16px;">
                            Let us know if you're interested in re-enrolling or furthering your qualifications. Our admissions team will reach out.
                        </p>
                        <form method="POST" action="{{ route('student.alumni.reenrollment') }}">
                            @csrf
                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase;">What are you interested in? <span style="color:#6b7280; font-weight:700; text-transform:none;">(optional)</span></label>
                                <textarea name="notes" class="form-control" rows="3"
                                          placeholder="e.g. Advanced Diploma in IT, or a Masters degree..."
                                          style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"
                                    style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 20px;">
                                <i class="fas fa-paper-plane mr-1"></i> Express Interest
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="dashboard-panel">
                    <div class="card-body" style="padding:20px;">
                        <div class="d-flex align-items-start" style="gap:14px;">
                            <div style="flex:0 0 40px; width:40px; height:40px; border-radius:8px; background:#f0fdf4; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-check-circle" style="color:#059669; font-size:18px;"></i>
                            </div>
                            <div>
                                <p class="mb-1" style="font-size:14px; font-weight:800; color:#065f46;">Interest in Re-enrollment Received</p>
                                <p class="mb-0" style="font-size:13px; color:#374151;">
                                    You have expressed interest in re-enrollment. Our admissions team will contact you shortly.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endunless

                @endif
            </div>
        </div>
    </div>
</section>
@endsection
