@extends('student::student.layouts.master')
@section('title', 'Student | Events')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Upcoming Events</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Events</li>
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
        @if(session('failure'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
             style="border-radius:8px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; font-size:13px; font-weight:700;">
            <i class="fas fa-times-circle mr-2"></i>{{ session('failure') }}
            <button type="button" class="close" data-dismiss="alert" style="color:#991b1b;"><span>&times;</span></button>
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert"
             style="border-radius:8px; border:1px solid #bae6fd; background:#f0f9ff; color:#0c4a6e; font-size:13px; font-weight:700;">
            <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
            <button type="button" class="close" data-dismiss="alert" style="color:#0c4a6e;"><span>&times;</span></button>
        </div>
        @endif

        @if($events->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-calendar-alt"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No upcoming events at this time.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Events scheduled for you will appear here.</p>
            </div>
        </div>
        @else
        @foreach($events as $event)
        @php
            $reg           = $event->my_registration;
            $isFull        = $event->isFull();
            $deadlinePassed = $event->registration_deadline && now()->gt($event->registration_deadline);
            $typeBadge     = $event->type === 'online' ? 'primary' : 'secondary';
            $regBadge      = $reg ? ($reg->status === 'registered' ? 'success' : ($reg->status === 'waitlisted' ? 'warning' : 'info')) : null;
        @endphp
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-{{ $event->type === 'online' ? 'video' : 'map-marker-alt' }}"></i>
                    {{ $event->title }}
                </h3>
                <div class="card-tools d-flex align-items-center" style="gap:8px;">
                    <span class="badge badge-{{ $typeBadge }}"
                          style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">
                        {{ ucfirst($event->type) }}
                    </span>
                    @if($reg)
                    <span class="badge badge-{{ $regBadge }}"
                          style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">
                        {{ ucfirst($reg->status) }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="card-body" style="padding:18px 20px;">
                @if($event->description)
                <p style="font-size:13px; color:#374151; line-height:1.6; margin-bottom:14px;">{{ $event->description }}</p>
                @endif

                @if($event->sessions->isNotEmpty())
                <div class="mb-3">
                    <p style="font-size:11px; font-weight:800; color:#6b7280; text-transform:uppercase; margin-bottom:8px;">Sessions</p>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        @foreach($event->sessions as $s)
                        <div style="display:flex; align-items:center; gap:10px; padding:8px 12px; background:#f8fafc; border-radius:7px; border:1px solid #e5e7eb;">
                            <i class="fas fa-clock" style="color:#6b7280; font-size:12px; flex-shrink:0;"></i>
                            <span style="font-size:12px; font-weight:700; color:#374151;">
                                {{ $s->starts_at->format('d M Y') }}
                                <span style="color:#6b7280;">{{ $s->starts_at->format('H:i') }} – {{ $s->ends_at->format('H:i') }}</span>
                                @if($s->title) &mdash; <span style="color:#111827;">{{ $s->title }}</span>@endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="d-flex flex-wrap align-items-center mb-3" style="gap:16px;">
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        <i class="fas fa-users mr-1"></i>
                        {{ $event->confirmed_registrations_count }}{{ $event->capacity ? ' / '.$event->capacity.' registered' : ' registered' }}
                        @if($event->capacity && $isFull)
                        <span class="badge badge-danger ml-1" style="font-size:10px; border-radius:999px;">Full</span>
                        @endif
                    </span>
                    @if($event->registration_deadline)
                        @if($deadlinePassed)
                        <span style="font-size:12px; font-weight:700; color:#dc2626;">
                            <i class="fas fa-hourglass-end mr-1"></i>Deadline passed {{ $event->registration_deadline->format('d M Y H:i') }}
                        </span>
                        @else
                        <span style="font-size:12px; font-weight:700; color:#d97706;">
                            <i class="fas fa-hourglass-half mr-1"></i>Register by {{ $event->registration_deadline->format('d M Y H:i') }}
                        </span>
                        @endif
                    @endif
                </div>

                <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
                    @if($reg && in_array($reg->status, ['registered','waitlisted']))
                    <a href="{{ route('student.event.cancel', $event->id) }}"
                       class="panel-action"
                       style="color:#dc2626; border-color:#fecaca;"
                       onclick="return confirm('Cancel your registration for this event?')">
                        <i class="fas fa-times"></i> Cancel Registration
                    </a>
                    @elseif(!$reg && !$deadlinePassed)
                        @if($isFull && !$event->waiting_list_enabled)
                        <span style="font-size:13px; font-weight:700; color:#dc2626;">
                            <i class="fas fa-ban mr-1"></i> Event is full
                        </span>
                        @else
                        <form method="POST" action="{{ route('student.event.register', $event->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm"
                                    style="font-weight:800; font-size:13px; border-radius:6px; padding:7px 16px;">
                                <i class="fas fa-{{ $isFull ? 'clock' : 'check' }} mr-1"></i>
                                {{ $isFull ? 'Join Waiting List' : 'Register' }}
                            </button>
                        </form>
                        @endif
                    @elseif($deadlinePassed && !$reg)
                    <span style="font-size:13px; font-weight:700; color:#94a3b8;">
                        <i class="fas fa-lock mr-1"></i> Registration closed
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @endif

    </div>
</section>
@endsection
