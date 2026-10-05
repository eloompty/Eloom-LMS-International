@extends('student::student.layouts.master')
@section('title', 'Student | My Certificates')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>My Certificates</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Certificates</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @if($certificates->isEmpty())
        <div class="dashboard-panel">
            <div class="dashboard-empty">
                <i class="fas fa-certificate"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No certificates issued yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Certificates will appear here once they have been issued to you.</p>
            </div>
        </div>
        @else
        <div class="dashboard-panel mb-0">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-certificate"></i>
                    Issued Certificates
                </h3>
                <div class="card-tools">
                    <span style="font-size:12px; font-weight:700; color:#6b7280;">
                        {{ $certificates->count() }} {{ Str::plural('certificate', $certificates->count()) }}
                    </span>
                </div>
            </div>
            <div class="card-body" style="padding:20px;">
                <div class="row">
                    @foreach($certificates as $cert)
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <div style="border:1px solid #e5e7eb; border-radius:8px; background:#fff; overflow:hidden; height:100%; display:flex; flex-direction:column; box-shadow:0 4px 14px rgba(15,23,42,.05); transition:box-shadow .2s, transform .2s;">
                            {{-- Cert banner --}}
                            <div style="background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 100%); padding:22px 20px; display:flex; align-items:center; gap:14px;">
                                <div style="width:44px; height:44px; border-radius:50%; background:rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="fas fa-award" style="color:#fff; font-size:20px;"></i>
                                </div>
                                <div style="min-width:0;">
                                    <p class="mb-0" style="font-size:14px; font-weight:800; color:#fff; line-height:1.3; word-break:break-word;">
                                        {{ optional($cert->template)->name ?? 'Certificate' }}
                                    </p>
                                    <span class="badge badge-light mt-1"
                                          style="font-size:10px; font-weight:800; border-radius:999px; color:#1e3a8a;">
                                        {{ ucfirst(optional($cert->template)->type ?? 'achievement') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Cert meta --}}
                            <div style="padding:14px 16px; flex:1;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <i class="fas fa-calendar-check" style="color:#059669; font-size:12px;"></i>
                                    <span style="font-size:12px; font-weight:700; color:#374151;">
                                        Issued: {{ $cert->issued_date }}
                                    </span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div style="padding:12px 16px; border-top:1px solid #f1f5f9; display:flex; gap:8px;">
                                <a href="{{ route('student.certificate.download', $cert->uuid) }}"
                                   class="btn btn-primary btn-sm flex-fill"
                                   style="font-weight:800; font-size:12px; border-radius:6px; text-align:center;">
                                    <i class="fas fa-download mr-1"></i> Download PDF
                                </a>
                                <a href="{{ route('certificate.verify', $cert->uuid) }}"
                                   class="panel-action"
                                   style="font-size:11px; padding:4px 10px; min-height:28px;"
                                   target="_blank">
                                    <i class="fas fa-shield-alt"></i> Verify
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
