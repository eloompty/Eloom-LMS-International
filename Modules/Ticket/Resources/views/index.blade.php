@extends('user::layouts.master')
@section('title', 'Admin | Tickets')

@section('content')
<style>
    .status-tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #e5e7eb;
        padding: 0 20px;
        background: #fff;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .status-tabs::-webkit-scrollbar { display: none; }
    .status-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        text-decoration: none;
        white-space: nowrap;
        transition: color .15s, border-color .15s;
        flex-shrink: 0;
    }
    .status-tab:hover { color: #2563eb; text-decoration: none; }
    .status-tab.active { color: #2563eb; border-bottom-color: #2563eb; }

    .ticket-status-badge {
        display: inline-block;
        padding: 2px 9px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
    }
    .ticket-status-1 { background:#dbeafe; color:#1d4ed8; }
    .ticket-status-2 { background:#fef3c7; color:#92400e; }
    .ticket-status-3 { background:#fee2e2; color:#991b1b; }
    .ticket-status-4 { background:#f0fdf4; color:#166534; }
</style>

@if ($text = Session::get('success'))
<div class="alert alert-success mx-3 mt-3" style="border-radius:8px;border:1px solid #bbf7d0;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-check-circle mr-2"></i><strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger mx-3 mt-3" style="border-radius:8px;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{ $text }}</strong>
</div>
@endif

<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center py-1">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:20px;font-weight:800;color:#111827;line-height:1.2;">Support Tickets</h1>
                <p class="m-0 mt-1" style="font-size:13px;color:#6b7280;">Manage and respond to student support requests</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0" style="background:none;padding:0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color:#2563eb;">Home</a></li>
                    <li class="breadcrumb-item active" style="color:#6b7280;">Tickets</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <!-- Status Tabs -->
                    <div class="status-tabs">
                        <a class="status-tab {{ $status == 1 ? 'active' : '' }}"
                           href="{{ route('admin.ticket.index') }}?status=1">
                            <i class="fas fa-circle" style="font-size:7px;"></i> Opened
                        </a>
                        <a class="status-tab {{ $status == 2 ? 'active' : '' }}"
                           href="{{ route('admin.ticket.index') }}?status=2">
                            <i class="fas fa-circle" style="font-size:7px;"></i> In Progress
                        </a>
                        <a class="status-tab {{ $status == 3 ? 'active' : '' }}"
                           href="{{ route('admin.ticket.index') }}?status=3">
                            <i class="fas fa-circle" style="font-size:7px;"></i> Closed
                        </a>
                        <a class="status-tab {{ $status == 4 ? 'active' : '' }}"
                           href="{{ route('admin.ticket.index') }}?status=4">
                            <i class="fas fa-circle" style="font-size:7px;"></i> Reopened
                        </a>
                    </div>

                    <!-- Card Header -->
                    <div class="card-header">
                        <h3 class="card-title mb-0">{{ getTicketStatus($status) }} Tickets</h3>
                    </div>

                    <div class="card-body">
                        @if(count($tickets) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Raised By</th>
                                    <th>Status</th>
                                    <th>Opened Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td style="font-weight:600;">{{ $value->subject }}</td>
                                    <td>{{ userName($value->user_type, $value->user_id) }}</td>
                                    <td>
                                        <span class="ticket-status-badge ticket-status-{{ $value->status }}">
                                            {{ getTicketStatus($value->status) }}
                                        </span>
                                    </td>
                                    <td>{{ dateFormat($value->created_at) }}</td>
                                    <td>
                                        <a href="{{ route('admin.ticket.show', $value->id) }}"
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center py-5" style="color:#9ca3af;">
                            <i class="fas fa-ticket-alt d-block mb-2" style="font-size:36px;opacity:.25;"></i>
                            <p class="mb-0" style="font-size:13px;">No {{ strtolower(getTicketStatus($status)) }} tickets found</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
