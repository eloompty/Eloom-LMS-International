@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Profile')

@section('content')
@php $user = Auth::guard('trainer')->user(); @endphp
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
        background: linear-gradient(160deg, #f0fdf4 0%, #f8fafc 100%);
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
        background: #f0fdf4;
        color: #166534;
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
        background: #f0fdf4;
        color: #16a34a;
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
    .meta-value-empty { color: #9ca3af; font-weight: 400; font-style: italic; }

    /* ── Tabs Panel ── */
    .tabs-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(15,23,42,.06);
        background: #fff;
    }
    .tabs-nav {
        display: flex;
        background: #fff;
        border-bottom: 2px solid #e5e7eb;
        padding: 0 6px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .tabs-nav::-webkit-scrollbar { display: none; }
    .tabs-nav-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 13px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: color .15s, border-color .15s;
        flex-shrink: 0;
    }
    .tabs-nav-item:hover { color: #2563eb; text-decoration: none; }
    .tabs-nav-item.active { color: #2563eb; border-bottom-color: #2563eb; }
    .tabs-nav-item .tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 9px;
        background: #e5e7eb;
        color: #374151;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;
    }
    .tabs-nav-item.active .tab-count { background: #dbeafe; color: #1d4ed8; }

    /* ── Data Tables ── */
    .profile-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .profile-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    .profile-table thead th {
        background: #f8fafc;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #6b7280;
        padding: 10px 16px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .profile-table tbody td {
        font-size: 13px;
        color: #374151;
        padding: 11px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .profile-table tbody tr:last-child td { border-bottom: none; }
    .profile-table tbody tr:hover { background: #f9fafb; }

    /* ── Status Badges ── */
    .badge-status {
        display: inline-block;
        padding: 2px 9px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-active   { background: #f0fdf4; color: #166534; }
    .badge-inactive { background: #fef3c7; color: #92400e; }
    .badge-deleted  { background: #fee2e2; color: #991b1b; }

    /* ── Empty State ── */
    .profile-empty {
        padding: 48px 20px;
        text-align: center;
        color: #9ca3af;
    }
    .profile-empty i { font-size: 36px; opacity: .25; display: block; margin-bottom: 10px; }
    .profile-empty p { font-size: 13px; margin: 0; }

    /* ── Title icon (shared) ── */
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
    .title-icon-green { background: #f0fdf4; color: #16a34a; }
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
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}" style="color:#2563eb;">Home</a></li>
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
                             alt="{{ userName('Trainer', $user->id) }}">
                        <h2 class="profile-name">{{ userName('Trainer', $user->id) }}</h2>
                        <span class="role-badge"><i class="fas fa-chalkboard-teacher"></i> Faculty</span>
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
                            <span class="meta-icon"><i class="fas fa-globe"></i></span>
                            <div>
                                <div class="meta-label">Country</div>
                                <div class="meta-value">
                                    {{ addressCountryName(optional($user->address)->country_id) ?: 'Not provided' }}
                                </div>
                            </div>
                        </li>
                        <li class="profile-meta-item">
                            <span class="meta-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <div>
                                <div class="meta-label">Location</div>
                                @php $addr = fullAddress('Trainer', $user->id); @endphp
                                <div class="meta-value {{ !$addr ? 'meta-value-empty' : '' }}">
                                    {{ $addr ?: 'Not provided' }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tabs Panel -->
            <div class="col-md-8 col-lg-9">
                <div class="tabs-card">

                    <!-- Tab Navigation -->
                    <nav class="tabs-nav">
                        <a class="tabs-nav-item active"
                           href="#qualification"
                           data-toggle="tab"
                           role="tab">
                            <i class="fas fa-certificate" style="font-size:12px;"></i>
                            Qualifications
                            <span class="tab-count">{{ count($qualifications) }}</span>
                        </a>
                        <a class="tabs-nav-item"
                           href="#profession"
                           data-toggle="tab"
                           role="tab">
                            <i class="fas fa-briefcase" style="font-size:12px;"></i>
                            Professional Development
                            <span class="tab-count">{{ count($professions) }}</span>
                        </a>
                        <a class="tabs-nav-item"
                           href="#workplacement"
                           data-toggle="tab"
                           role="tab">
                            <i class="fas fa-building" style="font-size:12px;"></i>
                            Work Placements
                            <span class="tab-count">{{ count($works) }}</span>
                        </a>
                    </nav>

                    <!-- Tab Content -->
                    <div class="tab-content">

                        <!-- Qualifications -->
                        <div class="tab-pane active" id="qualification" role="tabpanel">
                            @if(count($qualifications) > 0)
                            <div class="profile-table-wrap">
                                <table class="profile-table">
                                    <thead>
                                        <tr>
                                            <th>Qualification Name</th>
                                            <th>Awarding Institution</th>
                                            <th>Year</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($qualifications as $value)
                                        <tr>
                                            <td style="font-weight:600;color:#111827;">{{ $value->name }}</td>
                                            <td>{{ $value->award_university }}</td>
                                            <td>{{ $value->award_year }}</td>
                                            <td>{{ $value->country->name }}</td>
                                            <td>
                                                @if ($value->status == 1)
                                                <span class="badge-status badge-active">Active</span>
                                                @elseif ($value->status == 0)
                                                <span class="badge-status badge-inactive">Inactive</span>
                                                @else
                                                <span class="badge-status badge-deleted">Deleted</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="profile-empty">
                                <i class="fas fa-certificate"></i>
                                <p>No qualifications on record</p>
                            </div>
                            @endif
                        </div>

                        <!-- Professional Development -->
                        <div class="tab-pane" id="profession" role="tabpanel">
                            @if(count($professions) > 0)
                            <div class="profile-table-wrap">
                                <table class="profile-table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Duration</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($professions as $value)
                                        <tr>
                                            <td style="font-weight:600;color:#111827;">{{ $value->title }}</td>
                                            <td>{{ $value->duration }}</td>
                                            <td>{{ $value->start_date }}</td>
                                            <td>{{ $value->end_date }}</td>
                                            <td>
                                                @if ($value->status == 1)
                                                <span class="badge-status badge-active">Active</span>
                                                @elseif ($value->status == 0)
                                                <span class="badge-status badge-inactive">Inactive</span>
                                                @else
                                                <span class="badge-status badge-deleted">Deleted</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="profile-empty">
                                <i class="fas fa-briefcase"></i>
                                <p>No professional development records on file</p>
                            </div>
                            @endif
                        </div>

                        <!-- Work Placements -->
                        <div class="tab-pane" id="workplacement" role="tabpanel">
                            @if(count($works) > 0)
                            <div class="profile-table-wrap">
                                <table class="profile-table">
                                    <thead>
                                        <tr>
                                            <th>Company</th>
                                            <th>Contact Person</th>
                                            <th>Mobile</th>
                                            <th>Position</th>
                                            <th>Hours</th>
                                            <th>Site</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($works as $value)
                                        <tr>
                                            <td style="font-weight:600;color:#111827;white-space:nowrap;">{{ $value->placement_company_name }}</td>
                                            <td>{{ $value->contact_person }}</td>
                                            <td>{{ $value->contact_person_mobile }}</td>
                                            <td>{{ $value->position }}</td>
                                            <td>{{ $value->placement_hours }}</td>
                                            <td>{{ $value->site_name }}</td>
                                            <td style="white-space:nowrap;">{{ $value->starting_date }}</td>
                                            <td style="white-space:nowrap;">{{ $value->ending_date }}</td>
                                            <td>
                                                @if ($value->status == 1)
                                                <span class="badge-status badge-active">Active</span>
                                                @elseif ($value->status == 0)
                                                <span class="badge-status badge-inactive">Inactive</span>
                                                @else
                                                <span class="badge-status badge-deleted">Deleted</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="profile-empty">
                                <i class="fas fa-building"></i>
                                <p>No work placement records on file</p>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
