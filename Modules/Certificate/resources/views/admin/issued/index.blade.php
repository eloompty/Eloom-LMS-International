@extends('user::layouts.master')
@section('title', 'Admin | Issued Certificates')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Issued Certificates</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Issued Certificates</li>
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
                    <i class="fas fa-certificate"></i>
                    All Issued Certificates
                </h3>
                <div class="card-tools d-flex" style="gap:8px;">
                    <a href="{{ route('admin.certificate.template.index') }}" class="panel-action">
                        <i class="fas fa-layer-group"></i> Templates
                    </a>
                    <a href="{{ route('admin.certificate.issued.create') }}" class="btn btn-primary btn-sm"
                       style="font-weight:800; font-size:12px; border-radius:6px; padding:5px 14px;">
                        <i class="fas fa-plus mr-1"></i> Issue Certificate
                    </a>
                </div>
            </div>

            @if($certificates->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-certificate"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No certificates issued yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">
                    <a href="{{ route('admin.certificate.issued.create') }}" style="color:#2563eb; font-weight:700;">Issue a certificate</a> to a student.
                </p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Student</th>
                            <th>Template</th>
                            <th style="width:120px;">Type</th>
                            <th style="width:120px;">Issued Date</th>
                            <th class="text-center" style="width:100px;">Status</th>
                            <th class="text-center" style="width:200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificates as $cert)
                        <tr style="{{ $cert->revoked ? 'opacity:.6;' : '' }}">
                            <td style="color:#94a3b8; font-weight:700;">{{ $cert->id }}</td>
                            <td style="font-weight:700; font-size:13px;">
                                {{ trim(optional($cert->student)->first_name . ' ' . optional($cert->student)->last_name) }}
                            </td>
                            <td style="font-size:13px;">{{ optional($cert->template)->name }}</td>
                            <td>
                                <span class="badge badge-info"
                                      style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">
                                    {{ optional($cert->template)->type }}
                                </span>
                            </td>
                            <td style="font-size:12px; color:#374151;">{{ $cert->issued_date }}</td>
                            <td class="text-center">
                                @if($cert->revoked)
                                <span class="badge badge-danger"
                                      style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">
                                    Revoked
                                </span>
                                @else
                                <span class="badge badge-success"
                                      style="font-size:11px; font-weight:800; padding:0.35rem 0.65rem; border-radius:999px;">
                                    <i class="fas fa-check mr-1"></i>Valid
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center flex-wrap" style="gap:5px;">
                                    <a href="{{ route('admin.certificate.issued.download', $cert->id) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px;"
                                       target="_blank">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                    <a href="{{ route('certificate.verify', $cert->uuid) }}"
                                       class="panel-action"
                                       style="font-size:11px; padding:4px 8px; min-height:28px; color:#0891b2; border-color:#bae6fd;"
                                       target="_blank">
                                        <i class="fas fa-shield-alt"></i> Verify
                                    </a>
                                    @unless($cert->revoked)
                                    <button type="button"
                                            class="panel-action"
                                            style="font-size:11px; padding:4px 8px; min-height:28px; color:#dc2626; border-color:#fecaca; background:#fff; cursor:pointer;"
                                            data-toggle="modal"
                                            data-target="#revokeModal{{ $cert->id }}">
                                        <i class="fas fa-ban"></i> Revoke
                                    </button>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer" style="background:#f8fafc; border-top:1px solid #e5e7eb; padding:12px 16px;">
                {{ $certificates->links() }}
            </div>
            @endif
        </div>

    </div>
</section>

{{-- Revoke modals --}}
@foreach($certificates as $cert)
@unless($cert->revoked)
<div class="modal fade" id="revokeModal{{ $cert->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 20px 60px rgba(15,23,42,.15);">
            <div class="modal-header" style="border-bottom:1px solid #e5e7eb; padding:16px 20px;">
                <h5 class="modal-title" style="font-weight:800; font-size:15px; display:flex; align-items:center; gap:9px; margin:0;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; background:#fee2e2; border-radius:6px; color:#dc2626;">
                        <i class="fas fa-ban" style="font-size:12px;"></i>
                    </span>
                    Revoke Certificate
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="opacity:.5;"><span>&times;</span></button>
            </div>
            <form method="POST" action="{{ route('admin.certificate.issued.revoke', $cert->id) }}">
                @csrf
                <div class="modal-body" style="padding:20px;">
                    <p style="font-size:13px; color:#374151; margin-bottom:14px;">
                        Revoking <strong>{{ trim(optional($cert->student)->first_name . ' ' . optional($cert->student)->last_name) }}</strong>'s
                        <strong>{{ optional($cert->template)->name }}</strong> certificate. This action cannot be undone.
                    </p>
                    <div class="form-group mb-0">
                        <label style="font-size:11px; font-weight:800; color:#374151; text-transform:uppercase; margin-bottom:6px; display:block;">
                            Reason <span style="color:#dc2626;">*</span>
                        </label>
                        <textarea name="reason" class="form-control" rows="3" required
                                  placeholder="Explain why this certificate is being revoked..."
                                  style="border-radius:6px; border-color:#d1d5db; font-size:13px; resize:vertical;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e5e7eb; padding:14px 20px; display:flex; justify-content:flex-end; gap:8px;">
                    <button type="button" class="panel-action" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm"
                            style="font-weight:800; font-size:13px; border-radius:6px; padding:7px 16px;">
                        <i class="fas fa-ban mr-1"></i> Revoke Certificate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endunless
@endforeach
@endsection
