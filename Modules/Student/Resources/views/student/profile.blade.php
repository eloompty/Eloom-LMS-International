@extends('student::student.layouts.master')
@section('title', 'Student | Profile')

@section('content')
@php $user = Auth::guard('student')->user(); @endphp
<style>
    /* ── Profile Card ── */
    .profile-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(15,23,42,.07);
        background: #fff;
    }
    .profile-card-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 28px 20px 22px;
        background: linear-gradient(160deg, #eff6ff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e5e7eb;
        text-align: center;
    }
    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 4px 16px rgba(15,23,42,.14);
        margin-bottom: 12px;
    }
    .profile-name {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 8px;
        line-height: 1.3;
    }
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #eff6ff;
        color: #1d4ed8;
        letter-spacing: .3px;
    }

    /* ── Contact Info List ── */
    .profile-meta-list { list-style: none; margin: 0; padding: 0; }
    .profile-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 18px;
        border-bottom: 1px solid #f1f5f9;
    }
    .profile-meta-item:last-child { border-bottom: none; }
    .meta-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .meta-label {
        font-size: 10px;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 2px;
    }
    .meta-value {
        font-size: 13px;
        font-weight: 600;
        color: #111827;
        word-break: break-word;
        line-height: 1.4;
    }
    .meta-value-empty { color: #9ca3af; font-weight: 400; }

    /* ── Detail Section ── */
    .detail-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(15,23,42,.06);
        background: #fff;
    }
    .detail-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-bottom: 1px solid #e5e7eb;
    }
    .detail-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .title-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }
    .title-icon-blue { background: #eff6ff; color: #2563eb; }

    /* ── Detail Grid ── */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-top: 1px solid #f1f5f9;
        border-left: 1px solid #f1f5f9;
    }
    .detail-field {
        padding: 16px 20px;
        border-right: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }
    .detail-field-label {
        font-size: 11px;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 5px;
    }
    .detail-field-value {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        word-break: break-word;
    }
    .detail-field-empty { color: #9ca3af; font-weight: 400; font-style: italic; }

    @media (max-width: 575.98px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>

@if ($text = Session::get('success'))
<div class="alert alert-success mx-3 mt-3" style="border-radius:8px;border:1px solid #bbf7d0;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-check-circle mr-2"></i><strong>{{ $text }}</strong>
</div>
@endif

<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center py-1">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:20px;font-weight:800;color:#111827;line-height:1.2;">Profile</h1>
                <p class="m-0 mt-1" style="font-size:13px;color:#6b7280;">Your account information</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0" style="background:none;padding:0;">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" style="color:#2563eb;">Home</a></li>
                    <li class="breadcrumb-item active" style="color:#6b7280;">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">

            <!-- Profile Sidebar -->
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="profile-card">
                    <div class="profile-card-hero">
                        <img class="profile-avatar"
                             src="{{ asset($user->image) }}"
                             alt="{{ userName('Student', $user->id) }}">
                        <h2 class="profile-name">{{ userName('Student', $user->id) }}</h2>
                        <span class="role-badge"><i class="fas fa-user-graduate"></i> Student</span>
                    </div>
                    <ul class="profile-meta-list">
                        <li class="profile-meta-item">
                            <span class="meta-icon"><i class="fas fa-envelope"></i></span>
                            <div>
                                <div class="meta-label">Email</div>
                                <div class="meta-value {{ !$user->email ? 'meta-value-empty' : '' }}">
                                    {{ $user->email ?: 'Not provided' }}
                                </div>
                            </div>
                        </li>
                        <li class="profile-meta-item">
                            <span class="meta-icon"><i class="fas fa-phone-alt"></i></span>
                            <div>
                                <div class="meta-label">Phone</div>
                                <div class="meta-value {{ !$user->phone ? 'meta-value-empty' : '' }}">
                                    {{ $user->phone ?: 'Not provided' }}
                                </div>
                            </div>
                        </li>
                        <li class="profile-meta-item">
                            <span class="meta-icon"><i class="fas fa-mobile-alt"></i></span>
                            <div>
                                <div class="meta-label">Mobile</div>
                                <div class="meta-value {{ !$user->mobile ? 'meta-value-empty' : '' }}">
                                    {{ $user->mobile ?: 'Not provided' }}
                                </div>
                            </div>
                        </li>
                        <li class="profile-meta-item">
                            <span class="meta-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <div>
                                <div class="meta-label">Address</div>
                                @php $addr = fullAddress('Student', $user->id); @endphp
                                <div class="meta-value {{ !$addr ? 'meta-value-empty' : '' }}">
                                    {{ $addr ?: 'Not provided' }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="col-md-8 col-lg-9">
                <div class="detail-card">
                    <div class="detail-card-header">
                        <span class="title-icon title-icon-blue"><i class="fas fa-id-card"></i></span>
                        <h3 class="detail-card-title">Personal Information</h3>
                    </div>
                    <div class="detail-grid">
                        <div class="detail-field">
                            <div class="detail-field-label">Salutation</div>
                            <div class="detail-field-value {{ !$user->salutation ? 'detail-field-empty' : '' }}">
                                {{ $user->salutation ?: '—' }}
                            </div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-field-label">First Name</div>
                            <div class="detail-field-value {{ !$user->first_name ? 'detail-field-empty' : '' }}">
                                {{ $user->first_name ?: '—' }}
                            </div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-field-label">Family Name</div>
                            <div class="detail-field-value {{ !$user->family_name ? 'detail-field-empty' : '' }}">
                                {{ $user->family_name ?: '—' }}
                            </div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-field-label">Email Address</div>
                            <div class="detail-field-value {{ !$user->email ? 'detail-field-empty' : '' }}">
                                {{ $user->email ?: '—' }}
                            </div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-field-label">Phone</div>
                            <div class="detail-field-value {{ !$user->phone ? 'detail-field-empty' : '' }}">
                                {{ $user->phone ?: '—' }}
                            </div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-field-label">Mobile</div>
                            <div class="detail-field-value {{ !$user->mobile ? 'detail-field-empty' : '' }}">
                                {{ $user->mobile ?: '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
