@extends('user::layouts.master')
@section('title', 'Admin | Create Event')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Create Event</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.event.index') }}">Events</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
                     style="border-radius:8px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; font-size:13px; font-weight:700;">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
                    <button type="button" class="close" data-dismiss="alert" style="color:#991b1b;"><span>&times;</span></button>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.event.store') }}" id="createEventForm">
                    @csrf

                    {{-- Basic details --}}
                    <div class="dashboard-panel mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-plus"></i>
                                Event Details
                            </h3>
                        </div>
                        <div class="card-body" style="padding:22px;">

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Title <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="title" class="form-control" required
                                       value="{{ old('title') }}"
                                       placeholder="Event title"
                                       style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                            </div>

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Description
                                </label>
                                <textarea name="description" class="form-control" rows="4"
                                          placeholder="Optional event description..."
                                          style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;">{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                            Type <span style="color:#dc2626;">*</span>
                                        </label>
                                        <select name="type" class="form-control" required
                                                style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                            <option value="online">Online</option>
                                            <option value="physical">Physical</option>
                                            <option value="hybrid">Hybrid</option>
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
                                               value="{{ old('capacity') }}"
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
                                               style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Online Link
                                </label>
                                <input type="url" name="online_link" class="form-control"
                                       value="{{ old('online_link') }}"
                                       placeholder="https://..."
                                       style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                            </div>

                        </div>
                    </div>

                    {{-- Options --}}
                    <div class="dashboard-panel mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-sliders-h"></i>
                                Options
                            </h3>
                        </div>
                        <div class="card-body" style="padding:18px 22px;">

                            {{-- Waiting list toggle --}}
                            <div style="padding:14px 16px; background:#f8fafc; border-radius:7px; border:1px solid #e5e7eb; margin-bottom:14px;">
                                <div class="d-flex align-items-center" style="gap:10px;">
                                    <input type="checkbox" name="waiting_list_enabled" id="waitList" value="1" checked
                                           style="width:16px; height:16px; accent-color:#2563eb; cursor:pointer; flex-shrink:0;">
                                    <div>
                                        <label for="waitList" class="mb-0" style="font-size:13px; font-weight:700; color:#374151; cursor:pointer;">
                                            Enable Waiting List
                                        </label>
                                        <p class="mb-0" style="font-size:11px; color:#6b7280;">
                                            Students can join a waitlist when the event is full.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Issue certificate toggle --}}
                            <div style="padding:14px 16px; background:#f8fafc; border-radius:7px; border:1px solid #e5e7eb; margin-bottom:14px;">
                                <div class="d-flex align-items-center" style="gap:10px;">
                                    <input type="checkbox" name="issue_certificate" id="issueCert" value="1"
                                           style="width:16px; height:16px; accent-color:#2563eb; cursor:pointer; flex-shrink:0;">
                                    <div>
                                        <label for="issueCert" class="mb-0" style="font-size:13px; font-weight:700; color:#374151; cursor:pointer;">
                                            Issue Participation Certificate
                                        </label>
                                        <p class="mb-0" style="font-size:11px; color:#6b7280;">
                                            Automatically issue a certificate to each attendee when attendance is saved.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Certificate template selector — shown only when issueCert is checked --}}
                            <div id="certTemplateWrapper" style="display:none;">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Certificate Template <span style="color:#dc2626;">*</span>
                                </label>
                                @if($certTemplates->isEmpty())
                                <div style="padding:12px 14px; border-radius:7px; border:1px solid #fde68a; background:#fffbeb;">
                                    <p class="mb-0" style="font-size:12px; font-weight:700; color:#92400e;">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        No certificate templates found.
                                        <a href="{{ route('admin.certificate.template.create') }}" style="color:#2563eb; font-weight:800;">Create a template</a> first.
                                    </p>
                                </div>
                                @else
                                <select name="certificate_template_id" id="certTemplateSelect" class="form-control select2-cert"
                                        style="border-radius:6px; border-color:#d1d5db; font-size:13px; width:100%;">
                                    <option value="">— Select Certificate Template —</option>
                                    @foreach($certTemplates as $tpl)
                                    <option value="{{ $tpl->id }}" {{ old('certificate_template_id') == $tpl->id ? 'selected' : '' }}>
                                        {{ $tpl->name }}
                                        @if($tpl->type) ({{ ucfirst($tpl->type) }}) @endif
                                        @if($tpl->is_default) ★ @endif
                                    </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- Sessions --}}
                    <div class="dashboard-panel mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-day"></i>
                                Sessions
                            </h3>
                            <div class="card-tools">
                                <button type="button" id="addSession" class="panel-action"
                                        style="font-size:12px; color:#059669; border-color:#a7f3d0;">
                                    <i class="fas fa-plus"></i> Add Session
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="padding:18px 22px;">
                            <p style="font-size:12px; color:#6b7280; margin-bottom:14px; font-weight:700;">
                                Add one or more date/time sessions for this event.
                            </p>
                            <div id="sessionsContainer">
                                <div class="session-row d-flex flex-wrap align-items-center mb-2" style="gap:8px;">
                                    <input type="text" name="sessions[0][title]" class="form-control"
                                           placeholder="Session title"
                                           style="border-radius:6px; border-color:#d1d5db; font-size:13px; flex:2; min-width:150px;">
                                    <input type="datetime-local" name="sessions[0][starts_at]" class="form-control"
                                           style="border-radius:6px; border-color:#d1d5db; font-size:13px; flex:2; min-width:180px;">
                                    <input type="datetime-local" name="sessions[0][ends_at]" class="form-control"
                                           style="border-radius:6px; border-color:#d1d5db; font-size:13px; flex:2; min-width:180px;">
                                    <span style="flex:0 0 28px; width:28px;"></span>{{-- placeholder for remove button column alignment --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form actions --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                        <a href="{{ route('admin.event.index') }}" class="panel-action">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary"
                                style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 24px;">
                            <i class="fas fa-calendar-plus mr-1"></i> Create Event
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(function () {

    // ── Certificate template Select2 ─────────────────────────────
    if ($('.select2-cert').length) {
        $('.select2-cert').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: '— Select Certificate Template —',
            allowClear: true
        });
    }

    function toggleCertTemplate() {
        var $wrapper = $('#certTemplateWrapper');
        if ($('#issueCert').is(':checked')) {
            $wrapper.show();
        } else {
            $wrapper.hide();
            if ($('.select2-cert').length) {
                $('.select2-cert').val(null).trigger('change');
            }
        }
    }

    $('#issueCert').on('change', toggleCertTemplate);
    toggleCertTemplate(); // run on page load

    // ── Dynamic sessions ──────────────────────────────────────────
    var sessionCount = 1;

    $('#addSession').on('click', function () {
        var sc = sessionCount;
        var row = $('<div class="session-row d-flex flex-wrap align-items-center mb-2" style="gap:8px;"></div>');
        row.append('<input type="text"          name="sessions[' + sc + '][title]"     class="form-control" placeholder="Session title"   style="border-radius:6px; border-color:#d1d5db; font-size:13px; flex:2; min-width:150px;">');
        row.append('<input type="datetime-local" name="sessions[' + sc + '][starts_at]" class="form-control"                               style="border-radius:6px; border-color:#d1d5db; font-size:13px; flex:2; min-width:180px;">');
        row.append('<input type="datetime-local" name="sessions[' + sc + '][ends_at]"   class="form-control"                               style="border-radius:6px; border-color:#d1d5db; font-size:13px; flex:2; min-width:180px;">');
        row.append('<button type="button" class="remove-session panel-action" style="font-size:11px; padding:4px 8px; min-height:28px; color:#dc2626; border-color:#fecaca; flex-shrink:0;"><i class="fas fa-times"></i></button>');
        $('#sessionsContainer').append(row);
        sessionCount++;
    });

    $(document).on('click', '.remove-session', function () {
        $(this).closest('.session-row').remove();
    });

});
</script>
@endsection
