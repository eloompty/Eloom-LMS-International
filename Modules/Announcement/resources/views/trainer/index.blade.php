@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Announcements')

@section('header-script')
<style>
.ann-priority-normal    { border-left: 4px solid #0891b2; }
.ann-priority-important { border-left: 4px solid #d97706; }
.ann-priority-urgent    { border-left: 4px solid #dc2626; }
.ann-priority-normal    .ann-badge { background:#e0f2fe; color:#075985; }
.ann-priority-important .ann-badge { background:#fef3c7; color:#92400e; }
.ann-priority-urgent    .ann-badge { background:#fee2e2; color:#991b1b; }
.ann-priority-normal    .ann-icon  { background:#e0f2fe; color:#0891b2; }
.ann-priority-important .ann-icon  { background:#fef3c7; color:#d97706; }
.ann-priority-urgent    .ann-icon  { background:#fee2e2; color:#dc2626; }
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Announcements</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Announcements</li>
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

        <div class="mb-4 d-flex justify-content-end">
            <a href="{{ route('trainer.announcement.create') }}" class="btn btn-primary btn-sm"
               style="font-weight:800; font-size:13px; border-radius:6px; padding:7px 16px;">
                <i class="fas fa-plus mr-1"></i> Post Announcement
            </a>
        </div>

        @if($announcements->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-bullhorn"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No announcements yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Announcements from your institution and your own posts will appear here.</p>
            </div>
        </div>
        @else
        @foreach($announcements as $a)
        @php
            $priority     = $a->priority ?? 'normal';
            $acknowledged = in_array($a->id, $acknowledgedIds);
            $pubDate      = $a->publish_at ? $a->publish_at->format('d M Y') : $a->created_at->format('d M Y');
        @endphp
        <div class="dashboard-panel mb-3 ann-priority-{{ $priority }}">
            <div class="card-header" style="gap:10px;">
                <h3 class="card-title" style="min-width:0; flex:1;">
                    {{ $a->title }}
                </h3>
                <div class="card-tools d-flex align-items-center flex-shrink-0" style="gap:8px;">
                    <span class="ann-badge"
                          style="font-size:10px; font-weight:800; padding:3px 8px; border-radius:999px; text-transform:uppercase; white-space:nowrap;">
                        {{ ucfirst($priority) }}
                    </span>
                    <span style="font-size:11px; font-weight:700; color:#94a3b8; white-space:nowrap;">
                        <i class="fas fa-calendar mr-1"></i>{{ $pubDate }}
                    </span>
                    @if($acknowledged)
                    <span class="badge badge-success"
                          style="font-size:10px; font-weight:800; border-radius:999px; padding:3px 8px; white-space:nowrap;">
                        <i class="fas fa-check mr-1"></i>Read
                    </span>
                    @endif
                </div>
            </div>
            <div class="card-body" style="padding:18px 20px;">
                <div class="d-flex" style="gap:14px;">
                    <div class="ann-icon"
                         style="flex:0 0 36px; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-top:2px; flex-shrink:0;">
                        <i class="fas fa-{{ $priority === 'urgent' ? 'exclamation-circle' : ($priority === 'important' ? 'exclamation' : 'bullhorn') }}"
                           style="font-size:14px;"></i>
                    </div>
                    <div style="min-width:0; flex:1;">
                        <div style="font-size:13px; color:#374151; line-height:1.7;">{!! nl2br(e($a->body)) !!}</div>

                        @if($a->require_acknowledgement && !$acknowledged)
                        <form method="POST" action="{{ route('trainer.announcement.acknowledge', $a->id) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm"
                                    style="font-weight:800; font-size:12px; border-radius:6px; padding:6px 14px;">
                                <i class="fas fa-check mr-1"></i> Mark as Read
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

    </div>
</section>
@endsection
