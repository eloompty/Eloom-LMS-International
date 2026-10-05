@extends('user::layouts.master')
@section('title', 'Admin | Event Detail')

@section('header-script')
<style>
.attendance-check {
    width:18px; height:18px; accent-color:#2563eb; cursor:pointer;
}
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1>{{ $event->title }}</h1>
            </div>
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.event.index') }}">Events</a></li>
                    <li class="breadcrumb-item active">Detail</li>
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

        {{-- KPI row --}}
        @php
            $registeredCount = $event->registrations->where('status','registered')->count();
            $waitlistedCount = $event->registrations->where('status','waitlisted')->count();
            $attendedCount   = $event->registrations->where('status','attended')->count();
            $sessionCount    = $event->sessions->count();
        @endphp
        <div class="row mb-4">
            <div class="col-6 col-sm-3 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-blue"><i class="fas fa-user-check"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Registered</span>
                        <span class="metric-value">{{ $registeredCount }}</span>
                        @if($event->capacity)
                        <span class="metric-note">of {{ $event->capacity }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-green"><i class="fas fa-clipboard-check"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Attended</span>
                        <span class="metric-value">{{ $attendedCount }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-amber"><i class="fas fa-clock"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Waitlisted</span>
                        <span class="metric-value">{{ $waitlistedCount }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3 mb-3">
                <div class="metric-card">
                    <div class="metric-icon metric-cyan"><i class="fas fa-calendar-day"></i></div>
                    <div class="metric-content">
                        <span class="metric-label">Sessions</span>
                        <span class="metric-value">{{ $sessionCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Event info panel --}}
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Event Info
                </h3>
                <div class="card-tools d-flex" style="gap:8px;">
                    <a href="{{ route('admin.event.edit', $event->id) }}"
                       class="panel-action"
                       style="color:#d97706; border-color:#fde68a;">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <a href="{{ route('admin.event.index') }}" class="panel-action">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body" style="padding:18px 20px;">
                <div class="row">
                    <div class="col-md-6">
                        <table style="width:100%; border-collapse:separate; border-spacing:0 8px;">
                            <tr>
                                <td style="width:150px; font-size:11px; font-weight:800; color:#6b7280; text-transform:uppercase; padding-right:12px;">Type</td>
                                <td>
                                    <span class="badge badge-{{ $event->type === 'online' ? 'primary' : ($event->type === 'hybrid' ? 'info' : 'secondary') }}"
                                          style="font-size:11px; font-weight:800; padding:0.3rem 0.65rem; border-radius:999px;">
                                        <i class="fas fa-{{ $event->type === 'online' ? 'video' : ($event->type === 'hybrid' ? 'random' : 'map-marker-alt') }} mr-1"></i>
                                        {{ ucfirst($event->type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px; font-weight:800; color:#6b7280; text-transform:uppercase;">Capacity</td>
                                <td style="font-size:13px; font-weight:700; color:#374151;">
                                    {{ $event->capacity ?? 'Unlimited' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px; font-weight:800; color:#6b7280; text-transform:uppercase;">Deadline</td>
                                <td style="font-size:13px; font-weight:700; color:#374151;">
                                    {{ $event->registration_deadline ? $event->registration_deadline->format('d M Y H:i') : '—' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px; font-weight:800; color:#6b7280; text-transform:uppercase;">Waiting List</td>
                                <td>
                                    @if($event->waiting_list_enabled)
                                    <span style="font-size:12px; font-weight:700; color:#059669;">
                                        <i class="fas fa-check-circle mr-1"></i>Enabled
                                    </span>
                                    @else
                                    <span style="font-size:12px; font-weight:700; color:#94a3b8;">Disabled</span>
                                    @endif
                                </td>
                            </tr>
                            @if($event->online_link)
                            <tr>
                                <td style="font-size:11px; font-weight:800; color:#6b7280; text-transform:uppercase;">Online Link</td>
                                <td style="font-size:13px;">
                                    <a href="{{ $event->online_link }}" target="_blank"
                                       style="color:#2563eb; font-weight:700; word-break:break-all;">
                                        {{ $event->online_link }}
                                    </a>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        @if($event->description)
                        <p style="font-size:13px; color:#374151; line-height:1.7; margin:0;">{{ $event->description }}</p>
                        @else
                        <p style="font-size:13px; color:#94a3b8; font-style:italic; margin:0;">No description provided.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sessions --}}
        @if($event->sessions->isNotEmpty())
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-day"></i>
                    Sessions
                </h3>
                <div class="card-tools">
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        {{ $event->sessions->count() }} {{ Str::plural('session', $event->sessions->count()) }}
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>Session Title</th>
                            <th style="width:180px;">Starts</th>
                            <th style="width:180px;">Ends</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->sessions as $s)
                        <tr>
                            <td style="font-weight:700; font-size:13px;">{{ $s->title }}</td>
                            <td style="font-size:12px; color:#374151;">{{ $s->starts_at->format('d M Y H:i') }}</td>
                            <td style="font-size:12px; color:#374151;">{{ $s->ends_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Registrations + Attendance --}}
        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users"></i>
                    Registrations
                    <span style="font-size:12px; font-weight:700; color:#6b7280; margin-left:6px;">
                        ({{ $event->registrations->count() }})
                    </span>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.event.promote', $event->id) }}" class="panel-action"
                       style="font-size:12px;">
                        <i class="fas fa-arrow-up"></i> Promote from Waitlist
                    </a>
                </div>
            </div>

            @if($event->registrations->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-users"></i>
                <p class="mb-0" style="font-weight:700; font-size:14px; color:#374151;">No registrations yet.</p>
            </div>
            @else
            <form method="POST" action="{{ route('admin.event.attendance', $event->id) }}">
                @csrf
                <div class="table-responsive">
                    <table class="table dashboard-table mb-0">
                        <thead>
                            <tr>
                                <th>Attendee</th>
                                <th class="text-center" style="width:130px;">Status</th>
                                <th style="width:120px;">Registered</th>
                                <th class="text-center" style="width:120px;">Mark Attended</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($event->registrations as $reg)
                            @php
                                $regBadge = $reg->status === 'attended' ? 'success' : ($reg->status === 'waitlisted' ? 'warning' : 'info');
                            @endphp
                            <tr>
                                <td style="font-size:13px; font-weight:700; color:#374151;">
                                    {{ ucfirst($reg->registrant_type) }} #{{ $reg->registrant_id }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $regBadge }}"
                                          style="font-size:11px; font-weight:800; padding:0.3rem 0.65rem; border-radius:999px;">
                                        {{ ucfirst($reg->status) }}
                                    </span>
                                </td>
                                <td style="font-size:12px; color:#374151;">
                                    {{ $reg->registered_at->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    @if($reg->status !== 'attended')
                                    <input type="checkbox"
                                           name="attendee_ids[]"
                                           value="{{ $reg->id }}"
                                           class="attendance-check">
                                    @else
                                    <i class="fas fa-check-circle" style="color:#059669; font-size:16px;"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="background:#f8fafc; border-top:1px solid #e5e7eb; padding:14px 18px;">
                    <button type="submit" class="btn btn-primary btn-sm"
                            style="font-weight:800; font-size:13px; border-radius:6px; padding:7px 18px;">
                        <i class="fas fa-clipboard-check mr-1"></i> Save Attendance
                    </button>
                </div>
            </form>
            @endif
        </div>

    </div>
</section>
@endsection
