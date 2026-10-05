@extends('user::layouts.master')
@section('title', 'Admin | Reports Menu')
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
                <h1>Reports</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reports Menu</li>
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
                        @if (checkRole('student_report', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.student.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                                            <path fill="currentColor" d="M30 30h-2c0-1.654-1.346-3-3-3h-4c-1.654 0-3 1.346-3 3h-2c0-2.757 2.243-5 5-5h4c2.757 0 5 2.243 5 5M15 16a1 1 0 0 0-1 1v6h2v-6a1 1 0 0 0-1-1" />
                                            <path fill="currentColor" d="M32 12H14v2h4v5c0 2.757 2.243 5 5 5s5-2.243 5-5v-5h4zm-9 10c-1.654 0-3-1.346-3-3v-1h6v1c0 1.654-1.346 3-3 3m3-6h-6v-2h6z" />
                                            <path fill="currentColor" d="M25.798 10C24.87 5.441 20.83 2 16 2a9.977 9.977 0 0 0-9.822 8.124C2.655 10.754 0 13.849 0 17.5C0 21.636 3.365 25 7.5 25H12v-2H7.5A5.506 5.506 0 0 1 2 17.5a5.51 5.51 0 0 1 5.123-5.48l.836-.057l.09-.833A7.98 7.98 0 0 1 16 4c3.72 0 6.845 2.555 7.737 6z" />
                                        </svg>
                                        Student Report
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('intake_report', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.intake.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-secondary new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M5.615 20q-.67 0-1.143-.472Q4 19.056 4 18.385V5.615q0-.67.472-1.143Q4.944 4 5.615 4h4.7q-.136-.765.367-1.383Q11.185 2 12 2q.835 0 1.338.617q.502.618.347 1.383h4.7q.67 0 1.143.472q.472.472.472 1.143v12.77q0 .67-.472 1.143q-.472.472-1.143.472zm0-1h12.77q.23 0 .423-.192q.192-.193.192-.423V5.615q0-.23-.192-.423Q18.615 5 18.385 5H5.615q-.23 0-.423.192Q5 5.385 5 5.615v12.77q0 .23.192.423q.193.192.423.192M7.5 16.27h6v-1h-6zm0-3.77h9v-1h-9zm0-3.77h9v-1h-9zM12 4.443q.325 0 .538-.212t.212-.538q0-.325-.213-.537T12 2.942q-.325 0-.537.213t-.213.537q0 .325.213.538t.537.212M5 19V5z" />
                                        </svg>
                                        Intake Report
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('agent_report', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.agent.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-success new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h4.2q.325-.9 1.088-1.45T12 1q.95 0 1.713.55T14.8 3H19q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V5H5zm3-2h5q.425 0 .713-.288T14 16q0-.425-.288-.712T13 15H8q-.425 0-.712.288T7 16q0 .425.288.713T8 17m0-4h8q.425 0 .713-.288T17 12q0-.425-.288-.712T16 11H8q-.425 0-.712.288T7 12q0 .425.288.713T8 13m0-4h8q.425 0 .713-.288T17 8q0-.425-.288-.712T16 7H8q-.425 0-.712.288T7 8q0 .425.288.713T8 9m4-4.75q.325 0 .538-.213t.212-.537q0-.325-.213-.537T12 2.75q-.325 0-.537.213t-.213.537q0 .325.213.538T12 4.25M5 19V5z" />
                                        </svg>
                                        Agent Report
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('due_pays_report', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.due.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-info new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <g fill="currentColor">
                                                <path d="M7 8a1 1 0 0 1 1-1h8a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1m5 8a2 2 0 1 0 0-4a2 2 0 0 0 0 4" />
                                                <path fill-rule="evenodd" d="M6 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3zm12 2H6a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1" clip-rule="evenodd" />
                                            </g>
                                        </svg>
                                        Payment Due Report
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('fee_received_report', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.fee.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-warning new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                            <path fill="currentColor" d="M4 5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v7h3v2a3 3 0 0 1-3 3h-4.05c.033-.162.05-.329.05-.5v-3A2.5 2.5 0 0 0 8.5 11H4zm11 11a2 2 0 0 0 2-2v-1h-2zM7.5 6a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2.5 4.5A1.5 1.5 0 0 0 8.5 12h-6A1.5 1.5 0 0 0 1 13.5v3A1.5 1.5 0 0 0 2.5 18h6a1.5 1.5 0 0 0 1.5-1.5zm-1 2v1a.5.5 0 0 0-.5.5h-1A1.5 1.5 0 0 1 9 15.5M8.5 13a.5.5 0 0 0 .5.5v1A1.5 1.5 0 0 1 7.5 13zm-6.5.5a.5.5 0 0 0 .5-.5h1A1.5 1.5 0 0 1 2 14.5zm.5 3.5a.5.5 0 0 0-.5-.5v-1A1.5 1.5 0 0 1 3.5 17zM4 15a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0" />
                                        </svg>
                                        Fee Received Report
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('commission_report', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.commission.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-danger new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M17.414 10.414C18 9.828 18 8.886 18 7c0-1.886 0-2.828-.586-3.414m0 6.828C16.828 11 15.886 11 14 11h-4c-1.886 0-2.828 0-3.414-.586m10.828 0Zm0-6.828C16.828 3 15.886 3 14 3h-4c-1.886 0-2.828 0-3.414.586m10.828 0Zm-10.828 0C6 4.172 6 5.114 6 7c0 1.886 0 2.828.586 3.414m0-6.828Zm0 6.828ZM13 7a1 1 0 1 1-2 0a1 1 0 0 1 2 0Z" />
                                                <path stroke-linecap="round" d="M18 6a3 3 0 0 1-3-3m3 5a3 3 0 0 0-3 3M6 6a3 3 0 0 0 3-3M6 8a3 3 0 0 1 3 3m-4 9.388h2.26c1.01 0 2.033.106 3.016.308a14.85 14.85 0 0 0 5.33.118c.868-.14 1.72-.355 2.492-.727c.696-.337 1.549-.81 2.122-1.341c.572-.53 1.168-1.397 1.59-2.075c.364-.582.188-1.295-.386-1.728a1.887 1.887 0 0 0-2.22 0l-1.807 1.365c-.7.53-1.465 1.017-2.376 1.162c-.11.017-.225.033-.345.047m0 0a8.176 8.176 0 0 1-.11.012m.11-.012a.998.998 0 0 0 .427-.24a1.492 1.492 0 0 0 .126-2.134a1.9 1.9 0 0 0-.45-.367c-2.797-1.669-7.15-.398-9.779 1.467m9.676 1.274a.524.524 0 0 1-.11.012m0 0a9.274 9.274 0 0 1-1.814.004" />
                                                <rect width="3" height="8" x="2" y="14" rx="1.5" />
                                            </g>
                                        </svg>

                                        Commission Report
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('course_completion_report', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.course-completion.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-focus new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M4 21q-.825 0-1.412-.587T2 19V5q0-.825.588-1.412T4 3h16q.825 0 1.413.588T22 5v14q0 .825-.587 1.413T20 21zm0-2h16V5H4zm1-2h5v-2H5zm9.55-2l4.95-4.95l-1.425-1.425l-3.525 3.55l-1.425-1.425l-1.4 1.425zM5 13h5v-2H5zm0-4h5V7H5zM4 19V5z" />
                                        </svg>
                                        Course Completion Report
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('report_template', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.template.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-dark new_btn_app">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M14 6h8v16h-8zM2 4h20V2H2zm0 4h10V6H2zm7 14h3V10H9zm-7 0h5V10H2z" />
                                        </svg>
                                        Report Templates
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('student_risk_report', 'view') == true || checkRole('student_report', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.risk.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-danger new_btn_app">
                                        <i class="fas fa-chart-line"></i>
                                        Student Risk Analysis
                                    </button>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if (checkRole('analytics_dashboard', 'view') == true)
                        <div class="single_btn">
                            <a class="grid_items" href="{{ route('admin.report.analytics.index') }}">
                                <div class="inner_grid">
                                    <button class="btn btn-outline-primary new_btn_app">
                                        <i class="fas fa-tachometer-alt"></i>
                                        Analytics Dashboard
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
            <h1><i class="fas fa-chart-bar"></i> Reports</h1>
            <p class="text-muted">View and export various reports across the platform.</p>
        </div>

        <div class="settings-grid">
            @if (checkRole('student_report', 'view') == true)
            <a href="{{ route('admin.report.student.index') }}" class="setting-card">
                <div class="setting-icon icon-primary"><i class="fas fa-user-graduate"></i></div>
                <span class="setting-label">Student Report</span>
            </a>
            @endif
            @if (checkRole('intake_report', 'view') == true)
            <a href="{{ route('admin.report.intake.index') }}" class="setting-card">
                <div class="setting-icon icon-secondary"><i class="fas fa-clipboard-list"></i></div>
                <span class="setting-label">Intake Report</span>
            </a>
            @endif
            @if (checkRole('agent_report', 'view') == true)
            <a href="{{ route('admin.report.agent.index') }}" class="setting-card">
                <div class="setting-icon icon-success"><i class="fas fa-user-tie"></i></div>
                <span class="setting-label">Agent Report</span>
            </a>
            @endif
            @if (checkRole('due_pays_report', 'view') == true)
            <a href="{{ route('admin.report.due.index') }}" class="setting-card">
                <div class="setting-icon icon-info"><i class="fas fa-exclamation-circle"></i></div>
                <span class="setting-label">Payment Due Report</span>
            </a>
            @endif
            @if (checkRole('fee_received_report', 'view') == true)
            <a href="{{ route('admin.report.fee.index') }}" class="setting-card">
                <div class="setting-icon icon-warning"><i class="fas fa-money-bill-wave"></i></div>
                <span class="setting-label">Fee Received Report</span>
            </a>
            @endif
            @if (checkRole('commission_report', 'view') == true)
            <a href="{{ route('admin.report.commission.index') }}" class="setting-card">
                <div class="setting-icon icon-danger"><i class="fas fa-hand-holding-usd"></i></div>
                <span class="setting-label">Commission Report</span>
            </a>
            @endif
            @if (checkRole('course_completion_report', 'view') == true)
            <a href="{{ route('admin.report.course-completion.index') }}" class="setting-card">
                <div class="setting-icon icon-purple"><i class="fas fa-check-double"></i></div>
                <span class="setting-label">Course Completion Report</span>
            </a>
            @endif
            @if (checkRole('report_template', 'view') == true)
            <a href="{{ route('admin.report.template.index') }}" class="setting-card">
                <div class="setting-icon icon-dark"><i class="fas fa-file-alt"></i></div>
                <span class="setting-label">Report Templates</span>
            </a>
            @endif
            @if (checkRole('student_risk_report', 'view') == true || checkRole('student_report', 'view') == true)
            <a href="{{ route('admin.report.risk.index') }}" class="setting-card">
                <div class="setting-icon icon-danger"><i class="fas fa-chart-line"></i></div>
                <span class="setting-label">Student Risk Analysis</span>
            </a>
            @endif
            @if (checkRole('analytics_dashboard', 'view') == true)
            <a href="{{ route('admin.report.analytics.index') }}" class="setting-card">
                <div class="setting-icon icon-primary"><i class="fas fa-tachometer-alt"></i></div>
                <span class="setting-label">Analytics Dashboard</span>
            </a>
            @endif
        </div>
    </div>
</section>
@endif
@endsection
