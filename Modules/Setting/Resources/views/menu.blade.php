@extends('user::layouts.master')
@section('title', 'Admin | Settings Menu')
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
        --set-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
        --set-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, .07), 0 2px 4px -1px rgba(0, 0, 0, .04);
    }

    .settings-header {
        margin-bottom: 32px;
    }

    .settings-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--set-gray-900);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .settings-header h1 i {
        color: var(--set-primary);
    }

    .settings-category {
        margin-bottom: 40px;
    }

    .category-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--set-gray-500);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--set-gray-100);
    }

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

    .setting-card:hover .setting-icon {
        transform: scale(1.1);
    }

    .setting-label {
        font-weight: 700;
        font-size: 1rem;
        color: var(--set-gray-900);
        line-height: 1.2;
    }

    .icon-primary {
        background: var(--set-primary-light);
        color: var(--set-primary);
    }

    .icon-success {
        background: var(--set-success-light);
        color: var(--set-success);
    }

    .icon-warning {
        background: var(--set-warning-light);
        color: var(--set-warning);
    }

    .icon-danger {
        background: var(--set-danger-light);
        color: var(--set-danger);
    }

    .icon-info {
        background: var(--set-info-light);
        color: var(--set-info);
    }

    .icon-secondary {
        background: var(--set-secondary-light);
        color: var(--set-secondary);
    }

    .icon-purple {
        background: #f5f3ff;
        color: #8b5cf6;
    }

    .icon-dark {
        background: #f1f5f9;
        color: #334155;
    }

    .setting-card:has(.icon-primary) .setting-label {
        color: var(--set-primary);
    }

    .setting-card:has(.icon-success) .setting-label {
        color: var(--set-success);
    }

    .setting-card:has(.icon-warning) .setting-label {
        color: var(--set-warning);
    }

    .setting-card:has(.icon-danger) .setting-label {
        color: var(--set-danger);
    }

    .setting-card:has(.icon-info) .setting-label {
        color: var(--set-info);
    }

    .setting-card:has(.icon-secondary) .setting-label {
        color: var(--set-secondary);
    }

    .setting-card:has(.icon-purple) .setting-label {
        color: #8b5cf6;
    }

    .setting-card:has(.icon-dark) .setting-label {
        color: #334155;
    }

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
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
                <h1>Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Settings Menu</li>
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
                        @if (checkRole('setting', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19.9 12.66a1 1 0 0 1 0-1.32l1.28-1.44a1 1 0 0 0 .12-1.17l-2-3.46a1 1 0 0 0-1.07-.48l-1.88.38a1 1 0 0 1-1.15-.66l-.61-1.83a1 1 0 0 0-.95-.68h-4a1 1 0 0 0-1 .68l-.56 1.83a1 1 0 0 1-1.15.66L5 4.79a1 1 0 0 0-1 .48L2 8.73a1 1 0 0 0 .1 1.17l1.27 1.44a1 1 0 0 1 0 1.32L2.1 14.1a1 1 0 0 0-.1 1.17l2 3.46a1 1 0 0 0 1.07.48l1.88-.38a1 1 0 0 1 1.15.66l.61 1.83a1 1 0 0 0 1 .68h4a1 1 0 0 0 .95-.68l.61-1.83a1 1 0 0 1 1.15-.66l1.88.38a1 1 0 0 0 1.07-.48l2-3.46a1 1 0 0 0-.12-1.17ZM18.41 14l.8.9l-1.28 2.22l-1.18-.24a3 3 0 0 0-3.45 2L12.92 20h-2.56L10 18.86a3 3 0 0 0-3.45-2l-1.18.24l-1.3-2.21l.8-.9a3 3 0 0 0 0-4l-.8-.9l1.28-2.2l1.18.24a3 3 0 0 0 3.45-2L10.36 4h2.56l.38 1.14a3 3 0 0 0 3.45 2l1.18-.24l1.28 2.22l-.8.9a3 3 0 0 0 0 3.98m-6.77-6a4 4 0 1 0 4 4a4 4 0 0 0-4-4m0 6a2 2 0 1 1 2-2a2 2 0 0 1-2 2" />
                                        </svg>
                                        Settings
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.onlineclass.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                                            <path fill="currentColor" d="M6.001 7.5a4.5 4.5 0 0 1 4.5-4.5h15a4.5 4.5 0 0 1 4.5 4.5v10a4.5 4.5 0 0 1-4.5 4.5h-6.309c-.6-1.316-1.876-2.17-3.192-2.422V18a3 3 0 0 1 3-3h5a3 3 0 0 1 3 3v1.5a2.496 2.496 0 0 0 1.001-2v-10a2.5 2.5 0 0 0-2.5-2.5h-15a2.5 2.5 0 0 0-2.5 2.5v.314a6.483 6.483 0 0 0-2 1.062zm9.19 13.5a2.99 2.99 0 0 1 2.224 1h-.001a2.55 2.55 0 0 1 .627 1.873c-.135 2.074-.918 3.68-2.403 4.728C14.205 29.612 12.26 30 9.999 30c-2.248 0-4.156-.384-5.566-1.386c-1.458-1.037-2.228-2.619-2.417-4.65C1.853 22.218 3.35 21 4.872 21zm6.309-7a3.5 3.5 0 1 0 0-7a3.5 3.5 0 0 0 0 7M15 14a5 5 0 1 1-10 0a5 5 0 0 1 10 0" />
                                        </svg>
                                        Online Class Settings
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.dashboard.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-info new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                            <path fill="currentColor" d="M5.5 3A2.5 2.5 0 0 0 3 5.5v9A2.5 2.5 0 0 0 5.5 17h4.1a5.465 5.465 0 0 1-.393-1H5.5A1.5 1.5 0 0 1 4 14.5V7h12v2.207c.349.099.683.23 1 .393V5.5A2.5 2.5 0 0 0 14.5 3zM4 5.5A1.5 1.5 0 0 1 5.5 4h9A1.5 1.5 0 0 1 16 5.5V6H4zm5 3v6a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5M6 9v5h2V9zm3.998-.5a.5.5 0 0 1 .5-.5H14.5a.5.5 0 1 1 0 1h-4.002a.5.5 0 0 1-.5-.5m2.068 2.942a2 2 0 0 1-1.43 2.478l-.462.118a4.703 4.703 0 0 0 .01 1.016l.35.083a2 2 0 0 1 1.456 2.519l-.127.422c.258.204.537.378.835.518l.325-.344a2 2 0 0 1 2.91.002l.337.358c.292-.135.568-.302.822-.498l-.156-.556a2 2 0 0 1 1.43-2.479l.46-.117a4.731 4.731 0 0 0-.01-1.017l-.348-.082a2 2 0 0 1-1.456-2.52l.126-.421a4.318 4.318 0 0 0-.835-.519l-.325.344a2 2 0 0 1-2.91-.001l-.337-.358a4.316 4.316 0 0 0-.822.497zM14.5 15.5a1 1 0 1 1 0-2a1 1 0 0 1 0 2" />
                                        </svg>
                                        Dashboard Settings
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.notification.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-warning new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M22.72 19.5a4.193 4.193 0 0 0 0-1l1.05-.82c.1-.07.12-.18.06-.32l-1-1.72c-.06-.11-.19-.14-.3-.11l-1.25.47c-.28-.17-.53-.34-.84-.46l-.19-1.33A.249.249 0 0 0 20 14h-2c-.12 0-.23.09-.25.21l-.18 1.33c-.32.12-.57.29-.85.46l-1.22-.47c-.13-.03-.27 0-.33.11l-1 1.72c-.06.14-.03.25.06.32l1.06.82c-.02.17-.04.34-.04.5s.02.33.04.5l-1.06.82c-.09.07-.12.21-.06.32l1 1.73c.06.13.2.13.33.13l1.22-.53c.28.2.53.37.85.5l.18 1.32c.02.12.13.21.25.21h2c.13 0 .23-.09.25-.21l.19-1.32c.31-.13.56-.3.84-.5l1.25.53c.11 0 .24 0 .3-.13l1-1.73c.06-.11.04-.25-.06-.32zM19 20.75c-.96 0-1.75-.78-1.75-1.75s.79-1.75 1.75-1.75s1.75.78 1.75 1.75s-.78 1.75-1.75 1.75M12.08 20H3v-1l2-2v-6c0-3.1 2-5.8 5-6.7V4c0-1.1.9-2 2-2s2 .9 2 2v.3c3 .9 5 3.6 5 6.7v1c-.69 0-1.37.11-2 .29V11c0-2.8-2.2-5-5-5s-5 2.2-5 5v7h5.08c-.05.33-.08.66-.08 1c0 .34.03.67.08 1m.22 1c.2.6.44 1.17.76 1.69c-.31.19-.67.31-1.06.31c-1.1 0-2-.9-2-2z" />
                                        </svg>
                                        Notification Settings
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.assignment.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-danger new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
                                            <path fill="currentColor" d="M5 5h5.999V4H5zM3 5h1V4H3zm0 3h1V7H3zm6.022-1l-.15.333l-.737-.078l-.467-.05l-.33.342a5.13 5.13 0 0 0-.39.453H5V7zm-3.005 3L6 10.056l.306.411l.399.533H5v-1zM3 11h1v-1H3z" />
                                            <path fill="currentColor" d="m13 7.05l-.162-.359l-.2-.447l-.47-.11A5.019 5.019 0 0 0 12 6.098V2H2v11h4.36c.157.354.355.69.59 1H1V1h12z" />
                                            <path fill="currentColor" d="M11.004 7c.322 0 .646.036.966.109l.595 1.293l1.465-.152c.457.462.786 1.016.969 1.61l-.87 1.14l.871 1.141a3.94 3.94 0 0 1-.387.859a4.058 4.058 0 0 1-.583.75l-1.465-.152l-.594 1.292a4.37 4.37 0 0 1-1.941.001l-.594-1.293l-1.466.152a3.954 3.954 0 0 1-.969-1.61l.87-1.14L7 9.86a3.947 3.947 0 0 1 .97-1.61l1.466.152l.593-1.292a4.37 4.37 0 0 1 .975-.11M11 12a1 1 0 1 0 .002-1.998A1 1 0 0 0 11 12" />
                                        </svg>
                                        Assignment Settings
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.email.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-secondary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M3 4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10.5a6.5 6.5 0 0 1-.5-2H3V8l8 5l8-5v3a7 7 0 0 1 .5 0a6.5 6.5 0 0 1 1.5.18V6c0-1.1-.9-2-2-2zm0 2h16l-8 5zm16 6l-2.25 2.25L19 16.5V15a2.5 2.5 0 0 1 2.5 2.5c0 .4-.09.78-.26 1.12l1.09 1.09c.42-.63.67-1.39.67-2.21c0-2.21-1.79-4-4-4zm-3.33 3.29c-.42.63-.67 1.39-.67 2.21c0 2.21 1.79 4 4 4V23l2.25-2.25L19 18.5V20a2.5 2.5 0 0 1-2.5-2.5c0-.4.09-.78.26-1.12z" />
                                        </svg>
                                        Email Setting
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.fee.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                            <path fill="currentColor" d="M7 9a2 2 0 1 1 4 0a2 2 0 0 1-4 0m2-1a1 1 0 1 0 0 2a1 1 0 0 0 0-2M3.5 4A1.5 1.5 0 0 0 2 5.5v7A1.5 1.5 0 0 0 3.5 14h5.522a5.5 5.5 0 0 1 .185-1H6v-1a2 2 0 0 0-2-2H3V8h1a2 2 0 0 0 2-2V5h6v1a2 2 0 0 0 2 2h1v1.022q.516.047 1 .185V5.5A1.5 1.5 0 0 0 14.5 4zM3 5.5a.5.5 0 0 1 .5-.5H5v1a1 1 0 0 1-1 1H3zM13 5h1.5a.5.5 0 0 1 .5.5V7h-1a1 1 0 0 1-1-1zm-8 8H3.5a.5.5 0 0 1-.5-.5V11h1a1 1 0 0 1 1 1zm-.915 2h4.937q.047.516.185 1H5.5a1.5 1.5 0 0 1-1.415-1M18 7.5v2.757a5.5 5.5 0 0 0-1-.657V6.085A1.5 1.5 0 0 1 18 7.5m-5.935 3.943a2 2 0 0 1-1.43 2.478l-.462.118a4.7 4.7 0 0 0 .01 1.016l.35.083a2 2 0 0 1 1.456 2.519l-.127.422q.388.307.835.518l.325-.344a2 2 0 0 1 2.91.002l.337.358q.44-.203.822-.498l-.156-.556a2 2 0 0 1 1.43-2.479l.46-.117a4.7 4.7 0 0 0-.01-1.017l-.348-.082a2 2 0 0 1-1.456-2.52l.126-.421a4.3 4.3 0 0 0-.835-.519l-.325.344a2 2 0 0 1-2.91-.001l-.337-.358a4.3 4.3 0 0 0-.821.497zm2.434 4.058a1 1 0 1 1 0-2a1 1 0 0 1 0 2" />
                                        </svg> Fee Settings
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.fee-type.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                            <path fill="currentColor" d="M7 9a2 2 0 1 1 4 0a2 2 0 0 1-4 0m2-1a1 1 0 1 0 0 2a1 1 0 0 0 0-2M3.5 4A1.5 1.5 0 0 0 2 5.5v7A1.5 1.5 0 0 0 3.5 14h5.522a5.5 5.5 0 0 1 .185-1H6v-1a2 2 0 0 0-2-2H3V8h1a2 2 0 0 0 2-2V5h6v1a2 2 0 0 0 2 2h1v1.022q.516.047 1 .185V5.5A1.5 1.5 0 0 0 14.5 4zM3 5.5a.5.5 0 0 1 .5-.5H5v1a1 1 0 0 1-1 1H3zM13 5h1.5a.5.5 0 0 1 .5.5V7h-1a1 1 0 0 1-1-1zm-8 8H3.5a.5.5 0 0 1-.5-.5V11h1a1 1 0 0 1 1 1zm-.915 2h4.937q.047.516.185 1H5.5a1.5 1.5 0 0 1-1.415-1M18 7.5v2.757a5.5 5.5 0 0 0-1-.657V6.085A1.5 1.5 0 0 1 18 7.5m-5.935 3.943a2 2 0 0 1-1.43 2.478l-.462.118a4.7 4.7 0 0 0 .01 1.016l.35.083a2 2 0 0 1 1.456 2.519l-.127.422q.388.307.835.518l.325-.344a2 2 0 0 1 2.91.002l.337.358q.44-.203.822-.498l-.156-.556a2 2 0 0 1 1.43-2.479l.46-.117a4.7 4.7 0 0 0-.01-1.017l-.348-.082a2 2 0 0 1-1.456-2.52l.126-.421a4.3 4.3 0 0 0-.835-.519l-.325.344a2 2 0 0 1-2.91-.001l-.337-.358a4.3 4.3 0 0 0-.821.497zm2.434 4.058a1 1 0 1 1 0-2a1 1 0 0 1 0 2" />
                                        </svg> Fee Types
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.stripe.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M13.479 9.883c-1.626-.604-2.512-1.067-2.512-1.803c0-.622.511-.977 1.423-.977c1.667 0 3.379.642 4.558 1.22l.666-4.111c-.935-.446-2.847-1.177-5.49-1.177c-1.87 0-3.425.489-4.536 1.401c-1.155.954-1.757 2.334-1.757 4c0 3.023 1.847 4.312 4.847 5.403c1.936.688 2.579 1.178 2.579 1.934c0 .732-.629 1.155-1.762 1.155c-1.403 0-3.716-.689-5.231-1.578l-.674 4.157c1.304.732 3.705 1.488 6.197 1.488c1.976 0 3.624-.467 4.735-1.356c1.245-.977 1.89-2.422 1.89-4.289c0-3.091-1.889-4.38-4.935-5.468h.002z" />
                                        </svg>
                                        Stripe setting
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('user', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.user.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-focus new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M11.5 4a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7M6 7.5a5.5 5.5 0 1 1 11 0a5.5 5.5 0 0 1-11 0M8 16a4 4 0 0 0-4 4h8.05v2H2v-2a6 6 0 0 1 6-6h4v2zm11.5-3.25v1.376c.715.184 1.352.56 1.854 1.072l1.193-.689l1 1.732l-1.192.688a4.008 4.008 0 0 1 0 2.142l1.192.688l-1 1.732l-1.193-.689a4 4 0 0 1-1.854 1.072v1.376h-2v-1.376a3.996 3.996 0 0 1-1.854-1.072l-1.193.689l-1-1.732l1.192-.688a4.004 4.004 0 0 1 0-2.142l-1.192-.688l1-1.732l1.193.688a3.996 3.996 0 0 1 1.854-1.071V12.75zm-2.751 4.283a1.991 1.991 0 0 0-.25.967c0 .35.091.68.25.967l.036.063a1.999 1.999 0 0 0 3.43 0l.036-.063c.159-.287.249-.616.249-.967c0-.35-.09-.68-.249-.967l-.036-.063a1.999 1.999 0 0 0-3.43 0z" />
                                        </svg>
                                        Users
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('role', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.role.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-warning new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                            <path fill="currentColor" d="M6 3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h3.601A5.5 5.5 0 0 1 17 9.601V6a3 3 0 0 0-3-3zm3.354 3.396a.5.5 0 0 1 0 .708l-1.75 1.75a.5.5 0 0 1-.691.015l-.75-.685a.5.5 0 1 1 .674-.738l.397.362l1.412-1.412a.5.5 0 0 1 .708 0m-.708 5a.5.5 0 0 1 .708.708l-1.75 1.75a.5.5 0 0 1-.691.015l-.75-.685a.5.5 0 0 1 .674-.738l.397.363zM11 8a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 0 1zm-.366 5.92a2 2 0 0 0 1.43-2.478l-.156-.557c.255-.197.53-.364.822-.5l.337.358a2 2 0 0 0 2.91 0l.322-.343c.298.14.578.313.835.518l-.126.422a2.001 2.001 0 0 0 1.456 2.519l.35.082a4.595 4.595 0 0 1 .01 1.017l-.46.118a1.998 1.998 0 0 0-1.432 2.478l.156.556c-.254.197-.53.365-.822.5l-.337-.358a1.999 1.999 0 0 0-2.909 0l-.32.348a4.355 4.355 0 0 1-.836-.518l.126-.423a2 2 0 0 0-1.456-2.52l-.349-.082a4.622 4.622 0 0 1-.01-1.016zm4.865.58a1 1 0 1 0-2 0a1 1 0 0 0 2 0" />
                                        </svg>
                                        Roles
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('database_backup', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.backup.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                                                <path d="M4 6v6s0 3 7 3s7-3 7-3V6" />
                                                <path d="M11 3c7 0 7 3 7 3s0 3-7 3s-7-3-7-3s0-3 7-3m0 18c-7 0-7-3-7-3v-6m15 9a2 2 0 1 0 0-4a2 2 0 0 0 0 4" />
                                                <path stroke-dasharray=".3 2" d="M19 22a3 3 0 1 0 0-6a3 3 0 0 0 0 6" />
                                            </g>
                                        </svg>
                                        Database Backup
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif

                        @if (checkRole('payment', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.payment.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-info new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M10.5 8a3 3 0 1 0 0 6a3 3 0 0 0 0-6M9 11a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0M2 7.25A2.25 2.25 0 0 1 4.25 5h12.5A2.25 2.25 0 0 1 19 7.25v3.924A6.52 6.52 0 0 0 17.5 11V9.5h-.75a2.25 2.25 0 0 1-2.25-2.25V6.5h-8v.75A2.25 2.25 0 0 1 4.25 9.5H3.5v3h.75a2.25 2.25 0 0 1 2.25 2.25v.75h4.813c-.154.478-.255.98-.294 1.5H4.25A2.25 2.25 0 0 1 2 14.75zM4.401 18.5h6.676c.08.523.223 1.026.421 1.5H7a3 3 0 0 1-2.599-1.5M20.5 11.732A6.516 6.516 0 0 1 22 12.81V10a3 3 0 0 0-1.5-2.599zM4.25 6.5a.75.75 0 0 0-.75.75V8h.75A.75.75 0 0 0 5 7.25V6.5zM17.5 8v-.75a.75.75 0 0 0-.75-.75H16v.75c0 .414.336.75.75.75zm-14 6.75c0 .414.336.75.75.75H5v-.75a.75.75 0 0 0-.75-.75H3.5zm10.778-.774a2 2 0 0 1-1.441 2.496l-.584.144a5.728 5.728 0 0 0 .006 1.808l.54.13a2 2 0 0 1 1.45 2.51l-.187.631c.44.386.94.699 1.484.922l.494-.519a2 2 0 0 1 2.899 0l.498.525a5.276 5.276 0 0 0 1.483-.913l-.198-.686a2 2 0 0 1 1.441-2.496l.584-.144a5.716 5.716 0 0 0-.006-1.808l-.54-.13a2 2 0 0 1-1.45-2.51l.187-.63a5.282 5.282 0 0 0-1.484-.922l-.493.518a2 2 0 0 1-2.9 0l-.498-.525a5.28 5.28 0 0 0-1.483.912zM17.5 19c-.8 0-1.45-.672-1.45-1.5S16.7 16 17.5 16c.8 0 1.45.672 1.45 1.5S18.3 19 17.5 19" />
                                        </svg>
                                        Payments
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('setting', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.offer.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 14 14">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5.83.998a1.895 1.895 0 0 1 2.392 0l.333.271l.423-.068a1.895 1.895 0 0 1 2.072 1.196l.152.4l.401.153A1.895 1.895 0 0 1 12.8 5.022l-.068.423l.27.333a1.895 1.895 0 0 1 0 2.392l-.27.333l.068.423a1.895 1.895 0 0 1-1.196 2.072l-.4.153l-.153.4a1.895 1.895 0 0 1-2.072 1.196l-.423-.068l-.333.271a1.895 1.895 0 0 1-2.392 0l-.333-.27l-.423.067a1.895 1.895 0 0 1-2.072-1.196l-.153-.4l-.4-.153a1.895 1.895 0 0 1-1.196-2.072l.068-.423l-.271-.333a1.895 1.895 0 0 1 0-2.392l.27-.333l-.067-.423A1.895 1.895 0 0 1 2.449 2.95l.4-.152l.153-.401A1.895 1.895 0 0 1 5.074 1.2l.423.068zM4.526 9.474l5-5" />
                                                <path d="M5.026 5.474a.5.5 0 1 0 0-1a.5.5 0 0 0 0 1m4 4a.5.5 0 1 0 0-1a.5.5 0 0 0 0 1" />
                                            </g>
                                        </svg>
                                        Offer Setting
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('offer_template', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.offer.template.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zm0 2l4 4h-4zM8 12h8v2H8zm0 4h8v2H8z" />
                                        </svg>
                                        Offer Letter Templates
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('condition', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.condition.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-danger new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M12 16q.425 0 .713-.288T13 15q0-.425-.288-.712T12 14q-.425 0-.712.288T11 15q0 .425.288.713T12 16m0-3q.425 0 .713-.288T13 12V9q0-.425-.288-.712T12 8q-.425 0-.712.288T11 9v3q0 .425.288.713T12 13m-1.175 9q-.675 0-1.162-.45t-.588-1.1L8.85 18.8q-.325-.125-.612-.3t-.563-.375l-1.55.65q-.625.275-1.25.05t-.975-.8l-1.175-2.05q-.35-.575-.2-1.225t.675-1.075l1.325-1Q4.5 12.5 4.5 12.337v-.675q0-.162.025-.337l-1.325-1Q2.675 9.9 2.525 9.25t.2-1.225L3.9 5.975q.35-.575.975-.8t1.25.05l1.55.65q.275-.2.575-.375t.6-.3l.225-1.65q.1-.65.588-1.1T10.825 2h2.35q.675 0 1.163.45t.587 1.1l.225 1.65q.325.125.613.3t.562.375l1.55-.65q.625-.275 1.25-.05t.975.8l1.175 2.05q.35.575.2 1.225t-.675 1.075l-1.325 1q.025.175.025.338v.674q0 .163-.05.338l1.325 1q.525.425.675 1.075t-.2 1.225l-1.2 2.05q-.35.575-.975.8t-1.25-.05l-1.5-.65q-.275.2-.575.375t-.6.3l-.225 1.65q-.1.65-.587 1.1t-1.163.45z" />
                                        </svg>
                                        Conditions
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('setting', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.risk-scoring.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-danger new_btn_app">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Risk Scoring
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('credit', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.credit.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-dark new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M4 18v-8v.325V6zM4 8h16V6H4zm7.575 12H4q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h16q.825 0 1.413.588T22 6v5.325q-.875-.625-1.912-.975T17.9 10q-1.425 0-2.687.538T13 12H4v6h6.975q.075.525.225 1.025t.375.975m3.925-.1l-.725.225q-.325.1-.637-.025t-.488-.4l-.2-.35q-.175-.3-.125-.65t.325-.575l.55-.475q-.05-.325-.05-.65t.05-.65l-.55-.475q-.275-.225-.325-.562t.125-.638l.225-.375q.175-.275.475-.4t.625-.025l.725.225q.275-.2.538-.337t.562-.263l.15-.725q.075-.35.338-.562T17.7 12h.4q.35 0 .612.225t.338.575l.15.7q.3.125.562.262t.538.338l.725-.225q.325-.1.638.025t.487.4l.2.35q.175.3.125.65t-.325.575l-.55.475q.05.325.05.65t-.05.65l.55.475q.275.225.325.563t-.125.637l-.225.375q-.175.275-.475.4t-.625.025L20.3 19.9q-.275.2-.538.337t-.562.263l-.15.725q-.075.35-.337.563T18.1 22h-.4q-.35 0-.612-.225t-.338-.575l-.15-.7q-.3-.125-.562-.262T15.5 19.9m2.4-.9q.825 0 1.413-.587T19.9 17q0-.825-.587-1.412T17.9 15q-.825 0-1.412.588T15.9 17q0 .825.588 1.413T17.9 19" />
                                        </svg>
                                        Credit
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('email', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.email.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-secondary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2zm-2 0l-8 5l-8-5zm0 12H4V8l8 5l8-5z" />
                                        </svg>
                                        Emails
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('email_template', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.email.template.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M22 5.5H9c-1.1 0-2 .9-2 2v9a2 2 0 0 0 2 2h13c1.11 0 2-.89 2-2v-9a2 2 0 0 0-2-2m0 11H9V9.17l6.5 3.33L22 9.17zm-6.5-5.69L9 7.5h13zM5 16.5c0 .17.03.33.05.5H1c-.552 0-1-.45-1-1s.448-1 1-1h4zM3 7h2.05c-.02.17-.05.33-.05.5V9H3c-.55 0-1-.45-1-1s.45-1 1-1m-2 5c0-.55.45-1 1-1h3v2H2c-.55 0-1-.45-1-1" />
                                        </svg>
                                        Email Templates
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('email_user', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.email.user.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-warning new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M13 19c0-.34.04-.67.09-1H4V8l8 5l8-5v5.09c.72.12 1.39.37 2 .72V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h9.09c-.05-.33-.09-.66-.09-1m7-13l-8 5l-8-5zm0 16v-2h-4v-2h4v-2l3 3z" />
                                        </svg>
                                        Send Emails
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('template', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.template.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-info new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M3 6.25A3.25 3.25 0 0 1 6.25 3h11.5A3.25 3.25 0 0 1 21 6.25v5.772a6.471 6.471 0 0 0-1.5-.709V10h-4v1.313a6.471 6.471 0 0 0-1.5.709V10h-4v4h2.022a6.471 6.471 0 0 0-.709 1.5H10v4h1.313c.173.534.412 1.037.709 1.5H6.25A3.25 3.25 0 0 1 3 17.75zM6.25 4.5A1.75 1.75 0 0 0 4.5 6.25V8.5h4v-4zM4.5 10v4h4v-4zm11-1.5h4V6.25a1.75 1.75 0 0 0-1.75-1.75H15.5zm-1.5-4h-4v4h4zm-9.5 11v2.25c0 .966.784 1.75 1.75 1.75H8.5v-4zm9.778-1.525a2 2 0 0 1-1.441 2.497l-.584.144a5.729 5.729 0 0 0 .006 1.807l.54.13a2 2 0 0 1 1.45 2.51l-.187.632c.44.386.94.699 1.484.921l.494-.518a2 2 0 0 1 2.899 0l.498.525a5.281 5.281 0 0 0 1.483-.913l-.198-.686a2 2 0 0 1 1.441-2.496l.584-.144a5.716 5.716 0 0 0-.006-1.808l-.54-.13a2 2 0 0 1-1.45-2.51l.187-.63a5.278 5.278 0 0 0-1.484-.923l-.493.519a2 2 0 0 1-2.9 0l-.498-.525c-.544.22-1.044.53-1.483.912zM17.5 19c-.8 0-1.45-.672-1.45-1.5c0-.829.65-1.5 1.45-1.5c.8 0 1.45.671 1.45 1.5c0 .828-.65 1.5-1.45 1.5" />
                                        </svg>
                                        Templates
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('document_type', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.document.type.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 2048 2048">
                                            <path fill="currentColor" d="M1103 1920q23 37 52 68t62 60H128V0h1115l549 549v494q-63-22-128-29V640h-512V128H256v1792zm177-1701v293h293zm-128 998q-13 15-25 30t-24 33H512v-128h640zm-640 319v-128h512v60q0 14-4 33t-6 35zm896-640v128H512V896zm512 704q0 31-6 61l124 51l-49 119l-124-52q-35 51-86 86l52 124l-119 49l-51-124q-30 6-61 6t-61-6l-51 124l-119-49l52-124q-51-35-86-86l-124 52l-49-119l124-51q-6-30-6-61t6-61l-124-51l49-119l124 52q18-25 39-47t47-39l-52-124l119-49l51 124q30-6 61-6t61 6l51-124l119 49l-52 124q51 35 86 86l124-52l49 119l-124 51q6 30 6 61m-128 0q0-40-15-75t-41-61t-61-41t-75-15t-75 15t-61 41t-41 61t-15 75t15 75t41 61t61 41t75 15t75-15t61-41t41-61t15-75" />
                                        </svg>
                                        Document Types
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('student_document_type', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.document.type.student.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M18.25 3A2.75 2.75 0 0 1 21 5.75v6.272a6.5 6.5 0 0 0-1.5-.709V5.75c0-.69-.56-1.25-1.25-1.25H5.75c-.69 0-1.25.56-1.25 1.25v12.5c0 .69.56 1.25 1.25 1.25h5.563c.173.534.412 1.037.709 1.5H5.75A2.75 2.75 0 0 1 3 18.25V5.75A2.75 2.75 0 0 1 5.75 3zm-4 8.5c.162 0 .313.052.435.14A6.5 6.5 0 0 0 12.81 13H6.75a.75.75 0 0 1-.102-1.493l.102-.007zm-7.5 4h4.563c-.154.478-.255.98-.294 1.5H6.75a.75.75 0 0 1-.102-1.493zm10.5-8H6.75l-.102.007A.75.75 0 0 0 6.75 9h10.5l.102-.007A.75.75 0 0 0 17.25 7.5m-4.75 8.129l.447.43a2 2 0 0 1 0 2.882l-.447.43c.2.574.49 1.103.853 1.57l.602-.178a2 2 0 0 1 2.51 1.45l.174.715a5.2 5.2 0 0 0 1.722 0l.173-.716a2 2 0 0 1 2.511-1.449l.602.178c.362-.467.652-.996.853-1.57l-.447-.43a2 2 0 0 1 0-2.882l.447-.43a5.5 5.5 0 0 0-.853-1.57l-.602.178a2 2 0 0 1-2.51-1.45l-.174-.715a5.2 5.2 0 0 0-1.723 0l-.172.716a2 2 0 0 1-2.511 1.449l-.602-.178a5.5 5.5 0 0 0-.853 1.57m5 3.371c-.8 0-1.45-.672-1.45-1.5S16.7 16 17.5 16s1.45.672 1.45 1.5S18.3 19 17.5 19" />
                                        </svg>
                                        Student Document Type
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('offer_status', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.offer.status.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-dark new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <g fill="none">
                                                <path d="M24 0v24H0V0zM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                                <path fill="currentColor" d="M16 3a3 3 0 0 1 2.995 2.824L19 6v10h.75c.647 0 1.18.492 1.244 1.122l.006.128V19a3 3 0 0 1-2.824 2.995L18 22H8a3 3 0 0 1-2.995-2.824L5 19V9H3.25a1.25 1.25 0 0 1-1.244-1.122L2 7.75V6a3 3 0 0 1 2.824-2.995L5 3zm0 2H7v14a1 1 0 1 0 2 0v-1.75c0-.69.56-1.25 1.25-1.25H17V6a1 1 0 0 0-1-1m3 13h-8v1c0 .35-.06.687-.17 1H18a1 1 0 0 0 1-1zm-7-6a1 1 0 1 1 0 2h-2a1 1 0 1 1 0-2zm2-4a1 1 0 1 1 0 2h-4a1 1 0 0 1 0-2zM5 5a1 1 0 0 0-.993.883L4 6v1h1z" />
                                            </g>
                                        </svg>
                                        Offer Status
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('social_category', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.social.category.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-danger new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.004H6.657C4.085 18 2 15.993 2 13.517c0-2.475 2.085-4.482 4.657-4.482c.393-1.762 1.794-3.2 3.675-3.773c1.88-.572 3.956-.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c.956 0 1.822.39 2.449 1.02M17.001 19a2 2 0 1 0 4 0a2 2 0 1 0-4 0m2-3.5V17m0 4v1.5m3.031-5.25l-1.299.75m-3.463 2l-1.3.75m0-3.5l1.3.75m3.463 2l1.3.75" />
                                        </svg>
                                        Social Categories
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('country', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.country.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.901 14.702A5.014 5.014 0 0 1 12 14a5 5 0 0 0-7 0V5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v6.5M5 21v-7m12.001 5a2 2 0 1 0 4 0a2 2 0 1 0-4 0m2-3.5V17m0 4v1.5m3.031-5.25l-1.299.75m-3.463 2l-1.3.75m0-3.5l1.3.75m3.463 2l1.3.75" />
                                        </svg>
                                        Countries
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('location', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.location.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M12 1.998c5.524 0 10.002 4.478 10.002 10.002q0 .587-.067 1.157a5.5 5.5 0 0 0-1.434-1.058V12c0-.689-.081-1.359-.236-2h-3.358q.075.778.09 1.591a5.5 5.5 0 0 0-1.496.508V12q-.001-1.038-.104-2.001H8.603a19 19 0 0 0 .135 5h4.137a5.5 5.5 0 0 0-.352 1.5H9.06c.652 2.415 1.786 4.002 2.94 4.002c.454 0 .906-.247 1.326-.694c.361.616.832 1.222 1.399 1.818c-.867.245-1.781.376-2.726.376C6.476 22.001 2 17.523 2 12C1.999 6.476 6.476 1.998 12 1.998M7.508 16.5H4.786a8.53 8.53 0 0 0 4.094 3.41c-.522-.82-.953-1.846-1.27-3.015zm-.414-6.501H3.736l-.005.017A8.5 8.5 0 0 0 3.5 12a8.5 8.5 0 0 0 .544 3h3.173A20 20 0 0 1 7 12c0-.684.032-1.354.095-2.001m1.787-5.91l-.023.008A8.53 8.53 0 0 0 4.25 8.5h3.048c.314-1.752.86-3.278 1.583-4.41m3.12-.591l-.117.005C10.62 3.62 9.397 5.621 8.83 8.5h6.342c-.566-2.87-1.783-4.869-3.045-4.995zm3.12.59l.106.175c.67 1.112 1.177 2.572 1.475 4.237h3.048a8.53 8.53 0 0 0-4.339-4.29zM22.5 17a4.5 4.5 0 0 0-9 0c0 1.863 1.419 3.815 4.2 5.9a.5.5 0 0 0 .6 0c2.78-2.085 4.2-4.037 4.2-5.9m-6 0a1.5 1.5 0 1 1 2.999 0a1.5 1.5 0 0 1-3 0" />
                                        </svg>
                                        Locations
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('setting', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.theme') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-secondary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 36 36">
                                            <path fill="currentColor" d="M24.12 20.35a4 4 0 1 0 4.08 4a4.06 4.06 0 0 0-4.08-4m0 6.46a2.43 2.43 0 1 1 2.48-2.43a2.46 2.46 0 0 1-2.48 2.44Z" class="clr-i-outline--alerted clr-i-outline-path-1--alerted" />
                                            <path fill="currentColor" d="M14.49 31H6V5h15.87L23 3H6a2 2 0 0 0-2 2v26a2 2 0 0 0 2 2h10.23l-1.1-1.08a3.1 3.1 0 0 1-.64-.92" class="clr-i-outline--alerted clr-i-outline-path-3--alerted" />
                                            <path fill="none" d="M0 0h36v36H0z" />
                                        </svg>
                                        Themes
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('lead', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.lead.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-dark new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor">
                                                <path d="M11 5h7m-8 5l4.5 4.5M5 11v7" />
                                                <circle cx="6.444" cy="6.444" r="4.444" />
                                                <circle cx="5" cy="20" r="2" />
                                                <circle cx="16" cy="16" r="2" />
                                                <circle cx="20" cy="5" r="2" />
                                            </g>
                                        </svg>
                                        CRM
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('zoho_lead', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.setting.zoho.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-info new_btn_app">
                                        <i class="fas fa-plug fa-2x"></i>
                                        ZOHO Settings
                                    </button>
                                </div>
                            </a>
                        </div>

                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.zoho.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-warning new_btn_app">
                                        <i class="fas fa-sync-alt fa-2x"></i>
                                        ZOHO CRM
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
{{-- ===== THEME 3: Category Settings Layout ===== --}}
<section class="content">
    <div class="container-fluid">
        <div class="settings-header pt-4">
            <h1><i class="fas fa-cogs"></i> System Settings</h1>
            <p class="text-muted">Configure and manage various aspects of your LMS platform.</p>
        </div>

        {{-- 1. General Setting --}}
        <div class="settings-category">
            <h2 class="category-title"><i class="fas fa-desktop"></i> General Setting</h2>
            <div class="settings-grid">
                @if (checkRole('setting', 'view') == true)
                <a href="{{ route('admin.setting.index') }}" class="setting-card">
                    <div class="setting-icon icon-danger"><i class="fas fa-sliders-h"></i></div>
                    <span class="setting-label">General Settings</span>
                </a>
                @endif
                @if (checkRole('user', 'view') == true)
                <a href="{{ route('admin.user.index') }}" class="setting-card">
                    <div class="setting-icon icon-primary"><i class="fas fa-user-friends"></i></div>
                    <span class="setting-label">Users</span>
                </a>
                @endif
                @if (checkRole('role', 'view') == true)
                <a href="{{ route('admin.role.index') }}" class="setting-card">
                    <div class="setting-icon icon-warning"><i class="fas fa-user-tag"></i></div>
                    <span class="setting-label">Roles</span>
                </a>
                @endif
                @if (checkRole('database_backup', 'view') == true)
                <a href="{{ route('admin.backup.index') }}" class="setting-card">
                    <div class="setting-icon icon-info"><i class="fas fa-database"></i></div>
                    <span class="setting-label">Database Backup</span>
                </a>
                @endif
                @if (checkRole('setting', 'view') == true)
                <a href="{{ route('admin.setting.theme') }}" class="setting-card">
                    <div class="setting-icon icon-success"><i class="fas fa-palette"></i></div>
                    <span class="setting-label">Themes</span>
                </a>
                @endif
            </div>
        </div>

        {{-- 2. Finance & Fees --}}
        <div class="settings-category">
            <h2 class="category-title"><i class="fas fa-money-bill-wave"></i> Finance & Fees</h2>
            <div class="settings-grid">
                @if (checkRole('setting', 'view') == true)
                <a href="{{ route('admin.setting.fee.index') }}" class="setting-card">
                    <div class="setting-icon icon-success"><i class="fas fa-file-invoice-dollar"></i></div>
                    <span class="setting-label">Fee Settings</span>
                </a>
                <a href="{{ route('admin.fee-type.index') }}" class="setting-card">
                    <div class="setting-icon icon-success"><i class="fas fa-tags"></i></div>
                    <span class="setting-label">Fee Types</span>
                </a>
                <a href="{{ route('admin.setting.stripe.index') }}" class="setting-card">
                    <div class="setting-icon icon-success"><i class="fab fa-stripe-s"></i></div>
                    <span class="setting-label">Stripe Setting</span>
                </a>
                @endif
                @if (checkRole('payment', 'view') == true)
                <a href="{{ route('admin.payment.index') }}" class="setting-card">
                    <div class="setting-icon icon-info"><i class="fas fa-credit-card"></i></div>
                    <span class="setting-label">Payments</span>
                </a>
                @endif
            </div>
        </div>

        {{-- 3. Communication --}}
        <div class="settings-category">
            <h2 class="category-title"><i class="fas fa-envelope-open-text"></i> Communication</h2>
            <div class="settings-grid">
                @if (checkRole('email', 'view') == true)
                <a href="{{ route('admin.email.index') }}" class="setting-card">
                    <div class="setting-icon icon-secondary"><i class="fas fa-envelope"></i></div>
                    <span class="setting-label">Emails Config</span>
                </a>
                @endif
                @if (checkRole('email_template', 'view') == true)
                <a href="{{ route('admin.email.template.index') }}" class="setting-card">
                    <div class="setting-icon icon-success"><i class="fas fa-pager"></i></div>
                    <span class="setting-label">Email Templates</span>
                </a>
                @endif
                @if (checkRole('email_user', 'view') == true)
                <a href="{{ route('admin.email.user.index') }}" class="setting-card">
                    <div class="setting-icon icon-warning"><i class="fas fa-paper-plane"></i></div>
                    <span class="setting-label">Send Emails</span>
                </a>
                @endif
                @if (checkRole('setting', 'view') == true)
                <a href="{{ route('admin.setting.notification.index') }}" class="setting-card">
                    <div class="setting-icon icon-warning"><i class="fas fa-bell"></i></div>
                    <span class="setting-label">Notification Settings</span>
                </a>
                @endif
            </div>
        </div>

        {{-- 4. LMS Features --}}
        <div class="settings-category">
            <h2 class="category-title"><i class="fas fa-laptop-code"></i> LMS Features</h2>
            <div class="settings-grid">
                @if (checkRole('setting', 'view') == true)
                <a href="{{ route('admin.setting.onlineclass.index') }}" class="setting-card">
                    <div class="setting-icon icon-success"><i class="fas fa-video"></i></div>
                    <span class="setting-label">Online Class</span>
                </a>
                <a href="{{ route('admin.setting.dashboard.index') }}" class="setting-card">
                    <div class="setting-icon icon-info"><i class="fas fa-th-large"></i></div>
                    <span class="setting-label">Dashboard View</span>
                </a>
                <a href="{{ route('admin.setting.assignment.index') }}" class="setting-card">
                    <div class="setting-icon icon-danger"><i class="fas fa-tasks"></i></div>
                    <span class="setting-label">Assignments</span>
                </a>
                <a href="{{ route('admin.setting.offer.index') }}" class="setting-card">
                    <div class="setting-icon icon-primary"><i class="fas fa-percent"></i></div>
                    <span class="setting-label">Offers</span>
                </a>
                @endif
                @if (checkRole('offer_template', 'view') == true)
                <a href="{{ route('admin.offer.template.index') }}" class="setting-card">
                    <div class="setting-icon icon-info"><i class="fas fa-file-signature"></i></div>
                    <span class="setting-label">Offer Letter Templates</span>
                </a>
                @endif
                @if (checkRole('credit', 'view') == true)
                <a href="{{ route('admin.credit.index') }}" class="setting-card">
                    <div class="setting-icon icon-warning"><i class="fas fa-hand-holding-usd"></i></div>
                    <span class="setting-label">Credit</span>
                </a>
                @endif
                @if (checkRole('condition', 'view') == true)
                <a href="{{ route('admin.condition.index') }}" class="setting-card">
                    <div class="setting-icon icon-danger"><i class="fas fa-balance-scale"></i></div>
                    <span class="setting-label">Conditions</span>
                </a>
                @endif
                @if (checkRole('offer_status', 'view') == true)
                <a href="{{ route('admin.offer.status.index') }}" class="setting-card">
                    <div class="setting-icon icon-warning"><i class="fas fa-check-double"></i></div>
                    <span class="setting-label">Offer Statuses</span>
                </a>
                @endif
                @if (checkRole('lead', 'view') == true)
                <a href="{{ route('admin.lead.index') }}" class="setting-card">
                    <div class="setting-icon icon-secondary"><i class="fas fa-user-tie"></i></div>
                    <span class="setting-label">CRM Leads</span>
                </a>
                @endif
                @if (checkRole('document_type', 'view') == true)
                <a href="{{ route('admin.document.type.index') }}" class="setting-card">
                    <div class="setting-icon icon-success"><i class="fas fa-file-signature"></i></div>
                    <span class="setting-label">Document Types</span>
                </a>
                @endif
                @if (checkRole('student_document_type', 'view') == true)
                <a href="{{ route('admin.document.type.student.index') }}" class="setting-card">
                    <div class="setting-icon icon-primary"><i class="fas fa-id-card"></i></div>
                    <span class="setting-label">Student Docs</span>
                </a>
                @endif
                @if (checkRole('template', 'view') == true)
                <a href="{{ route('admin.template.index') }}" class="setting-card">
                    <div class="setting-icon icon-info"><i class="fas fa-clone"></i></div>
                    <span class="setting-label">Global Templates</span>
                </a>
                @endif
            </div>
        </div>

        {{-- 5. Integrations & Compliance --}}
        <div class="settings-category">
            <h2 class="category-title"><i class="fas fa-project-diagram"></i> Integrations & Compliance</h2>
            <div class="settings-grid">
                @if (checkRole('zoho_lead', 'view') == true)
                <a href="{{ route('admin.setting.zoho.index') }}" class="setting-card">
                    <div class="setting-icon icon-info"><i class="fas fa-plug"></i></div>
                    <span class="setting-label">ZOHO Settings</span>
                </a>
                <a href="{{ route('admin.zoho.index') }}" class="setting-card">
                    <div class="setting-icon icon-warning"><i class="fas fa-sync-alt"></i></div>
                    <span class="setting-label">ZOHO CRM</span>
                </a>
                @endif
            </div>
        </div>

        {{-- 6. Locations & Demographics --}}
        <div class="settings-category">
            <h2 class="category-title"><i class="fas fa-map-marked-alt"></i> Locations & Demographics</h2>
            <div class="settings-grid">
                @if (checkRole('country', 'view') == true)
                <a href="{{ route('admin.country.index') }}" class="setting-card">
                    <div class="setting-icon icon-primary"><i class="fas fa-globe-americas"></i></div>
                    <span class="setting-label">Countries</span>
                </a>
                @endif
                @if (checkRole('location', 'view') == true)
                <a href="{{ route('admin.location.index') }}" class="setting-card">
                    <div class="setting-icon icon-primary"><i class="fas fa-map-pin"></i></div>
                    <span class="setting-label">Locations</span>
                </a>
                @endif
                @if (checkRole('social_category', 'view') == true)
                <a href="{{ route('admin.social.category.index') }}" class="setting-card">
                    <div class="setting-icon icon-warning"><i class="fas fa-users"></i></div>
                    <span class="setting-label">Social Categories</span>
                </a>
                @endif
                @if (checkRole('setting', 'view') == true)
                <a href="{{ route('admin.setting.risk-scoring.index') }}" class="setting-card">
                    <div class="setting-icon icon-danger"><i class="fas fa-exclamation-triangle"></i></div>
                    <span class="setting-label">Risk Scoring</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
{{-- ===== END THEME 3 ===== --}}
@endif
@endsection
