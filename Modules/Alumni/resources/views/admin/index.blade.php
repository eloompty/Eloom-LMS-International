@extends('user::layouts.master')
@section('title', 'Admin | Alumni')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Alumni</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Alumni</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
             style="border-radius:8px; border:1px solid #a7f3d0; background:#f0fdf4; color:#065f46; font-size:13px; font-weight:700;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" style="color:#065f46;"><span>&times;</span></button>
        </div>
        @endif

        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate"></i>
                    All Alumni
                </h3>
                <div class="card-tools d-flex" style="gap:8px;">
                    <a href="{{ route('admin.alumni.directory') }}" class="panel-action">
                        <i class="fas fa-address-book"></i> Directory
                    </a>
                    <a href="{{ route('admin.alumni.create') }}" class="btn btn-primary btn-sm"
                       style="font-weight:800; font-size:12px; border-radius:6px; padding:5px 14px;">
                        <i class="fas fa-plus mr-1"></i> Manual Entry
                    </a>
                </div>
            </div>

            @if($alumni->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-user-graduate"></i>
                <p class="mb-0" style="font-weight:700; font-size:14px; color:#374151;">No alumni records yet.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th style="width:120px;">Graduated</th>
                            <th>Employer / Role</th>
                            <th class="text-center" style="width:130px;">Re-enroll Interest</th>
                            <th class="text-center" style="width:130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumni as $a)
                        <tr>
                            <td style="color:#94a3b8; font-weight:700;">{{ $a->id }}</td>
                            <td style="font-weight:700; font-size:13px;">
                                {{ optional($a->student)->first_name }} {{ optional($a->student)->last_name }}
                            </td>
                            <td style="font-size:12px; color:#374151;">
                                {{ optional(optional(optional($a->intakeCourse)?->intakeCourse)?->course)?->course_name ?? '—' }}
                            </td>
                            <td style="font-size:12px; color:#374151;">
                                {{ $a->graduation_date ? $a->graduation_date->format('d M Y') : '—' }}
                            </td>
                            <td style="font-size:12px; color:#374151;">
                                @if($a->employer_name || $a->job_title)
                                    {{ $a->job_title ?? '' }}
                                    @if($a->job_title && $a->employer_name) @ @endif
                                    {{ $a->employer_name ?? '' }}
                                @else
                                    <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($a->interested_in_reenrollment)
                                <span class="badge badge-warning"
                                      style="font-size:11px; font-weight:800; padding:0.3rem 0.6rem; border-radius:999px;">
                                    <i class="fas fa-flag mr-1"></i>Interested
                                </span>
                                @else
                                <span style="color:#94a3b8; font-size:12px;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center" style="gap:5px;">
                                    <a href="{{ route('admin.alumni.show', $a->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px; color:#0891b2; border-color:#bae6fd;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($a->interested_in_reenrollment)
                                    <a href="{{ route('admin.alumni.convert', $a->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px; color:#059669; border-color:#a7f3d0;"
                                       onclick="return confirm('Add to CRM as lead?')">
                                        <i class="fas fa-arrow-right"></i> CRM
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer" style="background:#f8fafc; border-top:1px solid #e5e7eb; padding:12px 16px;">
                {{ $alumni->links() }}
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
