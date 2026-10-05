@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Events')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Upcoming Events</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
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

        @if($events->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-calendar-alt"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No upcoming events at this time.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Events scheduled by your institution will appear here.</p>
            </div>
        </div>
        @else
        @foreach($events as $event)
        @php
            $deadlinePassed = $event->registration_deadline && now()->gt($event->registration_deadline);
            $isFull = $event->capacity && $event->confirmed_registrations_count >= $event->capacity;
        @endphp
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-{{ $event->type === 'online' ? 'video' : 'map-marker-alt' }} mr-1"></i>
                    {{ $event->title }}
                </h3>
                <div class="card-tools d-flex align-items-center" style="gap:8px;">
                    @if($event->type === 'online')
                    <span class="badge badge-primary"
                          style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">Online</span>
                    @elseif($event->type === 'hybrid')
                    <span class="badge badge-warning"
                          style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">Hybrid</span>
                    @else
                    <span class="badge badge-secondary"
                          style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">In-Person</span>
                    @endif

                    @if($event->trainer_registered)
                    <span class="badge badge-success"
                          style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">
                        <i class="fas fa-check mr-1"></i>Registered
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
                                {{ \Carbon\Carbon::parse($s->starts_at)->format('d M Y') }}
                                <span style="color:#6b7280;">
                                    {{ \Carbon\Carbon::parse($s->starts_at)->format('H:i') }}
                                    &ndash;
                                    {{ \Carbon\Carbon::parse($s->ends_at)->format('H:i') }}
                                </span>
                                @if($s->title) &mdash; <span style="color:#111827;">{{ $s->title }}</span>@endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="d-flex flex-wrap align-items-center mb-3" style="gap:16px;">
                    @if($event->capacity)
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        <i class="fas fa-users mr-1"></i>
                        {{ $event->confirmed_registrations_count }} / {{ $event->capacity }} registered
                        @if($isFull)
                        <span class="badge badge-danger ml-1" style="font-size:10px; border-radius:999px;">Full</span>
                        @endif
                    </span>
                    @else
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        <i class="fas fa-users mr-1"></i>{{ $event->confirmed_registrations_count }} registered
                    </span>
                    @endif

                    @if($event->registration_deadline)
                        @if($deadlinePassed)
                        <span style="font-size:12px; font-weight:700; color:#dc2626;">
                            <i class="fas fa-hourglass-end mr-1"></i>Deadline passed {{ \Carbon\Carbon::parse($event->registration_deadline)->format('d M Y') }}
                        </span>
                        @else
                        <span style="font-size:12px; font-weight:700; color:#d97706;">
                            <i class="fas fa-hourglass-half mr-1"></i>Register by {{ \Carbon\Carbon::parse($event->registration_deadline)->format('d M Y') }}
                        </span>
                        @endif
                    @endif

                    @if($event->location)
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location }}
                    </span>
                    @endif

                    @if($event->type === 'online' && $event->online_link)
                    <a href="{{ $event->online_link }}" target="_blank" rel="noopener"
                       class="panel-action" style="font-size:12px;">
                        <i class="fas fa-video"></i> Join Link
                    </a>
                    @endif
                </div>

                @if($event->issue_participation_certificate)
                <div style="font-size:12px; font-weight:700; color:#0891b2;">
                    <i class="fas fa-certificate mr-1"></i> Participation certificate issued upon completion
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endif

    </div>
</section>
@endsection
