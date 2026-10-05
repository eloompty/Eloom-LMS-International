@extends('user::layouts.master')
@section('title', 'Admin | Certificate Templates')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Certificate Templates</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.certificate.issued.index') }}">Certificates</a></li>
                    <li class="breadcrumb-item active">Templates</li>
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
                    <i class="fas fa-layer-group"></i>
                    Templates
                </h3>
                <div class="card-tools d-flex" style="gap:8px;">
                    <a href="{{ route('admin.certificate.issued.index') }}" class="panel-action">
                        <i class="fas fa-certificate"></i> Issued Certificates
                    </a>
                    <a href="{{ route('admin.certificate.template.create') }}" class="btn btn-primary btn-sm"
                       style="font-weight:800; font-size:12px; border-radius:6px; padding:5px 14px;">
                        <i class="fas fa-plus mr-1"></i> New Template
                    </a>
                </div>
            </div>

            @if($templates->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-layer-group"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No templates created yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">
                    <a href="{{ route('admin.certificate.template.create') }}" style="color:#2563eb; font-weight:700;">Create your first template</a> to start issuing certificates.
                </p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Name</th>
                            <th style="width:130px;">Type</th>
                            <th class="text-center" style="width:100px;">Default</th>
                            <th class="text-center" style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $t)
                        <tr>
                            <td style="color:#94a3b8; font-weight:700;">{{ $t->id }}</td>
                            <td style="font-weight:700; font-size:13px;">{{ $t->name }}</td>
                            <td>
                                <span class="badge badge-info"
                                      style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">
                                    {{ ucfirst($t->type) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($t->is_default)
                                <span class="badge badge-success"
                                      style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">
                                    <i class="fas fa-check mr-1"></i>Default
                                </span>
                                @else
                                <span style="color:#94a3b8; font-size:12px; font-weight:700;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center" style="gap:6px;">
                                    <a href="{{ route('admin.certificate.template.edit', $t->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 10px; min-height:28px; color:#d97706; border-color:#fde68a;">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.certificate.template.delete', $t->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 10px; min-height:28px; color:#dc2626; border-color:#fecaca;"
                                       onclick="return confirm('Delete this template? This cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
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
