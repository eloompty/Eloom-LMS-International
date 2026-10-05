@extends('user::layouts.master')
@section('title', 'Admin | Courses Menu')
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
                    <!-- <div class="card-header">
                <h3 class="card-title">Application Buttons</h3>
              </div> -->
                    <div class="my_btn_list">
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.course.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 56 56">
                                            <path fill="currentColor" d="M16.293 29.77c6.539 0 11.906-5.368 11.906-11.93c0-6.516-5.367-11.906-11.906-11.906c-6.516 0-11.906 5.39-11.906 11.906c0 6.562 5.39 11.93 11.906 11.93M33.8 13.246h16.008c1.008 0 1.804-.773 1.804-1.781c0-.985-.797-1.758-1.804-1.758H33.8c-1.008 0-1.782.773-1.782 1.758c0 1.008.774 1.781 1.782 1.781M14.887 24.824a1.64 1.64 0 0 1-1.149-.492l-4.5-4.922c-.164-.187-.281-.61-.281-.914c0-.82.633-1.453 1.43-1.453c.492 0 .843.234 1.101.492l3.328 3.633l6.211-8.625c.258-.375.68-.633 1.196-.633c.773 0 1.453.61 1.453 1.43c0 .234-.117.562-.328.844l-7.266 10.101c-.234.328-.703.54-1.195.54m18.914.703h16.008c1.008 0 1.804-.773 1.804-1.78c0-.985-.797-1.759-1.804-1.759H33.8c-1.008 0-1.782.774-1.782 1.758c0 1.008.774 1.781 1.782 1.781M6.168 37.81h43.64a1.786 1.786 0 0 0 1.805-1.782c0-.984-.797-1.758-1.804-1.758H6.168c-1.008 0-1.781.774-1.781 1.758c0 .985.773 1.782 1.78 1.782m0 12.257h43.64c1.008 0 1.805-.773 1.805-1.757c0-.985-.797-1.782-1.804-1.782H6.168a1.766 1.766 0 0 0-1.781 1.782c0 .984.773 1.757 1.78 1.757" />
                                        </svg>
                                        Registered
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.unregistered.index') }}">
                                <div class="inner_grid">
                                        <button class="btn btn-outline-success new_btn_app">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 56 56">
                                                <path fill="currentColor" d="M16.293 29.77c6.539 0 11.906-5.368 11.906-11.93c0-6.516-5.367-11.906-11.906-11.906c-6.516 0-11.906 5.39-11.906 11.906c0 6.562 5.39 11.93 11.906 11.93M33.8 13.246h16.008c1.008 0 1.804-.773 1.804-1.781c0-.985-.797-1.758-1.804-1.758H33.8c-1.008 0-1.782.773-1.782 1.758c0 1.008.774 1.781 1.782 1.781M12.848 23.395c-.61.585-1.477.468-2.016-.07c-.562-.54-.656-1.43-.07-2.016l3.539-3.54l-3.258-3.28c-.516-.54-.516-1.407 0-1.9a1.365 1.365 0 0 1 1.922 0l3.281 3.235l3.516-3.515c.586-.586 1.476-.47 2.015.07c.54.539.656 1.406.07 2.016L18.31 17.91l3.257 3.281c.516.54.516 1.43 0 1.922a1.41 1.41 0 0 1-1.921 0l-3.258-3.258ZM33.8 25.527h16.008c1.008 0 1.804-.773 1.804-1.78c0-.985-.797-1.759-1.804-1.759H33.8c-1.008 0-1.782.774-1.782 1.758c0 1.008.774 1.781 1.782 1.781M6.168 37.81h43.64a1.786 1.786 0 0 0 1.805-1.782c0-.984-.797-1.758-1.804-1.758H6.168c-1.008 0-1.781.774-1.781 1.758c0 .985.773 1.782 1.78 1.782m0 12.257h43.64c1.008 0 1.805-.773 1.805-1.757c0-.985-.797-1.782-1.804-1.782H6.168a1.766 1.766 0 0 0-1.781 1.782c0 .984.773 1.757 1.78 1.757" />
                                            </svg>
                                            Unregistered
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
            <h1><i class="fas fa-book"></i> Courses</h1>
            <p class="text-muted">Manage registered and unregistered course enrolments.</p>
        </div>

        <div class="settings-grid">
            <a href="{{ route('admin.course.index') }}" class="setting-card">
                <div class="setting-icon icon-primary"><i class="fas fa-check-circle"></i></div>
                <span class="setting-label">Registered</span>
            </a>
            <a href="{{ route('admin.unregistered.index') }}" class="setting-card">
                <div class="setting-icon icon-danger"><i class="fas fa-times-circle"></i></div>
                <span class="setting-label">Unregistered</span>
            </a>
        </div>
    </div>
</section>
@endif
@endsection
