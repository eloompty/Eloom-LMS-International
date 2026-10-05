@extends('user::layouts.master')
@section('title', 'Admin | Events')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Events</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
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

        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-alt"></i>
                    All Events
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.event.create') }}" class="btn btn-primary btn-sm"
                       style="font-weight:800; font-size:12px; border-radius:6px; padding:5px 14px;">
                        <i class="fas fa-plus mr-1"></i> New Event
                    </a>
                </div>
            </div>

            @if($events->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-calendar-alt"></i>
                <p class="mb-0" style="font-weight:700; font-size:14px; color:#374151;">No events created yet.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Title</th>
                            <th style="width:110px;">Type</th>
                            <th class="text-center" style="width:100px;">Capacity</th>
                            <th class="text-center" style="width:110px;">Registered</th>
                            <th style="width:130px;">Deadline</th>
                            <th class="text-center" style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td style="color:#94a3b8; font-weight:700;">{{ $event->id }}</td>
                            <td style="font-weight:700; font-size:13px;">{{ $event->title }}</td>
                            <td>
                                <span class="badge badge-{{ $event->type === 'online' ? 'primary' : 'secondary' }}"
                                      style="font-size:11px; font-weight:800; padding:0.3rem 0.6rem; border-radius:999px;">
                                    <i class="fas fa-{{ $event->type === 'online' ? 'video' : 'map-marker-alt' }} mr-1"></i>
                                    {{ ucfirst($event->type) }}
                                </span>
                            </td>
                            <td class="text-center" style="font-size:13px;">
                                {{ $event->capacity ?? '—' }}
                            </td>
                            <td class="text-center">
                                <span style="font-size:13px; font-weight:700; color:#374151;">
                                    {{ $event->confirmed_registrations_count }}
                                </span>
                                @if($event->capacity)
                                <span style="font-size:11px; color:#94a3b8;">/ {{ $event->capacity }}</span>
                                @endif
                            </td>
                            <td style="font-size:12px; color:#374151;">
                                {{ $event->registration_deadline ? $event->registration_deadline->format('d M Y') : '—' }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center" style="gap:5px;">
                                    <a href="{{ route('admin.event.show', $event->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px; color:#0891b2; border-color:#bae6fd;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.event.edit', $event->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px; color:#d97706; border-color:#fde68a;">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="{{ route('admin.event.delete', $event->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px; color:#dc2626; border-color:#fecaca;"
                                       onclick="return confirm('Delete this event?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer" style="background:#f8fafc; border-top:1px solid #e5e7eb; padding:12px 16px;">
                {{ $events->links() }}
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
