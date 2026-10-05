<?php

namespace Modules\Gradebook\Services;

use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentIntakeSubjectMark;
use Modules\Student\Entities\StudentIntakeUnitMark;
use Modules\Attendance\Entities\Attendance;

class GradebookService
{
    /**
     * Build the full gradebook data structure for a student in a specific intake course.
     *
     * Returns an array with subjects, units, attendance rate, and academic standing.
     */
    public function build(int $studentId, int $studentIntakeCourseId): array
    {
        $intakeCourse = StudentIntakeCourse::with([
            'intakeCourse.course',
            'intakeCourse.intake',
        ])->findOrFail($studentIntakeCourseId);

        $subjects = $this->buildSubjectRows($studentId, $studentIntakeCourseId);
        $units    = $this->buildUnitRows($studentId, $studentIntakeCourseId);

        $overallPct    = $this->calculateOverall($subjects, $units);
        $attendanceRate = $this->attendanceRate($studentId, $studentIntakeCourseId);
        $standing       = $this->deriveStanding($overallPct, $attendanceRate);

        return [
            'intake_course'   => $intakeCourse,
            'subjects'        => $subjects,
            'units'           => $units,
            'overall_pct'     => $overallPct,
            'attendance_rate' => $attendanceRate,
            'standing'        => $standing,
        ];
    }

    private function buildSubjectRows(int $studentId, int $studentIntakeCourseId): array
    {
        $studentSubjects = StudentIntakeSubject::with([
            'intakeSubject.subject',
            'studentIntakeSemester',
        ])
        ->where('student_intake_course_id', $studentIntakeCourseId)
        ->where('status', 1)
        ->get();

        return $studentSubjects->map(function ($ss) {
            $marks = StudentIntakeSubjectMark::where('student_intake_subject_id', $ss->id)
                ->where('status', 1)
                ->get();

            $fullMarks   = $marks->sum('full_marks');
            $obtainMarks = $marks->sum('obtain_marks');
            $passMarks   = $marks->sum('pass_marks');
            $pct = $fullMarks > 0 ? round(($obtainMarks / $fullMarks) * 100, 1) : null;

            return [
                'id'           => $ss->id,
                'name'         => optional(optional($ss->intakeSubject)->subject)->name ?? 'Unknown Subject',
                'marks'        => $marks,
                'full_marks'   => $fullMarks,
                'pass_marks'   => $passMarks,
                'obtain_marks' => $obtainMarks,
                'percentage'   => $pct,
                'result'       => $this->markResult($obtainMarks, $passMarks, $fullMarks),
                'is_complete'  => $ss->is_complete,
            ];
        })->toArray();
    }

    private function buildUnitRows(int $studentId, int $studentIntakeCourseId): array
    {
        $studentUnits = StudentIntakeUnit::with([
            'intakeUnit.unit',
            'studentIntakeSemester',
        ])
        ->where('student_intake_course_id', $studentIntakeCourseId)
        ->where('status', 1)
        ->get();

        return $studentUnits->map(function ($su) {
            $marks = StudentIntakeUnitMark::where('student_intake_unit_id', $su->id)
                ->where('status', 1)
                ->get();

            $fullMarks   = $marks->sum('full_marks');
            $obtainMarks = $marks->sum('obtain_marks');
            $passMarks   = $marks->sum('pass_marks');
            $pct = $fullMarks > 0 ? round(($obtainMarks / $fullMarks) * 100, 1) : null;

            return [
                'id'           => $su->id,
                'name'         => optional(optional($su->intakeUnit)->unit)->name ?? 'Unknown Unit',
                'marks'        => $marks,
                'full_marks'   => $fullMarks,
                'pass_marks'   => $passMarks,
                'obtain_marks' => $obtainMarks,
                'percentage'   => $pct,
                'result'       => $this->markResult($obtainMarks, $passMarks, $fullMarks),
                'is_complete'  => $su->is_complete,
                'outcome'      => $su->outcome,
            ];
        })->toArray();
    }

    private function calculateOverall(array $subjects, array $units): ?float
    {
        $allRows = array_merge($subjects, $units);
        $totalFull   = array_sum(array_column($allRows, 'full_marks'));
        $totalObtain = array_sum(array_column($allRows, 'obtain_marks'));

        return $totalFull > 0 ? round(($totalObtain / $totalFull) * 100, 1) : null;
    }

    private function attendanceRate(int $studentId, int $studentIntakeCourseId): ?float
    {
        $unitIds = StudentIntakeUnit::where('student_intake_course_id', $studentIntakeCourseId)
            ->where('status', 1)->pluck('intake_unit_id')->filter();
        $subjectIds = StudentIntakeSubject::where('student_intake_course_id', $studentIntakeCourseId)
            ->where('status', 1)->pluck('intake_subject_id')->filter();

        if ($unitIds->isEmpty() && $subjectIds->isEmpty()) {
            return null;
        }

        $base = Attendance::where('student_id', $studentId)
            ->where(function ($q) use ($unitIds, $subjectIds) {
                if ($unitIds->isNotEmpty()) {
                    $q->whereIn('intake_unit_id', $unitIds);
                }
                if ($subjectIds->isNotEmpty()) {
                    $q->orWhereIn('intake_subject_id', $subjectIds);
                }
            });

        // Excused excluded from both; late counts as attended (mirrors risk scoring).
        $required = (clone $base)->where('attendance_status', '!=', Attendance::EXCUSED)->count();
        $attended = (clone $base)->whereIn('attendance_status', [Attendance::PRESENT, Attendance::LATE])->count();

        return $required > 0 ? round(($attended / $required) * 100, 1) : null;
    }

    /**
     * Overall percentage across a single semester's subjects.
     * Used by Feature 2's per-semester scholarship maintenance evaluation.
     */
    public function calculateForSemester(int $studentId, int $studentIntakeCourseId, int $intakeSemesterId): ?float
    {
        $studentSubjects = StudentIntakeSubject::with('intakeSubject')
            ->where('student_intake_course_id', $studentIntakeCourseId)
            ->where('status', 1)
            ->get()
            ->filter(function ($ss) use ($intakeSemesterId) {
                return optional($ss->intakeSubject)->intake_semester_id == $intakeSemesterId;
            });

        $totalFull = 0;
        $totalObtain = 0;
        foreach ($studentSubjects as $ss) {
            $marks = StudentIntakeSubjectMark::where('student_intake_subject_id', $ss->id)
                ->where('status', 1)->get();
            $totalFull += $marks->sum('full_marks');
            $totalObtain += $marks->sum('obtain_marks');
        }

        return $totalFull > 0 ? round(($totalObtain / $totalFull) * 100, 1) : null;
    }

    private function deriveStanding(?float $overallPct, ?float $attendanceRate): string
    {
        if ($overallPct === null) {
            return 'No Data';
        }
        if ($overallPct < 50 || ($attendanceRate !== null && $attendanceRate < 70)) {
            return 'Failing';
        }
        if ($overallPct < 65 || ($attendanceRate !== null && $attendanceRate < 80)) {
            return 'At Risk';
        }
        return 'Good Standing';
    }

    private function markResult(float $obtain, float $pass, float $full): string
    {
        if ($full === 0.0) {
            return 'Pending';
        }
        return $obtain >= $pass ? 'Pass' : 'Fail';
    }
}
