@extends('user::layouts.master')
@section('title', 'Admin | Dispatched Surveys')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Dispatched Surveys</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.survey.template.index') }}">Survey Templates</a></li>
                    <li class="breadcrumb-item active">Dispatched</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="dashboard-panel">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-paper-plane"></i>
                    Dispatched Surveys
                </h3>
                <div class="card-tools d-flex" style="gap:8px;">
                    <a href="{{ route('admin.survey.template.index') }}" class="panel-action">
                        <i class="fas fa-poll"></i> Templates
                    </a>
                    <a href="{{ route('admin.survey.instance.create') }}" class="btn btn-primary btn-sm"
                       style="font-weight:800; font-size:12px; border-radius:6px; padding:5px 14px;">
                        <i class="fas fa-plus mr-1"></i> Dispatch Survey
                    </a>
                </div>
            </div>

            @if($instances->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-paper-plane"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No surveys dispatched yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">
                    <a href="{{ route('admin.survey.instance.create') }}" style="color:#2563eb; font-weight:700;">Dispatch a survey</a> to collect responses from students.
                </p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Template</th>
                            <th style="width:160px;">Target</th>
                            <th style="width:150px;">Dispatch</th>
                            <th style="width:120px;">Closes</th>
                            <th class="text-center" style="width:100px;">Responses</th>
                            <th class="text-center" style="width:100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instances as $inst)
                        @php
                            $isOpen   = $inst->isOpen();
                            $respCnt  = $inst->responses->count();
                        @endphp
                        <tr>
                            <td style="color:#94a3b8; font-weight:700;">{{ $inst->id }}</td>
                            <td style="font-weight:700; font-size:13px;">
                                {{ optional($inst->template)->name }}
                            </td>
                            <td>
                                <span class="badge badge-secondary"
                                      style="font-size:11px; font-weight:800; padding:0.3rem 0.6rem; border-radius:999px;">
                                    {{ ucfirst($inst->target_type) }}
                                </span>
                                @if($inst->target_id)
                                <span style="font-size:11px; color:#6b7280;">#{{ $inst->target_id }}</span>
                                @endif
                            </td>
                            <td style="font-size:12px; color:#374151;">
                                {{ $inst->dispatch_at ? $inst->dispatch_at->format('d M Y H:i') : 'Immediate' }}
                            </td>
                            <td style="font-size:12px;">
                                @if($inst->closes_at)
                                    @if($isOpen)
                                    <span style="color:#d97706; font-weight:700;">
                                        <i class="fas fa-clock mr-1"></i>{{ $inst->closes_at->format('d M Y') }}
                                    </span>
                                    @else
                                    <span style="color:#dc2626; font-weight:700;">
                                        <i class="fas fa-lock mr-1"></i>{{ $inst->closes_at->format('d M Y') }}
                                    </span>
                                    @endif
                                @else
                                <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span style="font-size:13px; font-weight:800; color:#374151;">{{ $respCnt }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.survey.results', $inst->id) }}"
                                   class="panel-action"
                                   style="font-size:11px; padding:4px 10px; min-height:28px; color:#0891b2; border-color:#bae6fd;">
                                    <i class="fas fa-chart-bar"></i> Results
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
