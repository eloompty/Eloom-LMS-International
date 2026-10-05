<?php

namespace Modules\Attendance\Services;

use Modules\Attendance\Entities\Attendance;
use Modules\Attendance\Entities\ClassSession;
use Modules\Intake\Entities\IntakeTime;

class AttendanceMarker
{
    /**
     * Timetable slots (intake_times rows) for a subject or unit in a given month, deduped
     * by date+time. Each slot is decorated with a representative time_id, the materialised
     * session id (if attendance has been marked), and the marked count.
     *
     * @param string $scope 'subject' | 'unit'
     */
    public function timetableSlots(string $scope, int $scopeId, $year, $month)
    {
        $query = IntakeTime::where('status', '!=', 2)
            ->whereNotNull('date')
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($scope === 'unit') {
            $query->where('intake_unit_id', $scopeId);
        } else {
            $query->where('intake_subject_id', $scopeId);
        }

        $rows = $query->orderBy('date')->orderBy('from')->get();

        // A subject timetable stores one row per unit per date — dedupe to one slot per date+time.
        return $rows->groupBy(fn ($r) => $r->date . '|' . $r->from . '|' . $r->to)
            ->map(function ($group) use ($scope, $scopeId) {
                $rep = $group->sortBy('id')->first();

                $session = ClassSession::where('date', $rep->date)
                    ->where('starts_at', $rep->from)
                    ->where('ends_at', $rep->to)
                    ->when($scope === 'unit', fn ($q) => $q->where('intake_unit_id', $scopeId))
                    ->when($scope === 'subject', fn ($q) => $q->where('intake_subject_id', $scopeId))
                    ->first();

                $rep->time_id = $rep->id;
                $rep->session_id = $session?->id;
                $rep->marked_count = $session ? Attendance::where('class_session_id', $session->id)->count() : 0;
                return $rep;
            })->values();
    }

    /**
     * Materialise (or fetch) the class session for a timetable slot, scoped to a subject or unit.
     */
    public function sessionForSlot(string $scope, int $scopeId, int $timeId): ClassSession
    {
        $slot = IntakeTime::findOrFail($timeId);

        $key = $scope === 'unit'
            ? ['intake_unit_id' => $scopeId, 'intake_subject_id' => null]
            : ['intake_subject_id' => $scopeId, 'intake_unit_id' => null];

        $key['date'] = $slot->date;
        $key['starts_at'] = $slot->from;
        $key['ends_at'] = $slot->to;

        return ClassSession::firstOrCreate($key, [
            'intake_time_id' => $slot->id,
            'title' => $slot->classroom ? ('Room ' . $slot->classroom) : null,
            'status' => 1,
        ]);
    }

    /**
     * Create or update a class session for an intake unit or subject.
     */
    public function upsertSession(array $data, ?int $sessionId = null): ClassSession
    {
        if ($sessionId) {
            $session = ClassSession::findOrFail($sessionId);
            $session->fill($data)->save();
            return $session;
        }

        return ClassSession::create($data);
    }

    /**
     * Mark attendance for every student against a session.
     *
     * $marks is keyed by student_id, each value an array that may contain:
     *   attendance_status, remarks, feedback, feedback_visible_to_student, left_early_at
     *
     * Rows are keyed by (student_id, class_session_id) so re-marking updates in place
     * rather than deleting/duplicating. Unit/subject/date are denormalised from the
     * session so existing risk/gradebook queries (by unit, subject, date) still work.
     */
    public function markSession(ClassSession $session, array $marks, int $userId, string $userType): void
    {
        $markedVia = in_array(strtolower($userType), ['admin', 'trainer'], true)
            ? strtolower($userType)
            : 'trainer';

        foreach ($marks as $studentId => $mark) {
            $status = $mark['attendance_status'] ?? Attendance::PRESENT;
            if (!in_array($status, [Attendance::PRESENT, Attendance::ABSENT, Attendance::LATE, Attendance::EXCUSED], true)) {
                $status = Attendance::PRESENT;
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_session_id' => $session->id,
                ],
                [
                    'date' => $session->date,
                    'intake_unit_id' => $session->intake_unit_id,
                    'intake_subject_id' => $session->intake_subject_id,
                    'user_id' => $userId,
                    'user_type' => ucfirst($markedVia),
                    'status' => 1,
                    'attendance_status' => $status,
                    'left_early_at' => $mark['left_early_at'] ?? null,
                    'remarks' => $mark['remarks'] ?? null,
                    'feedback' => $mark['feedback'] ?? null,
                    'feedback_visible_to_student' => !empty($mark['feedback_visible_to_student']),
                    'marked_via' => $markedVia,
                ]
            );
        }
    }
}
