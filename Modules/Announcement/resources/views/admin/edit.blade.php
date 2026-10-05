@extends('user::layouts.master')
@section('title', 'Admin | Edit Announcement')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Edit Announcement</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.announcement.index') }}">Announcements</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">{{ $announcement->title }}</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.announcement.update', $announcement->id) }}">
                    @csrf
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <textarea name="body" class="form-control" rows="6" required>{{ old('body', $announcement->body) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Audience Type</label>
                                <select name="audience_type" class="form-control">
                                    @foreach(['all','intake','course','role','student','trainer'] as $t)
                                        <option value="{{ $t }}" {{ old('audience_type', $announcement->audience_type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Audience ID</label>
                                <input type="number" name="audience_id" class="form-control" value="{{ old('audience_id', $announcement->audience_id) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Priority</label>
                                <select name="priority" class="form-control">
                                    @foreach(['normal','important','urgent'] as $p)
                                        <option value="{{ $p }}" {{ old('priority', $announcement->priority) === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Publish At</label>
                                <input type="datetime-local" name="publish_at" class="form-control"
                                       value="{{ old('publish_at', $announcement->publish_at?->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Expires At</label>
                                <input type="datetime-local" name="expires_at" class="form-control"
                                       value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_pinned" class="form-check-input" id="isPinned" value="1" {{ $announcement->is_pinned ? 'checked' : '' }}>
                        <label class="form-check-label" for="isPinned">Pin to dashboards</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="require_acknowledgement" class="form-check-input" id="reqAck" value="1" {{ $announcement->require_acknowledgement ? 'checked' : '' }}>
                        <label class="form-check-label" for="reqAck">Require acknowledgement</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.announcement.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
