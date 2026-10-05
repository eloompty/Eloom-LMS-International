@extends('user::layouts.master')
@section('title', 'Admin | Alumni Directory')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Alumni Directory</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.alumni.index') }}">Alumni</a></li>
                    <li class="breadcrumb-item active">Directory</li>
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
                    <i class="fas fa-address-book"></i>
                    Opt-In Directory
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.alumni.index') }}" class="panel-action">
                        <i class="fas fa-arrow-left"></i> All Alumni
                    </a>
                </div>
            </div>

            @if($alumni->isEmpty())
            <div class="dashboard-empty">
                <i class="fas fa-address-book"></i>
                <p class="mb-1" style="font-weight:700; font-size:14px; color:#374151;">No alumni in the directory yet.</p>
                <p class="mb-0" style="font-size:13px; color:#6b7280;">Alumni who opt in to the directory will appear here.</p>
            </div>
            @else
            <div class="card-body" style="padding:20px;">
                <div class="row">
                    @foreach($alumni as $a)
                    @php
                        $name     = trim(optional($a->student)->first_name . ' ' . optional($a->student)->last_name);
                        $initials = collect(explode(' ', $name))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                        $role     = trim(($a->job_title ?? '') . ($a->job_title && $a->employer_name ? ' @ ' : '') . ($a->employer_name ?? ''));
                    @endphp
                    <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; overflow:hidden; text-align:center; box-shadow:0 4px 14px rgba(15,23,42,.05); height:100%; display:flex; flex-direction:column;">
                            {{-- Avatar header --}}
                            <div style="background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 100%); padding:22px 16px 14px;">
                                <div style="width:56px; height:56px; border-radius:50%; background:rgba(255,255,255,.2); border:2px solid rgba(255,255,255,.4); display:flex; align-items:center; justify-content:center; margin:0 auto 10px; font-size:20px; font-weight:800; color:#fff; letter-spacing:1px;">
                                    {{ $initials ?: '??' }}
                                </div>
                                <p style="margin:0; font-size:14px; font-weight:800; color:#fff; line-height:1.3;">{{ $name ?: 'Unknown' }}</p>
                            </div>
                            {{-- Details --}}
                            <div style="padding:14px 14px 10px; flex:1;">
                                @if($role)
                                <p style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px;">
                                    <i class="fas fa-briefcase mr-1" style="color:#6b7280; font-size:10px;"></i>{{ $role }}
                                </p>
                                @endif
                                @if($a->industry)
                                <p style="font-size:11px; font-weight:700; color:#6b7280; margin-bottom:0;">
                                    <i class="fas fa-building mr-1" style="font-size:10px;"></i>{{ $a->industry }}
                                </p>
                                @endif
                                @if(!$role && !$a->industry)
                                <p style="font-size:12px; color:#94a3b8; margin-bottom:0;">No employment info</p>
                                @endif
                            </div>
                            {{-- Footer --}}
                            <div style="padding:10px 14px 14px;">
                                <a href="{{ route('admin.alumni.show', $a->id) }}"
                                   class="btn btn-sm btn-block"
                                   style="font-weight:800; font-size:12px; border-radius:6px; border:1px solid #d1d5db; color:#374151; background:#f9fafb;">
                                    <i class="fas fa-eye mr-1"></i> View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
