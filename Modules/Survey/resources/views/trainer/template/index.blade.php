@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Survey Templates')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Survey Templates</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Survey Templates</li>
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
                    <i class="fas fa-poll"></i>
                    My Survey Templates
                </h3>
                <div class="card-tools">
                    <a href="{{ route('trainer.survey.template.create') }}" class="btn btn-primary btn-sm"
                       style="font-weight:800; font-size:12px; border-radius:6px; padding:5px 14px;">
                        <i class="fas fa-plus mr-1"></i> New Template
                    </a>
                </div>
            </div>

            @if($templates->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-poll"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No survey templates yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">
                    <a href="{{ route('trainer.survey.template.create') }}" style="color:#2563eb; font-weight:700;">Create your first template</a> to start collecting student feedback.
                </p>
            </div>
            @else
            @foreach($templates as $t)
            <div class="d-flex align-items-start flex-wrap {{ !$loop->last ? 'tpl-row-border' : '' }}"
                 style="padding:16px 20px; gap:14px;">

                {{-- Template info --}}
                <div style="flex:1; min-width:180px;">
                    <p class="mb-0" style="font-size:14px; font-weight:800; color:#111827;">{{ $t->name }}</p>
                    @if($t->description)
                    <p class="mb-0 mt-1" style="font-size:12px; color:#6b7280; font-weight:700;">{{ $t->description }}</p>
                    @endif
                    <div class="d-flex align-items-center mt-1" style="gap:8px;">
                        @if($t->is_anonymous)
                        <span class="badge badge-info"
                              style="font-size:10px; font-weight:800; border-radius:999px; padding:2px 8px;">
                            Anonymous
                        </span>
                        @endif
                        <span style="font-size:11px; font-weight:700; color:#94a3b8;">
                            {{ ucfirst($t->created_by_type) }}
                        </span>
                    </div>
                </div>

                {{-- Quick dispatch form --}}
                <form method="POST" action="{{ route('trainer.survey.dispatch') }}"
                      class="dispatch-form d-flex flex-wrap align-items-center"
                      style="gap:6px; flex-shrink:0;">
                    @csrf
                    <input type="hidden" name="survey_template_id" value="{{ $t->id }}">

                    <select name="target_type" class="form-control form-control-sm target-type-sel"
                            style="border-radius:6px; border-color:#d1d5db; font-size:12px; width:150px;">
                        <option value="all">All My Students</option>
                        <option value="intake">Specific Intake</option>
                    </select>

                    <div class="intake-wrapper" style="display:none;">
                        <select name="target_id" class="intake-select2"
                                style="width:200px;">
                            <option value="">— Select Intake —</option>
                            @foreach($intakes as $intake)
                            <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                            @endforeach
                        </select>
                        @if($intakes->isEmpty())
                        <p class="mb-0 mt-1" style="font-size:11px; font-weight:700; color:#d97706;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>No intakes assigned to you yet.
                        </p>
                        @endif
                    </div>

                    <button type="submit" class="panel-action"
                            style="font-size:12px; color:#059669; border-color:#a7f3d0; padding:5px 12px; min-height:32px;">
                        <i class="fas fa-paper-plane"></i> Dispatch
                    </button>
                </form>

            </div>
            @endforeach
            @endif
        </div>

    </div>
</section>
@endsection

@section('header-script')
<style>
.tpl-row-border { border-bottom: 1px solid #f1f5f9; }
.intake-wrapper .select2-container { min-width: 200px; }
</style>
@endsection

@section('scripts')
<script>
$(function () {
    // Init Select2 on all intake dropdowns (hidden ones initialise fine)
    $('.intake-select2').select2({
        theme: 'bootstrap4',
        width: '200px',
        placeholder: '— Select Intake —',
        allowClear: true
    });

    // Show/hide intake dropdown when target type changes
    $(document).on('change', '.target-type-sel', function () {
        var wrapper = $(this).closest('.dispatch-form').find('.intake-wrapper');
        if ($(this).val() === 'intake') {
            wrapper.show();
        } else {
            wrapper.hide();
            wrapper.find('.intake-select2').val(null).trigger('change');
        }
    });
});
</script>
@endsection
