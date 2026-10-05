@extends('user::layouts.master')
@section('title', 'Admin | Dispatch Survey')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Dispatch Survey</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.survey.instance.index') }}">Dispatched Surveys</a></li>
                    <li class="breadcrumb-item active">Dispatch</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

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
                            <i class="fas fa-paper-plane"></i>
                            Dispatch Survey to Audience
                        </h3>
                    </div>
                    <div class="card-body" style="padding:22px;">
                        <form method="POST" action="{{ route('admin.survey.instance.store') }}">
                            @csrf

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Survey Template <span style="color:#dc2626;">*</span>
                                </label>
                                <select name="survey_template_id" class="form-control" required
                                        style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    <option value="">— Select Template —</option>
                                    @foreach($templates as $t)
                                    <option value="{{ $t->id }}" {{ request('template_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Target Audience
                                        </label>
                                        <select name="target_type" class="form-control"
                                                style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                            <option value="all">All Students</option>
                                            <option value="intake">Specific Intake</option>
                                            <option value="course">Specific Course</option>
                                            <option value="event">Event Attendees</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Target ID
                                            <span style="font-weight:700; text-transform:none; color:#6b7280;">— if not "All Students"</span>
                                        </label>
                                        <input type="number" name="target_id" class="form-control"
                                               placeholder="e.g. intake or event ID"
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Dispatch At
                                            <span style="font-weight:700; text-transform:none; color:#6b7280;">— blank = now</span>
                                        </label>
                                        <input type="datetime-local" name="dispatch_at" class="form-control"
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Closes At
                                        </label>
                                        <input type="datetime-local" name="closes_at" class="form-control"
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap mt-4" style="gap:8px;">
                                <a href="{{ route('admin.survey.instance.index') }}" class="panel-action">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary"
                                        style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 22px;">
                                    <i class="fas fa-paper-plane mr-1"></i> Dispatch Survey
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
