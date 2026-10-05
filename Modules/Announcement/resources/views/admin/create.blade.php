@extends('user::layouts.master')
@section('title', 'Admin | Create Announcement')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Create Announcement</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.announcement.index') }}">Announcements</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <div class="card">
            <div class="card-header"><h3 class="card-title">New Announcement</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.announcement.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Body <span class="text-danger">*</span></label>
                        <textarea name="body" class="form-control" rows="6" required>{{ old('body') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Audience Type</label>
                                <select name="audience_type" class="form-control" id="audienceType">
                                    @foreach(['all','intake','course','role','student','trainer'] as $t)
                                        <option value="{{ $t }}" {{ old('audience_type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="audienceIdRow" style="display:none">
                            <div class="form-group">
                                <label>Audience ID (Intake / Course / Student ID)</label>
                                <input type="number" name="audience_id" class="form-control" value="{{ old('audience_id') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Priority</label>
                                <select name="priority" class="form-control">
                                    @foreach(['normal','important','urgent'] as $p)
                                        <option value="{{ $p }}" {{ old('priority') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Publish At (blank = immediate)</label>
                                <input type="datetime-local" name="publish_at" class="form-control" value="{{ old('publish_at') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Expires At</label>
                                <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_pinned" class="form-check-input" id="isPinned" value="1">
                        <label class="form-check-label" for="isPinned">Pin to dashboards</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="require_acknowledgement" class="form-check-input" id="reqAck" value="1">
                        <label class="form-check-label" for="reqAck">Require acknowledgement</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Publish</button>
                    <a href="{{ route('admin.announcement.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script>
    document.getElementById('audienceType').addEventListener('change', function() {
        var show = ['intake','course','role','student'].includes(this.value);
        document.getElementById('audienceIdRow').style.display = show ? 'block' : 'none';
    });
</script>
@endpush
@endsection
