@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Create Survey Template')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Create Survey Template</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.survey.template.index') }}">Survey Templates</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
                            <i class="fas fa-poll"></i>
                            New Survey Template
                        </h3>
                    </div>
                    <div class="card-body" style="padding:22px;">
                        <form method="POST" action="{{ route('trainer.survey.template.store') }}">
                            @csrf

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Template Name <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="name" class="form-control" required
                                       placeholder="e.g. End-of-Course Feedback"
                                       style="border-radius:6px; border-color:#d1d5db; font-size:13px;">
                            </div>

                            <div class="form-group">
                                <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                                    Description
                                </label>
                                <textarea name="description" class="form-control" rows="2"
                                          placeholder="Optional — shown to students before they start"
                                          style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;"></textarea>
                            </div>

                            <div style="padding:12px 14px; background:#f8fafc; border-radius:7px; border:1px solid #e5e7eb; margin-bottom:22px;">
                                <div class="d-flex align-items-center" style="gap:10px;">
                                    <input type="checkbox" name="is_anonymous" id="anon" value="1"
                                           style="width:16px; height:16px; accent-color:#2563eb; cursor:pointer;">
                                    <div>
                                        <label for="anon" class="mb-0" style="font-size:13px; font-weight:700; color:#374151; cursor:pointer;">
                                            Anonymous responses
                                        </label>
                                        <p class="mb-0" style="font-size:11px; color:#6b7280;">Student identities will not be linked to their answers.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Questions --}}
                            <div style="border-top:1px solid #e5e7eb; padding-top:18px; margin-bottom:14px;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <p class="mb-0" style="font-size:13px; font-weight:800; color:#111827;">
                                        <i class="fas fa-list-ol mr-1" style="color:#2563eb;"></i> Questions
                                    </p>
                                    <button type="button" id="addQ" class="panel-action"
                                            style="font-size:12px; color:#059669; border-color:#a7f3d0;">
                                        <i class="fas fa-plus"></i> Add Question
                                    </button>
                                </div>
                                <div id="questionsContainer">
                                    @include('survey::admin.template._question_row', ['idx' => 0])
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                                <a href="{{ route('trainer.survey.template.index') }}" class="panel-action">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary"
                                        style="font-weight:800; font-size:13px; border-radius:6px; padding:8px 22px;">
                                    <i class="fas fa-save mr-1"></i> Save Template
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

@section('scripts')
<script>
(function () {
    var qc = 1;
    document.getElementById('addQ').addEventListener('click', function () {
        var tpl = document.querySelector('.question-row').cloneNode(true);
        tpl.querySelectorAll('[name]').forEach(function (el) {
            el.setAttribute('name', el.getAttribute('name').replace(/\[\d+\]/, '[' + qc + ']'));
            if (el.type !== 'checkbox') el.value = '';
        });
        tpl.id = 'q-row-' + qc;
        document.getElementById('questionsContainer').appendChild(tpl);
        qc++;
    });
})();
</script>
@endsection
