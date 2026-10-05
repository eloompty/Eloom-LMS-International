{{--
    Shared timetable-driven attendance marking UI (admin + trainer).
    Required vars:
      $title, $scope ('subject'|'unit'), $scopeId,
      $slots, $students (each ->id, ->name), $selectedSession, $existingMarks,
      $year, $month, $intakeYears, $hasAnyTimetable,
      $indexRoute, $markRoute (route names), $createTimetableUrl (url),
      $periodBase (url string), $yearAjaxUrl (url string)
--}}
<style>
    .att-status { display:inline-flex; border-radius:6px; overflow:hidden; border:1px solid #ced4da; }
    .att-status input { display:none; }
    .att-status label { margin:0; padding:.28rem .62rem; font-size:.8rem; font-weight:600; cursor:pointer; color:#495057; background:#fff; border-right:1px solid #ced4da; user-select:none; line-height:1.2; }
    .att-status label:last-child { border-right:0; }
    .att-status input:checked + label.att-present { background:#28a745; color:#fff; }
    .att-status input:checked + label.att-absent  { background:#dc3545; color:#fff; }
    .att-status input:checked + label.att-late    { background:#ffc107; color:#212529; }
    .att-status input:checked + label.att-excused { background:#17a2b8; color:#fff; }
    .att-roster td { vertical-align: middle; }
    .att-summary .badge { font-size:.85rem; padding:.4rem .6rem; }
    .session-row.active-session { background:#eef6ff !important; }
    .att-student-name { font-weight:600; }
    .att-mini-input { max-width:120px; }
</style>

<div id="attMarking" data-year-url="{{ $yearAjaxUrl }}" data-period-base="{{ $periodBase }}" data-scope-id="{{ $scopeId }}">
<div class="card card-outline card-primary">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <h3 class="card-title mb-0"><i class="far fa-calendar-alt mr-1"></i> Timetable Sessions</h3>
        <div class="d-flex align-items-center">
            <form action="" class="form-inline mr-2" id="periodForm">
                <input type="hidden" id="scopeId" value="{{ $scopeId }}">
                <select class="form-control form-control-sm mr-1" id="year">
                    <option value="" disabled>Year</option>
                    @foreach($intakeYears as $value)
                    <option @if($value==$year) selected @endif value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>
                <select class="form-control form-control-sm" id="month" data-current="{{ $month }}"></select>
            </form>
            <a href="{{ $createTimetableUrl }}" class="btn btn-sm btn-outline-primary">
                <i class="far fa-calendar-plus mr-1"></i> Manage Timetable
            </a>
        </div>
    </div>

    @unless($hasAnyTimetable)
    <div class="card-body">
        <div class="alert alert-warning mb-0">
            <h5 class="mb-1"><i class="icon fas fa-exclamation-triangle"></i> No timetable yet</h5>
            Attendance sessions come from the timetable. Create a timetable for this {{ $scope }} first —
            <a href="{{ $createTimetableUrl }}" class="alert-link">Create timetable</a>.
        </div>
    </div>
    @else
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th class="text-center">Marked</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slots as $slot)
                    <tr class="session-row @if($selectedSession && $selectedSession->intake_time_id == $slot->time_id) active-session @endif">
                        <td>{{ \Carbon\Carbon::parse($slot->date)->format('D, d M Y') }}</td>
                        <td>{{ $slot->day ?: '—' }}</td>
                        <td>
                            @if($slot->from){{ \Carbon\Carbon::parse($slot->from)->format('h:i A') }}@if($slot->to) – {{ \Carbon\Carbon::parse($slot->to)->format('h:i A') }}@endif
                            @else <span class="text-muted">—</span> @endif
                        </td>
                        <td>{{ $slot->classroom ?: '—' }}</td>
                        <td class="text-center">
                            @if($slot->marked_count > 0)
                                <span class="badge badge-success">{{ $slot->marked_count }} / {{ $students->count() }}</span>
                            @else
                                <span class="badge badge-secondary">Not marked</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route($indexRoute, [$scopeId, $year, $month]) }}?time={{ $slot->time_id }}#roster" class="btn btn-sm btn-primary">
                                <i class="fas fa-user-check mr-1"></i> Mark
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">
                        No timetable sessions for {{ \Carbon\Carbon::parse($year.'-'.$month.'-01')->format('F Y') }}.
                        <a href="{{ $createTimetableUrl }}">Add timetable dates</a>.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endunless
</div>

@if($selectedSession)
<div class="card card-outline card-success" id="roster">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <h3 class="card-title mb-0">
            <i class="fas fa-user-check mr-1"></i>
            Mark Attendance — {{ \Carbon\Carbon::parse($selectedSession->date)->format('D, d M Y') }}
            @if($selectedSession->starts_at) <span class="text-muted">· {{ \Carbon\Carbon::parse($selectedSession->starts_at)->format('h:i A') }}</span> @endif
        </h3>
        <div class="att-summary" id="attSummary"></div>
    </div>
    <form action="{{ route($markRoute, $selectedSession->id) }}" method="post">
        @csrf
        <div class="card-body">
            <div class="mb-3">
                <span class="text-muted mr-2">Quick set all:</span>
                <button type="button" class="btn btn-sm btn-outline-success bulk-set" data-status="present">All Present</button>
                <button type="button" class="btn btn-sm btn-outline-danger bulk-set" data-status="absent">All Absent</button>
                <button type="button" class="btn btn-sm btn-outline-warning bulk-set" data-status="late">All Late</button>
            </div>

            @if($students->isEmpty())
            <p class="text-muted">No students are enrolled in this {{ $scope }}.</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered att-roster">
                    <thead class="thead-light">
                        <tr>
                            <th style="min-width:180px">Student</th>
                            <th class="text-center" style="min-width:230px">Status</th>
                            <th style="min-width:110px">Left early</th>
                            <th style="min-width:200px">Remarks <small class="text-muted">(internal)</small></th>
                            <th style="min-width:240px">Feedback <small class="text-muted">(to student)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        @php
                            $sid = $student->id;
                            $mark = $existingMarks[$sid] ?? null;
                            $current = $mark->attendance_status ?? 'present';
                        @endphp
                        <tr>
                            <td class="att-student-name">{{ $student->name }}</td>
                            <td class="text-center">
                                <div class="att-status" role="group">
                                    <input type="radio" id="st_{{ $sid }}_p" name="status[{{ $sid }}]" value="present" @if($current=='present') checked @endif>
                                    <label for="st_{{ $sid }}_p" class="att-present">Present</label>
                                    <input type="radio" id="st_{{ $sid }}_a" name="status[{{ $sid }}]" value="absent" @if($current=='absent') checked @endif>
                                    <label for="st_{{ $sid }}_a" class="att-absent">Absent</label>
                                    <input type="radio" id="st_{{ $sid }}_l" name="status[{{ $sid }}]" value="late" @if($current=='late') checked @endif>
                                    <label for="st_{{ $sid }}_l" class="att-late">Late</label>
                                    <input type="radio" id="st_{{ $sid }}_e" name="status[{{ $sid }}]" value="excused" @if($current=='excused') checked @endif>
                                    <label for="st_{{ $sid }}_e" class="att-excused">Excused</label>
                                </div>
                            </td>
                            <td>
                                <input type="time" name="left_early[{{ $sid }}]" class="form-control form-control-sm att-mini-input" value="{{ $mark->left_early_at ?? '' }}">
                            </td>
                            <td>
                                <input type="text" name="remarks[{{ $sid }}]" class="form-control form-control-sm" value="{{ $mark->remarks ?? '' }}" placeholder="Internal note">
                            </td>
                            <td>
                                <input type="text" name="feedback[{{ $sid }}]" class="form-control form-control-sm" value="{{ $mark->feedback ?? '' }}" placeholder="Shared with student when visible">
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="fv_{{ $sid }}" name="feedback_visible[{{ $sid }}]" value="1" @if(!empty($mark) && $mark->feedback_visible_to_student) checked @endif>
                                    <label class="custom-control-label small" for="fv_{{ $sid }}">Visible to student</label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @unless($students->isEmpty())
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Save Attendance</button>
        </div>
        @endunless
    </form>
</div>
@endif
</div>{{-- /#attMarking --}}
