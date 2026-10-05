@extends('user::layouts.master')
@section('title', 'Admin | Company Menu')
@php $userTheme = Auth::guard('user')->user()->theme ?? 'theme2'; @endphp

@section('header-script')
@if ($userTheme == 'theme3')
<style>
:root {
    --set-primary: #2563eb;
    --set-primary-light: #eff6ff;
    --set-success: #059669;
    --set-success-light: #ecfdf5;
    --set-warning: #d97706;
    --set-warning-light: #fffbeb;
    --set-danger: #dc2626;
    --set-danger-light: #fef2f2;
    --set-info: #0891b2;
    --set-info-light: #ecfeff;
    --set-secondary: #6b7280;
    --set-secondary-light: #f3f4f6;
    --set-gray-100: #f3f4f6;
    --set-gray-200: #e5e7eb;
    --set-gray-500: #6b7280;
    --set-gray-900: #111827;
    --set-radius: 12px;
    --set-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --set-shadow-md: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -1px rgba(0,0,0,.04);
}

.settings-header { margin-bottom: 32px; }
.settings-header h1 { font-size: 1.75rem; font-weight: 800; color: var(--set-gray-900); display: flex; align-items: center; gap: 12px; }
.settings-header h1 i { color: var(--set-primary); }

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
}

.setting-card {
    background: #fff;
    border-radius: var(--set-radius);
    border: 1px solid var(--set-gray-200);
    padding: 20px;
    text-decoration: none !important;
    transition: all 0.2s ease-in-out;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
    box-shadow: var(--set-shadow);
    position: relative;
    overflow: hidden;
    height: 100%;
}

.setting-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--set-shadow-md);
    border-color: var(--set-primary);
}

.setting-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: transform 0.2s;
}

.setting-card:hover .setting-icon { transform: scale(1.1); }

.setting-label {
    font-weight: 700;
    font-size: 1rem;
    color: var(--set-gray-900);
    line-height: 1.2;
}

.icon-primary   { background: var(--set-primary-light); color: var(--set-primary); }
.icon-success   { background: var(--set-success-light); color: var(--set-success); }
.icon-warning   { background: var(--set-warning-light); color: var(--set-warning); }
.icon-danger    { background: var(--set-danger-light);  color: var(--set-danger);  }
.icon-info      { background: var(--set-info-light);    color: var(--set-info);    }
.icon-secondary { background: var(--set-secondary-light); color: var(--set-secondary); }
.icon-purple    { background: #f5f3ff; color: #8b5cf6; }
.icon-dark      { background: #f1f5f9; color: #334155; }

.setting-card:has(.icon-primary)   .setting-label { color: var(--set-primary); }
.setting-card:has(.icon-success)   .setting-label { color: var(--set-success); }
.setting-card:has(.icon-warning)   .setting-label { color: var(--set-warning); }
.setting-card:has(.icon-danger)    .setting-label { color: var(--set-danger); }
.setting-card:has(.icon-info)      .setting-label { color: var(--set-info); }
.setting-card:has(.icon-secondary) .setting-label { color: var(--set-secondary); }
.setting-card:has(.icon-purple)    .setting-label { color: #8b5cf6; }
.setting-card:has(.icon-dark)      .setting-label { color: #334155; }

@media (max-width: 768px) {
    .settings-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
}
</style>
@endif
@endsection

@section('content')
@if ($userTheme != 'theme3')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Courses</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Courses Menu</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- ./row -->
        <div class="row">

            <!-- /.col -->
            <div class="col-md-12">
                <!-- Application buttons -->
                <div class="card">
                    <div class="my_btn_list">
                        @if (checkRole('company', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.company') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                                        </svg>
                                        Company
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('company_delivery_site', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.company.delivery.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 2H9c-1.103 0-2 .897-2 2v5.586l-4.707 4.707A1 1 0 0 0 3 16v5a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4c0-1.103-.897-2-2-2m-8 18H5v-5.586l3-3l3 3zm8 0h-6v-4a.999.999 0 0 0 .707-1.707L9 9.586V4h10z" />
                                            <path fill="currentColor" d="M11 6h2v2h-2zm4 0h2v2h-2zm0 4.031h2V12h-2zM15 14h2v2h-2zm-8 1h2v2H7z" />
                                        </svg>
                                        Company Delivery Sites
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /. row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@else
{{-- ===== THEME 3: Card Layout ===== --}}
<section class="content">
    <div class="container-fluid">
        <div class="settings-header pt-4">
            <h1><i class="fas fa-building"></i> Company</h1>
            <p class="text-muted">Manage companies and their delivery sites.</p>
        </div>

        <div class="settings-grid">
            @if (checkRole('company', 'view') == true)
            <a href="{{ route('admin.company') }}" class="setting-card">
                <div class="setting-icon icon-primary"><i class="fas fa-building"></i></div>
                <span class="setting-label">Company</span>
            </a>
            @endif
            @if (checkRole('company_delivery_site', 'view') == true)
            <a href="{{ route('admin.company.delivery.index') }}" class="setting-card">
                <div class="setting-icon icon-success"><i class="fas fa-map-marker-alt"></i></div>
                <span class="setting-label">Company Delivery Sites</span>
            </a>
            @endif
        </div>
    </div>
</section>
@endif
@endsection
