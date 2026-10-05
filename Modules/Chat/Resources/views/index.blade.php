@extends('user::layouts.master')
@section('title', 'Admin | Chats')

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
    .chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
    }
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
                <h1 class="m-0" style="font-size:20px;font-weight:800;color:#111827;line-height:1.2;">Chats</h1>
                <p class="m-0 mt-1" style="font-size:13px;color:#6b7280;">Manage group and direct chat conversations</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0" style="background:none;padding:0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color:#2563eb;">Home</a></li>
                    <li class="breadcrumb-item active" style="color:#6b7280;">Chats</li>
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
                           href="{{ route('admin.chat.index') }}?status=1">
                            <i class="fas fa-circle" style="font-size:7px;"></i> Active
                        </a>
                        <a class="status-tab {{ $status == 0 ? 'active' : '' }}"
                           href="{{ route('admin.chat.index') }}?status=0">
                            <i class="fas fa-circle" style="font-size:7px;"></i> Inactive
                        </a>
                        <a class="status-tab {{ $status == 2 ? 'active' : '' }}"
                           href="{{ route('admin.chat.index') }}?status=2">
                            <i class="fas fa-circle" style="font-size:7px;"></i> Deleted
                        </a>
                    </div>

                    <!-- Card Header -->
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                        <h3 class="card-title mb-0">{{ getChatStatus($status) }} Chats</h3>
                        @if ($status == 1)
                        <a href="{{ route('admin.chat.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Chat
                        </a>
                        @endif
                    </div>

                    <div class="card-body">
                        @if(count($chats) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Image</th>
                                    <th>Opened Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chats as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td style="font-weight:600;">{{ $value->title }}</td>
                                    <td>
                                        @if($value->type == 'Group')
                                        <span class="badge badge-info">Group</span>
                                        @else
                                        <span class="badge badge-secondary">{{ $value->type }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ asset($value->image) }}" target="_blank">
                                            <img src="{{ asset($value->image) }}"
                                                 class="chat-avatar"
                                                 alt="{{ $value->title }}">
                                        </a>
                                    </td>
                                    <td>{{ dateFormat($value->created_at) }}</td>
                                    <td>
                                        <a href="{{ route('admin.chat.show', $value->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-comment"></i> Messages
                                        </a>
                                        @if ($value->type == 'Group')
                                        <a href="{{ route('admin.chat.edit', $value->id) }}"
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center py-5" style="color:#9ca3af;">
                            <i class="fas fa-comments d-block mb-2" style="font-size:36px;opacity:.25;"></i>
                            <p class="mb-0" style="font-size:13px;">No {{ strtolower(getChatStatus($status)) }} chats found</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
