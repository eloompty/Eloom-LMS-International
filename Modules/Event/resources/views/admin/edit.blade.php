@extends('user::layouts.master')
@section('title', 'Admin | Edit Event')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Edit Event</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.event.index') }}">Events</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.event.show', $event->id) }}">{{ Str::limit($event->title, 30) }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
                            <i class="fas fa-pen"></i>
                            {{ $event->title }}
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.event.show', $event->id) }}" class="panel-action">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                    <div class="card-body" style="padding:22px;">
                        <form method="POST" action="{{ route('admin.event.update', $event->id) }}">
                            @csrf

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Title <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="title" class="form-control" required
                                       value="{{ old('title', $event->title) }}"
                                       style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                            </div>

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Description
                                </label>
                                <textarea name="description" class="form-control" rows="4"
                                          style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;">{{ old('description', $event->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Type
                                        </label>
                                        <select name="type" class="form-control"
                                                style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                            @foreach(['online','physical','hybrid'] as $t)
                                            <option value="{{ $t }}" {{ old('type', $event->type) === $t ? 'selected' : '' }}>
                                                {{ ucfirst($t) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Capacity
                                            <span style="font-weight:700; text-transform:none; color:#6b7280;">— blank = unlimited</span>
                                        </label>
                                        <input type="number" name="capacity" class="form-control" min="1"
                                               value="{{ old('capacity', $event->capacity) }}"
                                               placeholder="Unlimited"
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Registration Deadline
                                        </label>
                                        <input type="datetime-local" name="registration_deadline" class="form-control"
                                               value="{{ old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i')) }}"
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Online Link
                                </label>
                                <input type="url" name="online_link" class="form-control"
                                       value="{{ old('online_link', $event->online_link) }}"
                                       placeholder="https://..."
                                       style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                            </div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap mt-4" style="gap:8px;">
                                <a href="{{ route('admin.event.show', $event->id) }}" class="panel-action">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary"
                                        style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 22px;">
                                    <i class="fas fa-save mr-1"></i> Update Event
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
