@extends('student::student.layouts.master')
@section('title', 'Student | Dashboard')

@section('content')
<style>
    /* ── Stat Cards ── */
    .stat-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(15,23,42,.06);
        overflow: hidden;
        transition: transform .15s, box-shadow .15s;
        margin-bottom: 0;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(15,23,42,.10);
    }
    .stat-card .card-body {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
    }
    .stat-icon-blue   { background: #eff6ff; color: #2563eb; }
    .stat-icon-violet { background: #f5f3ff; color: #7c3aed; }
    .stat-icon-green  { background: #f0fdf4; color: #16a34a; }
    .stat-info { flex: 1; min-width: 0; }
    .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 30px;
        font-weight: 800;
        color: #111827;
        line-height: 1;
    }
    .stat-value a { color: #111827; }
    .stat-value a:hover { color: #2563eb; text-decoration: none; }
    .stat-footer {
        padding: 8px 20px;
        background: #f9fafb;
        border-top: 1px solid #f1f5f9;
        font-size: 12px;
        color: #6b7280;
    }
    .stat-footer a { color: #2563eb; font-weight: 600; text-decoration: none; }
    .stat-footer a:hover { color: #1d4ed8; }

    /* ── Section Cards ── */
    .section-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15,23,42,.05);
        margin-bottom: 18px;
        background: #fff;
    }
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 13px 18px;
        border-bottom: 1px solid #e5e7eb;
    }
    .section-header-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .title-icon {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }
    .title-icon-blue  { background: #eff6ff; color: #2563eb; }
    .title-icon-amber { background: #fffbeb; color: #d97706; }
    .section-action-link {
        font-size: 12px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
    }
    .section-action-link:hover { color: #1d4ed8; text-decoration: none; }

    /* ── Notification Feed ── */
    .notif-feed { list-style: none; margin: 0; padding: 0; }
    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 11px 18px;
        border-bottom: 1px solid #f1f5f9;
        transition: background .1s;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: #f8fafc; }
    .notif-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
    }
    .notif-dot-blue  { background: #2563eb; }
    .notif-dot-amber { background: #d97706; }
    .notif-body { flex: 1; min-width: 0; }
    .notif-title {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .notif-meta { font-size: 11px; color: #6b7280; }
    .notif-action { flex-shrink: 0; }
    .notif-action .btn { font-size: 11px; padding: 2px 9px; border-radius: 5px; }

    /* ── Assessment Table ── */
    .assessment-table th {
        background: #f8fafc;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #6b7280;
        border-top: none;
        padding: 9px 12px;
        white-space: nowrap;
    }
    .assessment-table td {
        font-size: 13px;
        color: #374151;
        padding: 9px 12px;
        vertical-align: middle;
    }
    .assessment-table tbody tr:hover { background: #f9fafb; }
    .badge-due {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        background: #fef3c7;
        color: #92400e;
        font-weight: 600;
    }
    .assessment-name { font-size: 12px; font-weight: 700; margin-top: 2px; }
    .assessment-type-img { width: 30px; height: 30px; object-fit: contain; }

    /* ── Empty State ── */
    .empty-state {
        padding: 30px 16px;
        text-align: center;
        color: #9ca3af;
    }
    .empty-state i { font-size: 28px; margin-bottom: 6px; opacity: .35; display: block; }
    .empty-state p { font-size: 13px; margin: 0; }

    /* ── Calendar Card ── */
    .dashboard-calendar-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15,23,42,.05);
        background: #fff;
        margin-bottom: 18px;
    }
    .dashboard-calendar-card .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        padding: 13px 18px;
    }
    .dashboard-calendar-card .card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .dashboard-calendar-tools {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .dashboard-calendar-btn {
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        height: 27px;
        padding: 0 9px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .dashboard-calendar-btn:hover,
    .dashboard-calendar-btn.active {
        border-color: #2563eb;
        background: #eff6ff;
        color: #1d4ed8;
        text-decoration: none;
    }
    .dashboard-calendar-body { padding: 14px; }
    #student-dashboard-calendar { min-height: 360px; }
    #student-dashboard-calendar .fc { color: #111827; }
    #student-dashboard-calendar .fc-toolbar-title { font-size: 14px; font-weight: 800; }
    #student-dashboard-calendar .fc-theme-standard td,
    #student-dashboard-calendar .fc-theme-standard th,
    #student-dashboard-calendar .fc-theme-standard .fc-scrollgrid { border-color: #e5e7eb; }
    #student-dashboard-calendar .fc-col-header-cell-cushion { color: #4b5563; font-size: 10px; text-transform: uppercase; padding: 6px 2px; }
    #student-dashboard-calendar .fc-daygrid-day-number { color: #374151; font-size: 12px; font-weight: 700; padding: 4px; }
    #student-dashboard-calendar .fc-day-today { background: #eff6ff !important; }
    #student-dashboard-calendar .fc-event { border: 0; border-radius: 4px; padding: 1px 4px; font-size: 11px; }

    .dashboard-agenda { border-top: 1px solid #f1f5f9; padding-top: 10px; margin-top: 10px; }
    .dashboard-agenda-title {
        margin: 0 0 7px;
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .dashboard-agenda-list { display: grid; gap: 5px; }
    .dashboard-agenda-item {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        padding: 7px 10px;
        color: #111827;
        background: #fff;
        text-decoration: none;
    }
    .dashboard-agenda-item:hover { color: #111827; text-decoration: none; border-color: #93c5fd; background: #f8fafc; }
    .agenda-dot { width: 7px; height: 7px; border-radius: 50%; background: #2563eb; flex-shrink: 0; }
    .dashboard-agenda-time { color: #2563eb; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .dashboard-agenda-name { font-size: 12px; font-weight: 700; line-height: 1.3; }
    .dashboard-agenda-empty {
        padding: 12px;
        border: 1px dashed #e5e7eb;
        border-radius: 7px;
        color: #9ca3af;
        text-align: center;
        font-size: 12px;
        background: #f9fafb;
    }

    @media (max-width: 575.98px) {
        .dashboard-calendar-card .card-header { flex-direction: column; align-items: stretch; }
        .dashboard-calendar-tools { justify-content: flex-start; }
        .dashboard-calendar-btn { flex: 1 1 auto; justify-content: center; }
        #student-dashboard-calendar { min-height: 300px; }
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center py-1">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:20px;font-weight:800;color:#111827;line-height:1.2;">Dashboard</h1>
                <p class="m-0 mt-1" style="font-size:13px;color:#6b7280;">Welcome back, {{ userName('Student', Auth::guard('student')->user()->id) }}</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0" style="background:none;padding:0;">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" style="color:#2563eb;">Home</a></li>
                    <li class="breadcrumb-item active" style="color:#6b7280;">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <!-- Stat Cards -->
        <div class="row mb-1">
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-icon stat-icon-blue"><i class="fas fa-graduation-cap"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Enrolled Courses</div>
                            <div class="stat-value"><a href="{{ route('student.course.index') }}">{{ $total_courses }}</a></div>
                        </div>
                    </div>
                    <div class="stat-footer"><a href="{{ route('student.course.index') }}">View all courses <i class="fas fa-arrow-right fa-xs"></i></a></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-icon stat-icon-violet"><i class="fas fa-book-open"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Units</div>
                            <div class="stat-value">{{ $total_units }}</div>
                        </div>
                    </div>
                    <div class="stat-footer"><span>Active training units</span></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-icon stat-icon-green"><i class="fas fa-tasks"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Assessments</div>
                            <div class="stat-value">{{ $total_assignments }}</div>
                        </div>
                    </div>
                    <div class="stat-footer"><span>Total assessments assigned</span></div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row">

            <!-- Calendar -->
            <div class="col-md-6">
                <div class="dashboard-calendar-card">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="title-icon title-icon-blue"><i class="far fa-calendar-alt"></i></span>
                            Calendar
                        </div>
                        <div class="dashboard-calendar-tools">
                            <button type="button" class="dashboard-calendar-btn" id="dashboard-calendar-prev"><i class="fas fa-chevron-left"></i></button>
                            <button type="button" class="dashboard-calendar-btn" id="dashboard-calendar-today">Today</button>
                            <button type="button" class="dashboard-calendar-btn" id="dashboard-calendar-next"><i class="fas fa-chevron-right"></i></button>
                            <button type="button" class="dashboard-calendar-btn active" data-dashboard-view="dayGridMonth">Month</button>
                            <button type="button" class="dashboard-calendar-btn" data-dashboard-view="timeGridWeek">Week</button>
                            <a href="{{ route('student.calendar.index') }}" class="dashboard-calendar-btn">Full View</a>
                        </div>
                    </div>
                    <div class="card-body dashboard-calendar-body">
                        <div id="student-dashboard-calendar">
                            <div class="dashboard-agenda-empty">Loading calendar...</div>
                        </div>
                        <div class="dashboard-agenda">
                            <p class="dashboard-agenda-title">Upcoming Classes</p>
                            <div class="dashboard-agenda-list" id="dashboard-agenda-list">
                                <div class="dashboard-agenda-empty">No upcoming classes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">

                <!-- Notifications -->
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-header-title">
                            <span class="title-icon title-icon-blue"><i class="fas fa-bell"></i></span>
                            Latest Notifications
                        </h3>
                        <a href="{{ route('student.notification.index') }}" class="section-action-link">View all</a>
                    </div>
                    @if(count($notifications) > 0)
                    <ul class="notif-feed">
                        @foreach($notifications as $value)
                        <li class="notif-item">
                            <span class="notif-dot {{ $value->type == 'Assignment' ? 'notif-dot-blue' : 'notif-dot-amber' }}"></span>
                            <div class="notif-body">
                                <div class="notif-title">{{ $value->title }}</div>
                                <div class="notif-meta">{{ $value->body }} &middot; {{ dateFormat($value->created_at) }}</div>
                            </div>
                            <div class="notif-action">
                                @if ($value->type == 'Assignment')
                                <a href="{{ $value->assignment_url }}" class="btn btn-outline-primary btn-sm">View</a>
                                @elseif ($value->type == 'OnlineClass')
                                @php $url = 'https://us06web.zoom.us/j/' . $value->link @endphp
                                <a href="{{ $url }}" class="btn btn-outline-success btn-sm" target="_blank">Join</a>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <p>No new notifications</p>
                    </div>
                    @endif
                </div>

                <!-- Announcements -->
                @if($dashAnnouncements->isNotEmpty())
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-header-title">
                            <span class="title-icon" style="background:#fef2f2;color:#dc2626"><i class="fas fa-bullhorn"></i></span>
                            Announcements
                        </h3>
                        <a href="{{ route('student.announcement.index') }}" class="section-action-link">View all</a>
                    </div>
                    <ul class="notif-feed">
                        @foreach($dashAnnouncements as $ann)
                        @php
                            $pCls   = ['urgent'=>'danger','important'=>'warning','normal'=>'secondary'][$ann->priority] ?? 'secondary';
                            $annAck = in_array($ann->id, $dashAcknowledgedIds);
                        @endphp
                        <li class="notif-item">
                            @if($ann->priority === 'urgent')
                            <span class="notif-dot" style="background:#dc2626"></span>
                            @elseif($ann->priority === 'important')
                            <span class="notif-dot" style="background:#d97706"></span>
                            @else
                            <span class="notif-dot" style="background:#9ca3af"></span>
                            @endif
                            <div class="notif-body">
                                <div class="notif-title d-flex align-items-center" style="gap:6px;">
                                    {{ $ann->title }}
                                    @if($annAck)
                                    <span class="badge badge-success" style="font-size:9px;font-weight:800;border-radius:999px;padding:2px 6px;white-space:nowrap;"><i class="fas fa-check mr-1"></i>Read</span>
                                    @endif
                                </div>
                                <div class="notif-meta">
                                    <span class="badge badge-{{ $pCls }} mr-1" style="font-size:10px">{{ $ann->priority }}</span>
                                    {{ Str::limit(strip_tags($ann->body), 80) }}
                                </div>
                                @if($ann->require_acknowledgement && !$annAck)
                                <form method="POST" action="{{ route('student.announcement.acknowledge', $ann->id) }}" class="mt-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-xs"
                                            style="font-size:11px;font-weight:800;border-radius:5px;padding:3px 10px;line-height:1.4;">
                                        <i class="fas fa-check mr-1"></i>Mark as Read
                                    </button>
                                </form>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Upcoming Events -->
                @if($upcomingEvents->isNotEmpty())
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-header-title">
                            <span class="title-icon" style="background:#f0fdf4;color:#16a34a"><i class="fas fa-calendar-alt"></i></span>
                            Upcoming Events
                        </h3>
                        <a href="{{ route('student.event.index') }}" class="section-action-link">View all</a>
                    </div>
                    <ul class="notif-feed">
                        @foreach($upcomingEvents as $ev)
                        @php
                            $myReg = $ev->registrations->first();
                        @endphp
                        <li class="notif-item">
                            <span class="notif-dot" style="background:#16a34a"></span>
                            <div class="notif-body">
                                <div class="notif-title">{{ $ev->title }}</div>
                                <div class="notif-meta">
                                    <span class="badge badge-info mr-1" style="font-size:10px">{{ $ev->type }}</span>
                                    {{ $ev->registration_deadline ? 'Closes '.dateFormat($ev->registration_deadline) : 'Open registration' }}
                                    &middot; {{ $ev->confirmed_registrations_count }} registered
                                </div>
                            </div>
                            <div class="notif-action">
                                @if($myReg)
                                    <span class="badge badge-success" style="font-size:10px">{{ ucfirst($myReg->status) }}</span>
                                @else
                                    <a href="{{ route('student.event.index') }}" class="btn btn-outline-success btn-sm">Register</a>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Pending Surveys -->
                @if($pendingSurveys->isNotEmpty())
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-header-title">
                            <span class="title-icon" style="background:#f5f3ff;color:#7c3aed"><i class="fas fa-poll"></i></span>
                            Surveys Awaiting Response
                        </h3>
                        <a href="{{ route('student.survey.index') }}" class="section-action-link">View all</a>
                    </div>
                    <ul class="notif-feed">
                        @foreach($pendingSurveys as $sv)
                        <li class="notif-item">
                            <span class="notif-dot" style="background:#7c3aed"></span>
                            <div class="notif-body">
                                <div class="notif-title">{{ optional($sv->template)->name ?? 'Survey' }}</div>
                                <div class="notif-meta">
                                    {{ $sv->closes_at ? 'Closes '.dateFormat($sv->closes_at) : 'No closing date' }}
                                </div>
                            </div>
                            <div class="notif-action">
                                <a href="{{ route('student.survey.take', $sv->id) }}" class="btn btn-outline-primary btn-sm">Take</a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Due Assessments -->
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-header-title">
                            <span class="title-icon title-icon-amber"><i class="fas fa-clipboard-list"></i></span>
                            Due Assessments
                        </h3>
                    </div>
                    @if(count($due_submissions) > 0)
                    <div class="table-responsive">
                        <table class="table assessment-table m-0">
                            <thead>
                                <tr>
                                    <th>Assessment</th>
                                    <th>Faculty</th>
                                    <th>Due Date</th>
                                    <th>Grade</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($due_submissions as $value)
                                <tr>
                                    <td>
                                        @if ($value->type == 'file')
                                        <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" class="assessment-type-img" alt=""></a>
                                        @elseif ($value->type == 'question')
                                        <a href="{{ route('student.assignment.question.index', [$value->id, $value->student_intake_id]) }}"><img src="{{ asset('files/qa.png') }}" class="assessment-type-img" alt="Q&A"></a>
                                        @else
                                        <a href="{{ route('student.assignment.mcq.index', [$value->id, $value->student_intake_id]) }}"><img src="{{ asset('files/mcq.png') }}" class="assessment-type-img" alt="MCQ"></a>
                                        @endif
                                        <div class="assessment-name">{{ $value->name }}</div>
                                    </td>
                                    <td>@if ($value->trainer_id == NULL) &mdash; @else {{ userName('Trainer', $value->trainer_id) }} @endif</td>
                                    <td>
                                        <span class="badge-due">
                                            @if ($value->intakeUnit->due_date == NULL)
                                            {{ dateFormat($value->due_date) }}
                                            @else
                                            {{ dateFormat($value->intakeUnit->due_date) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td style="color:#6b7280;">{{ $value->grade ?? '—' }}</td>
                                    <td>
                                        @if ($value->type == 'file')
                                        <a href="{{ asset($value->path) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="View"><i class="fas fa-eye"></i></a>
                                        @elseif ($value->type == 'question')
                                        <a href="{{ route('student.assignment.question.index', [$value->id, $value->student_intake_id]) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="View"><i class="fas fa-eye"></i></a>
                                        @else
                                        <a href="{{ route('student.assignment.mcq.index', [$value->id, $value->student_intake_id]) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="View"><i class="fas fa-eye"></i></a>
                                        @endif
                                        <a href="{{ route('student.submission.index', [$value->id, $value->student_intake_id]) }}" class="btn btn-sm btn-outline-secondary" title="Submissions"><i class="fas fa-upload"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>No assessments currently due</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('student-dashboard-calendar');
        var agendaEl = document.getElementById('dashboard-agenda-list');

        function formatTimeRange(event) {
            var start = event.start ? moment(event.start).format('h:mm A') : '';
            var end = event.end ? moment(event.end).format('h:mm A') : '';
            return end ? start + ' - ' + end : start;
        }

        function setActiveView(viewName) {
            document.querySelectorAll('[data-dashboard-view]').forEach(function(btn) {
                btn.classList.toggle('active', btn.getAttribute('data-dashboard-view') === viewName);
            });
        }

        function updateAgenda(events) {
            var now = new Date();
            var upcoming = events
                .filter(function(e) { return e.start && e.start >= now; })
                .sort(function(a, b) { return a.start - b.start; })
                .slice(0, 4);

            agendaEl.innerHTML = '';
            if (!upcoming.length) {
                agendaEl.innerHTML = '<div class="dashboard-agenda-empty">No upcoming classes</div>';
                return;
            }
            upcoming.forEach(function(event) {
                var item = document.createElement('a');
                item.className = 'dashboard-agenda-item';
                item.href = event.url || '#';
                item.innerHTML =
                    '<span class="agenda-dot"></span>' +
                    '<div>' +
                    '<div class="dashboard-agenda-time">' + moment(event.start).format('ddd, D MMM') + ' &middot; ' + formatTimeRange(event) + '</div>' +
                    '<div class="dashboard-agenda-name">' + event.title + '</div>' +
                    '</div>';
                agendaEl.appendChild(item);
            });
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'timeGridWeek' : 'dayGridMonth',
            height: 'auto',
            expandRows: true,
            nowIndicator: true,
            dayMaxEvents: 2,
            allDaySlot: false,
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            headerToolbar: { left: '', center: 'title', right: '' },
            eventDisplay: 'block',
            eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
            events: {
                url: "{{ url('student/calendar/time') }}",
                method: 'GET',
                failure: function() {
                    agendaEl.innerHTML = '<div class="dashboard-agenda-empty">Unable to load calendar</div>';
                }
            },
            eventsSet: function(events) { updateAgenda(events); },
            datesSet: function() { setActiveView(calendar.view.type); },
            eventClick: function(info) {
                if (info.event.url) { info.jsEvent.preventDefault(); window.location = info.event.url; }
            }
        });

        calendar.render();

        document.getElementById('dashboard-calendar-prev').addEventListener('click', function() { calendar.prev(); });
        document.getElementById('dashboard-calendar-next').addEventListener('click', function() { calendar.next(); });
        document.getElementById('dashboard-calendar-today').addEventListener('click', function() { calendar.today(); });
        document.querySelectorAll('[data-dashboard-view]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                calendar.changeView(btn.getAttribute('data-dashboard-view'));
                setActiveView(btn.getAttribute('data-dashboard-view'));
            });
        });
    });
</script>
@endsection
