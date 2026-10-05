@extends('student::student.layouts.master')
@section('title', 'Student | Ticket Details')

@section('content')
<style>
    /* ── Status Badge ── */
    .ticket-status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
    }
    .ticket-status-1 { background:#dbeafe; color:#1d4ed8; }
    .ticket-status-2 { background:#fef3c7; color:#92400e; }
    .ticket-status-3 { background:#fee2e2; color:#991b1b; }
    .ticket-status-4 { background:#f0fdf4; color:#166534; }

    /* ── Ticket Detail Card ── */
    .ticket-detail-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(15,23,42,.05);
        margin-bottom: 18px;
    }
    .ticket-detail-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
        flex-wrap: wrap;
    }
    .ticket-subject {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 6px;
        line-height: 1.3;
    }
    .ticket-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 12px;
        color: #6b7280;
    }
    .ticket-meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
    }
    .ticket-meta-item i { color: #9ca3af; font-size: 11px; }
    .ticket-body {
        padding: 18px 20px;
        font-size: 14px;
        color: #374151;
        line-height: 1.65;
        white-space: pre-wrap;
    }

    /* ── Attachments ── */
    .attachment-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 0 20px 16px;
    }
    .attachment-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        background: #f8fafc;
        text-decoration: none;
        transition: border-color .12s, background .12s;
    }
    .attachment-item:hover {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
        text-decoration: none;
    }
    .attachment-item i { color: #2563eb; font-size: 13px; }

    /* ── Status event ── */
    .status-event {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        margin: 0 20px 16px;
    }
    .status-event-closed   { background:#fee2e2; color:#991b1b; }
    .status-event-reopened { background:#f0fdf4; color:#166534; }

    /* ── Replies Thread ── */
    .replies-section {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(15,23,42,.05);
        margin-bottom: 18px;
    }
    .replies-header {
        padding: 13px 20px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .reply-item {
        display: flex;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .reply-item:last-child { border-bottom: none; }
    .reply-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e5e7eb;
    }
    .reply-body { flex: 1; min-width: 0; }
    .reply-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
        flex-wrap: wrap;
    }
    .reply-author { font-size: 13px; font-weight: 700; color: #111827; }
    .reply-time   { font-size: 11px; color: #9ca3af; font-weight: 600; }
    .reply-message { font-size: 13px; color: #374151; line-height: 1.6; }
    .reply-attachments { margin-top: 8px; display: flex; flex-wrap: wrap; gap: 6px; }

    /* ── Reply Form ── */
    .reply-form-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(15,23,42,.05);
        margin-bottom: 18px;
    }
    .reply-form-header {
        padding: 13px 20px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .reply-form-body { padding: 18px 20px; }
    .reply-form-body .form-group label {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 6px;
    }
    .reply-form-body .form-control {
        border-radius: 8px;
        border-color: #dbe3ef;
        font-size: 13px;
        color: #111827;
    }
    .reply-form-body .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }

    /* ── Closed / Pending notice ── */
    .ticket-notice {
        margin-bottom: 18px;
        padding: 14px 18px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f8fafc;
        font-size: 13px;
        color: #6b7280;
        text-align: center;
    }

    .title-icon {
        width: 26px; height: 26px; border-radius: 6px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; flex-shrink: 0;
    }
    .title-icon-blue  { background:#eff6ff; color:#2563eb; }
    .title-icon-amber { background:#fffbeb; color:#d97706; }
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
                <h1 class="m-0" style="font-size:20px;font-weight:800;color:#111827;line-height:1.2;">Ticket Details</h1>
                <p class="m-0 mt-1" style="font-size:13px;color:#6b7280;">Support ticket thread</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0" style="background:none;padding:0;">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" style="color:#2563eb;">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.ticket.index') }}" style="color:#2563eb;">Tickets</a></li>
                    <li class="breadcrumb-item active" style="color:#6b7280;">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <!-- Ticket Detail -->
        <div class="ticket-detail-card">
            <div class="ticket-detail-header">
                <div>
                    <h2 class="ticket-subject">{{ $ticket->subject }}</h2>
                    <div class="ticket-meta">
                        <span class="ticket-meta-item">
                            <i class="far fa-calendar-alt"></i>
                            Opened {{ dateFormat($ticket->created_at) }}
                        </span>
                        <span class="ticket-meta-item">
                            <span class="ticket-status-badge ticket-status-{{ $ticket->status }}">
                                {{ getTicketStatus($ticket->status) }}
                            </span>
                        </span>
                    </div>
                </div>
                <a href="{{ route('student.ticket.index') }}" class="btn btn-light btn-sm flex-shrink-0">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Tickets
                </a>
            </div>

            <div class="ticket-body">{{ $ticket->description }}</div>

            @if($ticket->attachments->count())
            <div class="attachment-list">
                @foreach($ticket->attachments as $attachment)
                @php $fileName = basename($attachment->path); @endphp
                <a href="{{ asset($attachment->path) }}" target="_blank" class="attachment-item">
                    <i class="fas fa-paperclip"></i> {{ $fileName }}
                </a>
                @endforeach
            </div>
            @endif

            @if($ticket->status == 3 && $closed)
            <div class="status-event status-event-closed">
                <i class="fas fa-lock"></i>
                Closed on {{ $closed->created_at->format('d M Y, H:i') }}
                by {{ userName($closed->user_type, $closed->user_id) }}
            </div>
            @endif

            @if($ticket->status == 4 && $reopned)
            <div class="status-event status-event-reopened">
                <i class="fas fa-redo"></i>
                Reopened on {{ $reopned->created_at->format('d M Y, H:i') }}
                by {{ userName($reopned->user_type, $reopned->user_id) }}
            </div>
            @endif
        </div>

        <!-- Replies -->
        <div class="replies-section">
            <div class="replies-header">
                <span class="title-icon title-icon-blue"><i class="fas fa-reply-all"></i></span>
                Replies
                @if($ticket->replies->count())
                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;padding:0 5px;border-radius:9px;background:#dbeafe;color:#1d4ed8;font-size:10px;font-weight:700;">
                    {{ $ticket->replies->count() }}
                </span>
                @endif
            </div>

            @forelse($ticket->replies as $reply)
            @php
                $replyImage = ($reply->user_type == 'Student')
                    ? $reply->student->image
                    : $reply->user->image;
            @endphp
            <div class="reply-item">
                <img src="{{ asset($replyImage) }}" class="reply-avatar"
                     alt="{{ userName($reply->user_type, $reply->user_id) }}">
                <div class="reply-body">
                    <div class="reply-meta">
                        <span class="reply-author">{{ userName($reply->user_type, $reply->user_id) }}</span>
                        <span style="display:inline-block;padding:1px 7px;border-radius:3px;font-size:10px;font-weight:700;background:#f1f5f9;color:#475569;">
                            {{ $reply->user_type }}
                        </span>
                        <span class="reply-time">{{ $reply->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <p class="reply-message mb-0">{{ $reply->message }}</p>
                    @if($reply->attachments->count())
                    <div class="reply-attachments">
                        @foreach($reply->attachments as $attachment)
                        @php $fileName = basename($attachment->path); @endphp
                        <a href="{{ asset($attachment->path) }}" target="_blank" class="attachment-item">
                            <i class="fas fa-paperclip"></i> {{ $fileName }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-4" style="color:#9ca3af;">
                <i class="fas fa-comment-slash d-block mb-2" style="font-size:28px;opacity:.25;"></i>
                <p class="mb-0" style="font-size:13px;">No replies yet</p>
            </div>
            @endforelse
        </div>

        <!-- Reply Form / Notices -->
        @if($ticket->status == 2 || $ticket->status == 4)
        <div class="reply-form-card">
            <div class="reply-form-header">
                <span class="title-icon title-icon-amber"><i class="fas fa-reply"></i></span>
                Reply to this Ticket
            </div>
            <div class="reply-form-body">
                <form action="{{ route('student.ticket.reply', $ticket->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Type your reply…"
                                  required></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label>Attachments <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
                        <input type="file" name="attachments[]" class="form-control-file" multiple>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i> Send Reply
                        </button>
                        <a href="{{ route('student.ticket.index') }}" class="btn btn-light ml-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        @elseif($ticket->status == 1)
        <div class="ticket-notice">
            <i class="fas fa-clock mr-2" style="color:#d97706;"></i>
            Your ticket has been received and is awaiting a response from our team.
        </div>
        @elseif($ticket->status == 3)
        <div class="ticket-notice">
            <i class="fas fa-lock mr-2" style="color:#9ca3af;"></i>
            This ticket is closed. Contact support to reopen it.
        </div>
        @endif

    </div>
</section>
@endsection
