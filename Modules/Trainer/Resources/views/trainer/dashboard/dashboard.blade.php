@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Dashboard')

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
    .stat-icon-amber  { background: #fffbeb; color: #d97706; }
    .stat-icon-red    { background: #fef2f2; color: #dc2626; }
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
    .title-icon-green { background: #f0fdf4; color: #16a34a; }
    .section-footer {
        display: flex;
        gap: 8px;
        padding: 10px 18px;
        border-top: 1px solid #f1f5f9;
        background: #f9fafb;
    }
    .section-action-link {
        font-size: 12px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
    }
    .section-action-link:hover { color: #1d4ed8; text-decoration: none; }

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
    .badge-graded {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        background: #f0fdf4;
        color: #166534;
        font-weight: 600;
    }
    .badge-pending {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        background: #fef3c7;
        color: #92400e;
        font-weight: 600;
    }

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
    #trainer-dashboard-calendar { min-height: 360px; }
    #trainer-dashboard-calendar .fc { color: #111827; }
    #trainer-dashboard-calendar .fc-toolbar-title { font-size: 14px; font-weight: 800; }
    #trainer-dashboard-calendar .fc-theme-standard td,
    #trainer-dashboard-calendar .fc-theme-standard th,
    #trainer-dashboard-calendar .fc-theme-standard .fc-scrollgrid { border-color: #e5e7eb; }
    #trainer-dashboard-calendar .fc-col-header-cell-cushion { color: #4b5563; font-size: 10px; text-transform: uppercase; padding: 6px 2px; }
    #trainer-dashboard-calendar .fc-daygrid-day-number { color: #374151; font-size: 12px; font-weight: 700; padding: 4px; }
    #trainer-dashboard-calendar .fc-day-today { background: #eff6ff !important; }
    #trainer-dashboard-calendar .fc-event { border: 0; border-radius: 4px; padding: 1px 4px; font-size: 11px; }

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
        #trainer-dashboard-calendar { min-height: 300px; }
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center py-1">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:20px;font-weight:800;color:#111827;line-height:1.2;">Dashboard</h1>
                <p class="m-0 mt-1" style="font-size:13px;color:#6b7280;">Welcome back, {{ userName('Trainer', Auth::guard('trainer')->user()->id) }}</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0" style="background:none;padding:0;">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}" style="color:#2563eb;">Home</a></li>
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
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-icon stat-icon-blue"><i class="fas fa-graduation-cap"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Courses</div>
                            <div class="stat-value"><a href="{{ route('trainer.course.index') }}">{{ $total_courses }}</a></div>
                        </div>
                    </div>
                    <div class="stat-footer"><a href="{{ route('trainer.course.index') }}">View courses <i class="fas fa-arrow-right fa-xs"></i></a></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 mb-3">
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
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-icon stat-icon-green"><i class="fas fa-user-graduate"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Students</div>
                            <div class="stat-value"><a href="{{ route('trainer.students.index') }}">{{ $total_students }}</a></div>
                        </div>
                    </div>
                    <div class="stat-footer"><a href="{{ route('trainer.students.index') }}">View students <i class="fas fa-arrow-right fa-xs"></i></a></div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-icon stat-icon-amber"><i class="fas fa-tasks"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Assignments</div>
                            <div class="stat-value">{{ $total_assignments }}</div>
                        </div>
                    </div>
                    <div class="stat-footer"><a href="{{ route('trainer.assignments.index') }}">View assignments <i class="fas fa-arrow-right fa-xs"></i></a></div>
                </div>
            </div>
            @if($pendingAppealsCount > 0)
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-icon stat-icon-red"><i class="fas fa-flag"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Grade Appeals</div>
                            <div class="stat-value">{{ $pendingAppealsCount }}</div>
                        </div>
                    </div>
                    <div class="stat-footer"><a href="{{ route('trainer.gradebook.appeals') }}">Review appeals <i class="fas fa-arrow-right fa-xs"></i></a></div>
                </div>
            </div>
            @endif
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
                            <button type="button" class="dashboard-calendar-btn" id="trainer-dashboard-calendar-prev"><i class="fas fa-chevron-left"></i></button>
                            <button type="button" class="dashboard-calendar-btn" id="trainer-dashboard-calendar-today">Today</button>
                            <button type="button" class="dashboard-calendar-btn" id="trainer-dashboard-calendar-next"><i class="fas fa-chevron-right"></i></button>
                            <button type="button" class="dashboard-calendar-btn active" data-trainer-dashboard-view="dayGridMonth">Month</button>
                            <button type="button" class="dashboard-calendar-btn" data-trainer-dashboard-view="timeGridWeek">Week</button>
                            <a href="{{ route('trainer.calendar.index') }}" class="dashboard-calendar-btn">Full View</a>
                        </div>
                    </div>
                    <div class="card-body dashboard-calendar-body">
                        <div id="trainer-dashboard-calendar">
                            <div class="dashboard-agenda-empty">Loading calendar...</div>
                        </div>
                        <div class="dashboard-agenda">
                            <p class="dashboard-agenda-title">Upcoming Classes</p>
                            <div class="dashboard-agenda-list" id="trainer-dashboard-agenda-list">
                                <div class="dashboard-agenda-empty">No upcoming classes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-header-title">
                            <span class="title-icon title-icon-green"><i class="fas fa-clipboard-check"></i></span>
                            Latest Submissions
                        </h3>
                    </div>
                    @if(count($submissions) > 0)
                    <div class="table-responsive">
                        <table class="table assessment-table m-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Due Date</th>
                                    <th>Submitted</th>
                                    <th>Grade</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $value)
                                <tr>
                                    <td style="font-weight:600;">{{ userName('Student', $value->student_id) }}</td>
                                    <td style="color:#6b7280;">{{ dateFormat($value->assignment->due_date) }}</td>
                                    <td style="color:#6b7280;">{{ dateFormat($value->created_at) }}</td>
                                    <td>
                                        @if ($value->assignment_grade_id == NULL)
                                        <span class="badge-pending">Pending</span>
                                        @else
                                        <span class="badge-graded">{{ $value->assignmentGrade->name }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $isPdfSubmission = $value->assignment->type == 'file' && filePath($value->path) == 'files/pdf.png';
                                        @endphp
                                        @if ($value->assignment->type == 'question' && $value->submission_question_route && $value->trainer_intake_id)
                                        <a href="{{ route($value->submission_question_route, [$value->id, $value->trainer_intake_id]) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="fas fa-eye"></i></a>
                                        @elseif ($isPdfSubmission && $value->submission_pdf_route)
                                        <a href="{{ route($value->submission_pdf_route, [$value->id, 'student']) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="fas fa-eye"></i></a>
                                        @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary" title="View not available" disabled><i class="fas fa-eye"></i></button>
                                        @endif

                                        @if ($value->submission_edit_route && $value->trainer_intake_id)
                                        <a href="{{ route($value->submission_edit_route, [$value->id, $value->trainer_intake_id]) }}" class="btn btn-sm btn-outline-primary" title="Grade"><i class="fas fa-pencil-alt"></i></a>
                                        @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary" title="Trainer intake not found" disabled><i class="fas fa-pencil-alt"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="section-footer">
                        <a href="{{ route('trainer.submissions.index') }}" class="section-action-link">View all submissions <i class="fas fa-arrow-right fa-xs"></i></a>
                        <span style="color:#d1d5db;margin:0 4px;">|</span>
                        <a href="{{ route('trainer.assignments.index') }}" class="section-action-link">View all assessments <i class="fas fa-arrow-right fa-xs"></i></a>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No submissions to review</p>
                    </div>
                    @endif
                </div>

                <!-- Announcements -->
                @if($trainerAnnouncements->isNotEmpty())
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="section-header-title">
                            <span class="title-icon" style="background:#fef2f2;color:#dc2626"><i class="fas fa-bullhorn"></i></span>
                            Announcements
                        </h3>
                        <a href="{{ route('trainer.announcement.index') }}" class="section-action-link">View all</a>
                    </div>
                    <ul style="list-style:none;margin:0;padding:0">
                        @foreach($trainerAnnouncements as $ann)
                        @php
                            $pCls   = ['urgent'=>'danger','important'=>'warning','normal'=>'secondary'][$ann->priority] ?? 'secondary';
                            $annAck = in_array($ann->id, $trainerAcknowledgedIds);
                        @endphp
                        <li style="display:flex;align-items:flex-start;gap:12px;padding:11px 18px;border-bottom:1px solid #f1f5f9">
                            @if($ann->priority === 'urgent')
                            <span style="width:7px;height:7px;border-radius:50%;margin-top:5px;flex-shrink:0;background:#dc2626"></span>
                            @elseif($ann->priority === 'important')
                            <span style="width:7px;height:7px;border-radius:50%;margin-top:5px;flex-shrink:0;background:#d97706"></span>
                            @else
                            <span style="width:7px;height:7px;border-radius:50%;margin-top:5px;flex-shrink:0;background:#9ca3af"></span>
                            @endif
                            <div style="flex:1;min-width:0">
                                <div style="font-size:13px;font-weight:700;color:#111827;margin-bottom:1px;display:flex;align-items:center;gap:6px;">
                                    {{ $ann->title }}
                                    @if($annAck)
                                    <span class="badge badge-success" style="font-size:9px;font-weight:800;border-radius:999px;padding:2px 6px;white-space:nowrap;"><i class="fas fa-check mr-1"></i>Read</span>
                                    @endif
                                </div>
                                <div style="font-size:11px;color:#6b7280">
                                    <span class="badge badge-{{ $pCls }} mr-1" style="font-size:10px">{{ $ann->priority }}</span>
                                    {{ Str::limit(strip_tags($ann->body), 80) }}
                                </div>
                                @if($ann->require_acknowledgement && !$annAck)
                                <form method="POST" action="{{ route('trainer.announcement.acknowledge', $ann->id) }}" class="mt-1">
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

            </div>{{-- /col-md-6 right --}}

        </div>{{-- /row --}}
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('trainer-dashboard-calendar');
        var agendaEl = document.getElementById('trainer-dashboard-agenda-list');

        function formatTimeRange(event) {
            var start = event.start ? moment(event.start).format('h:mm A') : '';
            var end = event.end ? moment(event.end).format('h:mm A') : '';
            return end ? start + ' - ' + end : start;
        }

        function setActiveView(viewName) {
            document.querySelectorAll('[data-trainer-dashboard-view]').forEach(function(btn) {
                btn.classList.toggle('active', btn.getAttribute('data-trainer-dashboard-view') === viewName);
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
                url: "{{ url('trainer/calendar/time') }}",
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

        document.getElementById('trainer-dashboard-calendar-prev').addEventListener('click', function() { calendar.prev(); });
        document.getElementById('trainer-dashboard-calendar-next').addEventListener('click', function() { calendar.next(); });
        document.getElementById('trainer-dashboard-calendar-today').addEventListener('click', function() { calendar.today(); });
        document.querySelectorAll('[data-trainer-dashboard-view]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                calendar.changeView(btn.getAttribute('data-trainer-dashboard-view'));
                setActiveView(btn.getAttribute('data-trainer-dashboard-view'));
            });
        });
    });
</script>
@endsection
