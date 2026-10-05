@extends('user::layouts.master')
@section('title', 'Admin | Assignments Menu')
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
                <h1>Assignments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Assignments Menu</li>
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
                            <a class="grid_items" href="{{ route('admin.assignment.grade.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                                            <path fill="currentColor" d="M30 30h-2c0-1.654-1.346-3-3-3h-4c-1.654 0-3 1.346-3 3h-2c0-2.757 2.243-5 5-5h4c2.757 0 5 2.243 5 5M15 16a1 1 0 0 0-1 1v6h2v-6a1 1 0 0 0-1-1" />
                                            <path fill="currentColor" d="M32 12H14v2h4v5c0 2.757 2.243 5 5 5s5-2.243 5-5v-5h4zm-9 10c-1.654 0-3-1.346-3-3v-1h6v1c0 1.654-1.346 3-3 3m3-6h-6v-2h6z" />
                                            <path fill="currentColor" d="M25.798 10C24.87 5.441 20.83 2 16 2a9.977 9.977 0 0 0-9.822 8.124C2.655 10.754 0 13.849 0 17.5C0 21.636 3.365 25 7.5 25H12v-2H7.5A5.506 5.506 0 0 1 2 17.5a5.51 5.51 0 0 1 5.123-5.48l.836-.057l.09-.833A7.98 7.98 0 0 1 16 4c3.72 0 6.845 2.555 7.737 6z" />
                                        </svg>
                                        Grades
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.assignment.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M5.615 20q-.67 0-1.143-.472Q4 19.056 4 18.385V5.615q0-.67.472-1.143Q4.944 4 5.615 4h4.7q-.136-.765.367-1.383Q11.185 2 12 2q.835 0 1.338.617q.502.618.347 1.383h4.7q.67 0 1.143.472q.472.472.472 1.143v12.77q0 .67-.472 1.143q-.472.472-1.143.472zm0-1h12.77q.23 0 .423-.192q.192-.193.192-.423V5.615q0-.23-.192-.423Q18.615 5 18.385 5H5.615q-.23 0-.423.192Q5 5.385 5 5.615v12.77q0 .23.192.423q.193.192.423.192M7.5 16.27h6v-1h-6zm0-3.77h9v-1h-9zm0-3.77h9v-1h-9zM12 4.443q.325 0 .538-.212t.212-.538q0-.325-.213-.537T12 2.942q-.325 0-.537.213t-.213.537q0 .325.213.538t.537.212M5 19V5z" />
                                        </svg>
                                        Assignments
                                    </button>
                                </div>
                            </a>
                        </div>
                        @if (checkRole('assignment_submission', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.submission.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-secondary new_btn_app">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                                                <path d="M20 13V5.749a.6.6 0 0 0-.176-.425l-3.148-3.148A.6.6 0 0 0 16.252 2H4.6a.6.6 0 0 0-.6.6v18.8a.6.6 0 0 0 .6.6H14" />
                                                <path d="M16 2v3.4a.6.6 0 0 0 .6.6H20m-4 13h6m0 0l-3-3m3 3l-3 3" />
                                            </g>
                                        </svg>
                                        Submissions
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.resubmission.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-warning new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M5.615 20q-.67 0-1.143-.472Q4 19.056 4 18.385V5.615q0-.67.472-1.143Q4.944 4 5.615 4h4.7q-.136-.765.367-1.383Q11.185 2 12 2q.835 0 1.338.617q.502.618.347 1.383h4.7q.67 0 1.143.472q.472.472.472 1.143v5.95q-.263-.09-.504-.147q-.24-.056-.496-.112v-5.69q0-.231-.192-.424Q18.615 5 18.385 5H5.615q-.23 0-.423.192Q5 5.385 5 5.615v12.77q0 .23.192.423q.193.192.423.192h5.666q.036.28.093.521q.057.24.147.479zM5 18v1V5v6.306v-.075zm2.5-1.73h3.96q.055-.257.15-.497l.2-.504H7.5zm0-3.77h6.58q.493-.346.97-.587q.479-.24 1.027-.376V11.5H7.5zm0-3.77h9v-1h-9zM12 4.443q.325 0 .538-.212t.212-.538q0-.325-.213-.537T12 2.942q-.325 0-.537.213t-.213.537q0 .325.213.538t.537.212m6 17.673q-1.671 0-2.836-1.164T14 18.115q0-1.67 1.164-2.835T18 14.115q1.671 0 2.836 1.165T22 18.115q0 1.672-1.164 2.836Q19.67 22.115 18 22.115M17.615 21h.77v-2.5h2.5v-.77h-2.5v-2.5h-.77v2.5h-2.5v.77h2.5z" />
                                        </svg>

                                        Resubmission Requests
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
            <h1><i class="fas fa-tasks"></i> Assignments</h1>
            <p class="text-muted">Manage assignments, grades, submissions, and resubmission requests.</p>
        </div>

        <div class="settings-grid">
            <a href="{{ route('admin.assignment.grade.index') }}" class="setting-card">
                <div class="setting-icon icon-primary"><i class="fas fa-graduation-cap"></i></div>
                <span class="setting-label">Grades</span>
            </a>
            <a href="{{ route('admin.assignment.index') }}" class="setting-card">
                <div class="setting-icon icon-success"><i class="fas fa-clipboard-list"></i></div>
                <span class="setting-label">Assignments</span>
            </a>
            @if (checkRole('assignment_submission', 'view') == true)
            <a href="{{ route('admin.submission.index') }}" class="setting-card">
                <div class="setting-icon icon-secondary"><i class="fas fa-upload"></i></div>
                <span class="setting-label">Submissions</span>
            </a>
            <a href="{{ route('admin.resubmission.index') }}" class="setting-card">
                <div class="setting-icon icon-warning"><i class="fas fa-redo-alt"></i></div>
                <span class="setting-label">Resubmission Requests</span>
            </a>
            @endif
        </div>
    </div>
</section>
@endif
@endsection
