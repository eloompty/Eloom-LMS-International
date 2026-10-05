@extends('user::layouts.master')
@section('title', 'Admin | Survey Results')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Survey Results</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.survey.instance.index') }}">Dispatched Surveys</a></li>
                    <li class="breadcrumb-item active">Results</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        {{-- Summary panel --}}
        <div class="dashboard-panel mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    {{ optional($instance->template)->name }}
                </h3>
                <div class="card-tools d-flex align-items-center" style="gap:10px;">
                    <div class="metric-card" style="min-height:auto; padding:8px 16px; box-shadow:none; border-color:#dbeafe;">
                        <div class="metric-icon metric-blue" style="width:32px; height:32px; font-size:13px; border-radius:6px; flex:0 0 32px;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="metric-content" style="padding-left:10px;">
                            <span class="metric-label" style="font-size:10px;">Responses</span>
                            <span class="metric-value" style="font-size:20px;">{{ $responses->count() }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.survey.instance.index') }}" class="panel-action">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        {{-- Per-question results --}}
        @forelse($aggregated as $qId => $agg)
        @php
            $typeBadge = ['text' => 'secondary', 'likert' => 'info', 'rating' => 'primary', 'nps' => 'warning', 'mcq' => 'success'][$agg['type']] ?? 'secondary';
        @endphp
        <div class="dashboard-panel mb-3">
            <div class="card-header" style="flex-wrap:wrap; gap:8px;">
                <h3 class="card-title" style="min-width:0; flex:1; flex-wrap:wrap; white-space:normal;">
                    <span class="badge badge-{{ $typeBadge }}"
                          style="font-size:10px; font-weight:800; border-radius:999px; padding:0.25rem 0.6rem; margin-right:6px; text-transform:uppercase; vertical-align:middle;">
                        {{ $agg['type'] }}
                    </span>
                    {{ $agg['question'] }}
                </h3>
                <div class="card-tools d-flex align-items-center flex-shrink-0" style="gap:12px;">
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        {{ $agg['count'] }} {{ Str::plural('response', $agg['count']) }}
                    </span>
                    @if($agg['avg'] !== null)
                    <span style="font-size:12px; font-weight:800; color:#2563eb;">
                        Avg: {{ $agg['avg'] }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="card-body" style="padding:18px 20px;">
                @if($agg['type'] === 'text')
                {{-- Text responses list --}}
                @if(count($agg['answers']) === 0)
                <p style="font-size:13px; color:#94a3b8; margin:0;">No text responses yet.</p>
                @else
                <ul style="margin:0; padding-left:18px; display:flex; flex-direction:column; gap:6px;">
                    @foreach($agg['answers'] as $ans)
                    <li style="font-size:13px; color:#374151; line-height:1.6;">{{ $ans }}</li>
                    @endforeach
                </ul>
                @endif

                @else
                {{-- Bar chart for numeric/MCQ --}}
                @php $counts = $agg['answers']->countBy()->sortKeys(); @endphp
                @if($counts->isEmpty())
                <p style="font-size:13px; color:#94a3b8; margin:0;">No responses yet.</p>
                @else
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach($counts as $val => $cnt)
                    @php $pct = $agg['count'] ? round($cnt / $agg['count'] * 100) : 0; @endphp
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <span style="min-width:60px; font-size:12px; font-weight:800; color:#374151; text-align:right;">{{ $val }}</span>
                        <div class="progress flex-grow-1" style="height:18px; border-radius:4px; background:#e5e7eb;">
                            <div class="progress-bar bg-primary gb-bar"
                                 data-pct="{{ $pct }}"
                                 style="border-radius:4px; width:0; transition:width .4s ease;">
                            </div>
                        </div>
                        <span style="min-width:50px; font-size:12px; font-weight:700; color:#6b7280;">
                            {{ $cnt }} ({{ $pct }}%)
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
                @endif
            </div>
        </div>
        @empty
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-chart-bar"></i>
                <p class="mb-0" style="font-weight:700; font-size:14px; color:#374151;">No results to display yet.</p>
            </div>
        </div>
        @endforelse

    </div>
</section>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.gb-bar').forEach(function(el) {
        el.style.width = (el.dataset.pct || 0) + '%';
    });
</script>
@endsection
