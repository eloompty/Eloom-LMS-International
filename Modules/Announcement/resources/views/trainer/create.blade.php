@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Post Announcement')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Post Announcement</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.announcement.index') }}">Announcements</a></li>
                    <li class="breadcrumb-item active">Post</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
                     style="border-radius:8px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; font-size:13px; font-weight:700;">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
                    <button type="button" class="close" data-dismiss="alert" style="color:#991b1b;"><span>&times;</span></button>
                </div>
                @endif

                <div class="dashboard-panel">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn"></i>
                            New Announcement
                        </h3>
                    </div>
                    <div class="card-body" style="padding:22px;">
                        <form method="POST" action="{{ route('trainer.announcement.store') }}">
                            @csrf

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Title <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="title" class="form-control" required
                                       value="{{ old('title') }}"
                                       placeholder="Announcement title"
                                       style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                            </div>

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Message <span style="color:#dc2626;">*</span>
                                </label>
                                <textarea name="body" class="form-control" rows="6" required
                                          placeholder="Write your announcement here..."
                                          style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;">{{ old('body') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Audience
                                        </label>
                                        <select name="audience_type" class="form-control"
                                                style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                            <option value="student">All My Students</option>
                                            <option value="intake">Specific Intake</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Intake
                                            <span style="font-weight:700; text-transform:none; color:#6b7280;">— if not "All"</span>
                                        </label>
                                        <select name="audience_id" class="form-control"
                                                style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                            <option value="">— All —</option>
                                            @foreach($intakes as $intake)
                                            <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Priority
                                        </label>
                                        <select name="priority" class="form-control"
                                                style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                            <option value="normal">Normal</option>
                                            <option value="important">Important</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Expires At
                                    <span style="font-weight:700; text-transform:none; color:#6b7280;">— optional</span>
                                </label>
                                <input type="datetime-local" name="expires_at" class="form-control"
                                       style="border-radius:6px; border-color:#d1d5db; font-size:13px; max-width:280px;">
                            </div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap mt-4" style="gap:8px;">
                                <a href="{{ route('trainer.announcement.index') }}" class="panel-action">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary"
                                        style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 22px;">
                                    <i class="fas fa-paper-plane mr-1"></i> Post Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
