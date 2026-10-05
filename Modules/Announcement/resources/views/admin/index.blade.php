@extends('user::layouts.master')
@section('title', 'Admin | Announcements')

@section('header-script')
<style>
.ann-pin-icon { color:#d97706; }
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Announcements</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
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

        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bullhorn"></i>
                    All Announcements
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.announcement.create') }}" class="btn btn-primary btn-sm"
                       style="font-weight:800; font-size:12px; border-radius:6px; padding:5px 14px;">
                        <i class="fas fa-plus mr-1"></i> New Announcement
                    </a>
                </div>
            </div>

            @if($announcements->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-bullhorn"></i>
                <p class="mb-0" style="font-weight:700; font-size:14px; color:#374151;">No announcements yet.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Title</th>
                            <th style="width:130px;">Audience</th>
                            <th class="text-center" style="width:110px;">Priority</th>
                            <th class="text-center" style="width:70px;">Pinned</th>
                            <th style="width:140px;">Publish At</th>
                            <th style="width:110px;">Expires</th>
                            <th class="text-center" style="width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($announcements as $a)
                        @php
                            $pCls = ['normal'=>'secondary','important'=>'warning','urgent'=>'danger'][$a->priority] ?? 'secondary';
                        @endphp
                        <tr>
                            <td style="color:#94a3b8; font-weight:700;">{{ $a->id }}</td>
                            <td style="font-weight:700; font-size:13px;">
                                @if($a->is_pinned)
                                <i class="fas fa-thumbtack ann-pin-icon mr-1" style="font-size:11px;" title="Pinned"></i>
                                @endif
                                {{ $a->title }}
                            </td>
                            <td>
                                <span class="badge badge-secondary"
                                      style="font-size:11px; font-weight:800; padding:0.3rem 0.6rem; border-radius:999px;">
                                    {{ $a->audience_type }}
                                </span>
                                @if($a->audience_id)
                                <span style="font-size:11px; color:#6b7280;">#{{ $a->audience_id }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $pCls }}"
                                      style="font-size:11px; font-weight:800; padding:0.3rem 0.6rem; border-radius:999px;">
                                    {{ ucfirst($a->priority) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($a->is_pinned)
                                <i class="fas fa-check" style="color:#059669; font-size:13px;"></i>
                                @else
                                <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td style="font-size:12px; color:#374151;">
                                {{ $a->publish_at ? $a->publish_at->format('d M Y H:i') : 'Immediate' }}
                            </td>
                            <td style="font-size:12px; color:#374151;">
                                {{ $a->expires_at ? $a->expires_at->format('d M Y') : '—' }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center" style="gap:5px;">
                                    <a href="{{ route('admin.announcement.edit', $a->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px; color:#d97706; border-color:#fde68a;">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="{{ route('admin.announcement.delete', $a->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px; color:#dc2626; border-color:#fecaca;"
                                       onclick="return confirm('Delete this announcement?')">
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
                {{ $announcements->links() }}
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
