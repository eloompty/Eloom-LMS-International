{{-- Shared student attendance display. Vars: $records, $summary, $title --}}
<style>
    .att-chip { font-size:.78rem; font-weight:700; padding:.28rem .6rem; border-radius:20px; color:#fff; }
    .att-chip.present { background:#28a745; }
    .att-chip.absent  { background:#dc3545; }
    .att-chip.late    { background:#ffc107; color:#212529; }
    .att-chip.excused { background:#17a2b8; }
    .att-pct { font-size:2.2rem; font-weight:700; line-height:1; }
    .att-feedback { background:#eef7ff; border-left:3px solid #17a2b8; padding:.35rem .6rem; border-radius:4px; font-size:.85rem; }
</style>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card card-outline card-info h-100">
            <div class="card-body text-center">
                <div class="text-muted mb-1">Attendance</div>
                <div class="att-pct {{ ($summary['percentage'] ?? 0) >= 80 ? 'text-success' : (($summary['percentage'] ?? 0) >= 60 ? 'text-warning' : 'text-danger') }}">
                    {{ $summary['percentage'] !== null ? $summary['percentage'].'%' : '—' }}
                </div>
                <div class="small text-muted mt-2">
                    Attended {{ $summary['attended'] }} of {{ $summary['required'] }} required sessions
                </div>
                <div class="mt-2">
                    <span class="badge badge-warning">Late {{ $summary['late'] }}</span>
                    <span class="badge badge-info">Excused {{ $summary['excused'] }}</span>
                </div>
                <div class="small text-muted mt-2">Excused sessions are not counted against you.</div>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-3">
        <div class="card card-outline card-primary h-100">
            <div class="card-header"><h3 class="card-title mb-0">{{ $title }} — Sessions</h3></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th class="text-center">Status</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($record->date)->format('D, d M Y') }}</td>
                                <td>
                                    @if($record->classSession && $record->classSession->starts_at)
                                        {{ \Carbon\Carbon::parse($record->classSession->starts_at)->format('h:i A') }}
                                    @else <span class="text-muted">—</span> @endif
                                </td>
                                <td class="text-center">
                                    <span class="att-chip {{ $record->attendance_status }}">{{ ucfirst($record->attendance_status) }}</span>
                                    @if($record->left_early_at)
                                        <div class="small text-muted mt-1">Left {{ \Carbon\Carbon::parse($record->left_early_at)->format('h:i A') }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($record->feedback && $record->feedback_visible_to_student)
                                        <div class="att-feedback">{{ $record->feedback }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No attendance has been recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
