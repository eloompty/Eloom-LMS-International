@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Calendar')

@section('content')
<style>
    .calendar-shell {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 16px;
        align-items: start;
    }

    .calendar-panel,
    .agenda-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
    }

    .calendar-panel {
        overflow: hidden;
    }

    .calendar-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .calendar-toolbar h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .calendar-toolbar .calendar-meta {
        margin-top: 2px;
        color: #6b7280;
        font-size: 13px;
    }

    .calendar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .calendar-action,
    .calendar-view {
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
        height: 34px;
        padding: 0 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .calendar-action:hover,
    .calendar-view:hover,
    .calendar-view.active {
        border-color: #2563eb;
        color: #1d4ed8;
        background: #eff6ff;
    }

    #calendar {
        padding: 14px;
        min-height: 720px;
    }

    .fc {
        color: #111827;
    }

    .fc .fc-toolbar-title {
        font-size: 20px;
        font-weight: 800;
    }

    .fc .fc-button-primary {
        background: #2563eb;
        border-color: #2563eb;
        box-shadow: none;
    }

    .fc .fc-button-primary:not(:disabled):hover,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .fc-theme-standard td,
    .fc-theme-standard th,
    .fc-theme-standard .fc-scrollgrid {
        border-color: #e5e7eb;
    }

    .fc .fc-col-header-cell-cushion {
        color: #4b5563;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0;
        padding: 10px 4px;
    }

    .fc .fc-daygrid-day-number {
        color: #374151;
        font-weight: 700;
        padding: 8px;
    }

    .fc .fc-day-today {
        background: #eff6ff !important;
    }

    .fc-event {
        border-radius: 6px;
        border: 0;
        padding: 2px 4px;
        font-size: 12px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, .18);
    }

    .agenda-panel {
        padding: 14px;
    }

    .agenda-panel h3 {
        margin: 0 0 12px;
        font-size: 16px;
        font-weight: 800;
        color: #111827;
    }

    .agenda-list {
        display: grid;
        gap: 10px;
    }

    .agenda-item {
        display: block;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #111827;
        background: #fff;
    }

    .agenda-item:hover {
        color: #111827;
        border-color: #93c5fd;
        background: #f8fafc;
        text-decoration: none;
    }

    .agenda-time {
        color: #2563eb;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .agenda-title {
        margin-top: 4px;
        font-weight: 800;
        line-height: 1.3;
    }

    .agenda-course {
        margin-top: 4px;
        color: #6b7280;
        font-size: 13px;
    }

    .agenda-empty {
        color: #6b7280;
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        border-radius: 8px;
        padding: 14px;
        text-align: center;
    }

    .calendar-loading {
        padding: 16px;
        color: #6b7280;
        text-align: center;
    }

    @media (max-width: 991.98px) {
        .calendar-shell {
            grid-template-columns: 1fr;
        }

        #calendar {
            min-height: 620px;
        }
    }

    @media (max-width: 575.98px) {
        .calendar-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .calendar-actions {
            justify-content: flex-start;
        }

        .calendar-action,
        .calendar-view {
            flex: 1 1 auto;
        }

        #calendar {
            padding: 8px;
            min-height: 560px;
        }
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Calendar</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Calendar</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="calendar-shell">
            <div class="calendar-panel">
                <div class="calendar-toolbar">
                    <div>
                        <h3 id="calendar-title">Calendar</h3>
                        <div class="calendar-meta" id="calendar-meta">0 scheduled classes</div>
                    </div>
                    <div class="calendar-actions">
                        <button type="button" class="calendar-action" id="calendar-prev"><i class="fas fa-chevron-left"></i></button>
                        <button type="button" class="calendar-action" id="calendar-today">Today</button>
                        <button type="button" class="calendar-action" id="calendar-next"><i class="fas fa-chevron-right"></i></button>
                        <button type="button" class="calendar-view active" data-view="dayGridMonth">Month</button>
                        <button type="button" class="calendar-view" data-view="timeGridWeek">Week</button>
                        <button type="button" class="calendar-view" data-view="timeGridDay">Day</button>
                    </div>
                </div>
                <div id="calendar">
                    <div class="calendar-loading">Loading calendar...</div>
                </div>
            </div>

            <aside class="agenda-panel">
                <h3>Upcoming Classes</h3>
                <div class="agenda-list" id="agenda-list">
                    <div class="agenda-empty">No upcoming classes</div>
                </div>
            </aside>
        </div>
    </div>
</section>

<div class="modal fade" id="calendarEventModal" tabindex="-1" role="dialog" aria-labelledby="calendarEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarEventModalLabel">Class Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Unit</dt>
                    <dd class="col-sm-8" id="event-unit">-</dd>
                    <dt class="col-sm-4">Course</dt>
                    <dd class="col-sm-8" id="event-course">-</dd>
                    <dt class="col-sm-4">Date</dt>
                    <dd class="col-sm-8" id="event-date">-</dd>
                    <dt class="col-sm-4">Time</dt>
                    <dd class="col-sm-8" id="event-time">-</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary" id="event-link">Open Timetable</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var agendaEl = document.getElementById('agenda-list');
        var titleEl = document.getElementById('calendar-title');
        var metaEl = document.getElementById('calendar-meta');
        var cachedEvents = [];

        function formatTimeRange(event) {
            var start = event.start ? moment(event.start).format('h:mm A') : '';
            var end = event.end ? moment(event.end).format('h:mm A') : '';
            return end ? start + ' - ' + end : start;
        }

        function updateAgenda(events) {
            var now = new Date();
            var upcoming = events
                .filter(function(event) {
                    return event.start && event.start >= now;
                })
                .sort(function(a, b) {
                    return a.start - b.start;
                })
                .slice(0, 6);

            agendaEl.innerHTML = '';
            if (!upcoming.length) {
                agendaEl.innerHTML = '<div class="agenda-empty">No upcoming classes</div>';
                return;
            }

            upcoming.forEach(function(event) {
                var item = document.createElement('a');
                item.className = 'agenda-item';
                item.href = event.url || '#';
                item.innerHTML =
                    '<div class="agenda-time">' + moment(event.start).format('ddd, D MMM') + ' · ' + formatTimeRange(event) + '</div>' +
                    '<div class="agenda-title">' + event.title + '</div>' +
                    '<div class="agenda-course">' + (event.extendedProps.course || '') + '</div>';
                agendaEl.appendChild(item);
            });
        }

        function updateHeader(calendar) {
            titleEl.textContent = calendar.view.title;
            metaEl.textContent = cachedEvents.length + (cachedEvents.length === 1 ? ' scheduled class' : ' scheduled classes');
        }

        function setActiveView(viewName) {
            document.querySelectorAll('.calendar-view').forEach(function(button) {
                button.classList.toggle('active', button.getAttribute('data-view') === viewName);
            });
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'timeGridDay' : 'dayGridMonth',
            height: 'auto',
            expandRows: true,
            nowIndicator: true,
            navLinks: true,
            dayMaxEvents: 3,
            allDaySlot: false,
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            headerToolbar: false,
            eventDisplay: 'block',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            events: {
                url: "{{ url('trainer/calendar/time') }}",
                method: 'GET',
                failure: function() {
                    agendaEl.innerHTML = '<div class="agenda-empty">Unable to load calendar</div>';
                }
            },
            loading: function(isLoading) {
                if (isLoading) {
                    metaEl.textContent = 'Loading scheduled classes';
                }
            },
            eventsSet: function(events) {
                cachedEvents = events;
                updateHeader(calendar);
                updateAgenda(events);
            },
            datesSet: function() {
                updateHeader(calendar);
                setActiveView(calendar.view.type);
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                document.getElementById('event-unit').textContent = info.event.extendedProps.unit || info.event.title || '-';
                document.getElementById('event-course').textContent = info.event.extendedProps.course || '-';
                document.getElementById('event-date').textContent = info.event.start ? moment(info.event.start).format('dddd, D MMMM YYYY') : '-';
                document.getElementById('event-time').textContent = formatTimeRange(info.event) || '-';
                document.getElementById('event-link').setAttribute('href', info.event.url || '#');
                $('#calendarEventModal').modal('show');
            }
        });

        calendar.render();

        document.getElementById('calendar-prev').addEventListener('click', function() {
            calendar.prev();
        });
        document.getElementById('calendar-next').addEventListener('click', function() {
            calendar.next();
        });
        document.getElementById('calendar-today').addEventListener('click', function() {
            calendar.today();
        });
        document.querySelectorAll('.calendar-view').forEach(function(button) {
            button.addEventListener('click', function() {
                calendar.changeView(button.getAttribute('data-view'));
                setActiveView(button.getAttribute('data-view'));
            });
        });
    });
</script>
@endsection
