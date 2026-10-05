@extends('user::layouts.master')
@section('title', 'Admin | Resources Menu')
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
                <h1>Resources</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Resources Menu</li>
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
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.resource.category.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <g fill="none" fill-rule="evenodd">
                                                <path d="M24 0v24H0V0zM12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036c-.01-.003-.019 0-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z" />
                                                <path fill="currentColor" d="M13 3a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2v2h4a3 3 0 0 1 3 3v1a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2v-1a1 1 0 0 0-1-1h-4v2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2v-2H7a1 1 0 0 0-1 1v1a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2v-1a3 3 0 0 1 3-3h4V9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 14h-2v2h2zM6 17H4v2h2zm7 0h-2v2h2zm0-12h-2v2h2z" />
                                            </g>
                                        </svg>

                                        Categories
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.resource.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256">
                                            <path fill="currentColor" d="M245 110.64a16 16 0 0 0-13-6.64h-16V88a16 16 0 0 0-16-16h-69.33l-27.73-20.8a16.14 16.14 0 0 0-9.6-3.2H40a16 16 0 0 0-16 16v144a8 8 0 0 0 8 8h179.1a8 8 0 0 0 7.59-5.47l28.49-85.47a16.05 16.05 0 0 0-2.18-14.42M93.34 64l27.73 20.8a16.12 16.12 0 0 0 9.6 3.2H200v16H69.77a16 16 0 0 0-15.18 10.94L40 158.7V64Z" />
                                        </svg>
                                        Resources
                                    </button>
                                </div>
                            </a>
                        </div>

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
            <h1><i class="fas fa-folder-open"></i> Resources</h1>
            <p class="text-muted">Manage resource categories and learning resources.</p>
        </div>

        <div class="settings-grid">
            <a href="{{ route('admin.resource.category.index') }}" class="setting-card">
                <div class="setting-icon icon-primary"><i class="fas fa-sitemap"></i></div>
                <span class="setting-label">Categories</span>
            </a>
            <a href="{{ route('admin.resource.index') }}" class="setting-card">
                <div class="setting-icon icon-success"><i class="fas fa-folder-open"></i></div>
                <span class="setting-label">Resources</span>
            </a>
        </div>
    </div>
</section>
@endif
@endsection
