<?php

namespace Modules\Report\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Report\Entities\StudentRiskScoreTrend;
use Modules\Assignment\Entities\Assignment;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Attendance\Entities\Attendance;
use Modules\Log\Entities\Log as ActivityLog;
use Modules\Report\Entities\StudentRiskScore;
use Modules\Setting\Entities\RiskScoringSetting;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Student\Entities\StudentIntakeSubjectMark;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Student\Entities\StudentIntakeUnitMark;

class StudentRiskAnalyzer
{
    protected float $weightAttendance = 0.30;
    protected float $weightAssignment = 0.25;
    protected float $weightGrade = 0.20;
    protected float $weightFee = 0.15;
    protected float $weightEngagement = 0.10;

    public function __construct()
    {
        $settings = RiskScoringSetting::first();
        if ($settings) {
            $this->weightAttendance = $settings->attendance_weight / 100;
            $this->weightAssignment = $settings->assignment_weight / 100;
            $this->weightGrade = $settings->grade_weight / 100;
            $this->weightFee = $settings->fee_weight / 100;
            $this->weightEngagement = $settings->engagement_weight / 100;
        }
    }

    public function analyzeAll(): int
    {
        $count = 0;

        $studentIntakeCourses = StudentIntakeCourse::where('status', 1)
            ->where('is_enrolled', 1)
            ->with('student')
            ->get();

        foreach ($studentIntakeCourses as $studentIntakeCourse) {
            if (!$studentIntakeCourse->student || !in_array($studentIntakeCourse->student->status, [0, 1])) {
                continue;
            }

            try {
                $this->analyzeStudentCourse($studentIntakeCourse);
                $count++;
            } catch (\Exception $e) {
                Log::error("Risk analysis failed for student_id={$studentIntakeCourse->student_id}, student_intake_course_id={$studentIntakeCourse->id}: " . $e->getMessage());
            }
        }

        return $count;
    }

    public function analyzeStudent(Student $student): Collection
    {
        $scores = collect();

        $intakeCourses = StudentIntakeCourse::where('student_id', $student->id)
            ->where('status', 1)
            ->where('is_enrolled', 1)
            ->get();

        foreach ($intakeCourses as $studentIntakeCourse) {
            $scores->push($this->analyzeStudentCourse($studentIntakeCourse));
        }

        return $scores;
    }

    public function analyzeStudentCourse(StudentIntakeCourse $studentIntakeCourse): StudentRiskScore
    {
        $studentId = $studentIntakeCourse->student_id;
        $intakeCourseId = $studentIntakeCourse->intake_course_id;

        $studentIntakeUnits = StudentIntakeUnit::where('student_intake_course_id', $studentIntakeCourse->id)
            ->where('status', 1)
            ->get();

        $studentIntakeSubjects = StudentIntakeSubject::where('student_intake_course_id', $studentIntakeCourse->id)
            ->where('status', 1)
            ->get();

        $intakeUnitIds = $studentIntakeUnits->pluck('intake_unit_id')->filter()->unique()->values();
        $intakeSubjectIds = $studentIntakeSubjects->pluck('intake_subject_id')->filter()->unique()->values();

        $attendance = $this->attendanceScore($studentId, $intakeUnitIds, $intakeSubjectIds);
        $assignment = $this->assignmentScore($studentId, $intakeUnitIds, $intakeSubjectIds);
        $grade = $this->gradeScore($studentIntakeUnits, $studentIntakeSubjects);
        $fee = $this->feeScore($studentId, $intakeCourseId);
        $engagement = $this->engagementScore($studentId);

        $composite = $this->compositeScore([
            'attendance' => $attendance,
            'assignment' => $assignment,
            'grade' => $grade,
            'fee' => $fee,
            'engagement' => $engagement,
        ]);

        $riskScore = StudentRiskScore::updateOrCreate(
            [
                'student_id' => $studentId,
                'student_intake_course_id' => $studentIntakeCourse->id,
            ],
            [
                'overall_score' => $composite['score'],
                'risk_level' => $composite['level'],
                'attendance_score' => $attendance['score'],
                'assignment_score' => $assignment['score'],
                'grade_score' => $grade['score'],
                'fee_score' => $fee['score'],
                'engagement_score' => $engagement['score'],
                'details' => [
                    'attendance' => $attendance['details'],
                    'assignment' => $assignment['details'],
                    'grade' => $grade['details'],
                    'fee' => $fee['details'],
                    'engagement' => $engagement['details'],
                ],
                'analyzed_at' => Carbon::now(),
            ]
        );

        try {
            StudentRiskScoreTrend::updateOrCreate(
                [
                    'student_id'               => $studentId,
                    'student_intake_course_id' => $studentIntakeCourse->id,
                    'analyzed_at'              => Carbon::today(),
                ],
                [
                    'overall_score'    => $composite['score'],
                    'risk_level'       => $composite['level'],
                    'attendance_score' => $attendance['score'],
                    'assignment_score' => $assignment['score'],
                    'grade_score'      => $grade['score'],
                    'fee_score'        => $fee['score'],
                    'engagement_score' => $engagement['score'],
                ]
            );
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Trend snapshot failed for student_id={$studentId}: " . $e->getMessage());
        }

        return $riskScore;
    }

    private function attendanceScore(int $studentId, Collection $intakeUnitIds, Collection $intakeSubjectIds): array
    {
        $since = Carbon::now()->subDays(30);

        // Count both unit-level and subject-level rows for this student's active units/subjects.
        $query = Attendance::where('student_id', $studentId)
            ->where('date', '>=', $since)
            ->where(function ($q) use ($intakeUnitIds, $intakeSubjectIds) {
                if ($intakeUnitIds->isNotEmpty()) {
                    $q->whereIn('intake_unit_id', $intakeUnitIds);
                }
                if ($intakeSubjectIds->isNotEmpty()) {
                    $q->orWhereIn('intake_subject_id', $intakeSubjectIds);
                }
            });

        // Excused rows are excluded from BOTH numerator and denominator: the percentage
        // reads "of sessions you were required to attend, how many did you attend".
        $required = (clone $query)->where('attendance_status', '!=', Attendance::EXCUSED)->count();
        $attended = (clone $query)->whereIn('attendance_status', [Attendance::PRESENT, Attendance::LATE])->count();
        $late = (clone $query)->where('attendance_status', Attendance::LATE)->count();
        $excused = (clone $query)->where('attendance_status', Attendance::EXCUSED)->count();

        if ($required === 0) {
            return [
                'score' => 0,
                'details' => ['required' => 0, 'attended' => 0, 'percentage' => null, 'note' => 'No attendance records found'],
            ];
        }

        $percentage = round(($attended / $required) * 100, 1);

        if ($percentage >= 90) {
            $score = 0;
        } elseif ($percentage >= 80) {
            $score = 20;
        } elseif ($percentage >= 70) {
            $score = 50;
        } elseif ($percentage >= 60) {
            $score = 75;
        } else {
            $score = 100;
        }

        return [
            'score' => $score,
            'details' => [
                'required' => $required,
                'attended' => $attended,
                'absent' => $required - $attended,
                'late' => $late,
                'excused' => $excused,
                'percentage' => $percentage,
                'period' => '30 days',
            ],
        ];
    }

    private function assignmentScore(int $studentId, Collection $intakeUnitIds, Collection $intakeSubjectIds): array
    {
        if ($intakeUnitIds->isEmpty() && $intakeSubjectIds->isEmpty()) {
            return [
                'score' => 0,
                'details' => ['total' => 0, 'note' => 'No active units or subjects'],
            ];
        }

        $assignments = Assignment::where('status', 1)
            ->where(function ($query) use ($intakeUnitIds, $intakeSubjectIds) {
                if ($intakeUnitIds->isNotEmpty()) {
                    $query->whereIn('intake_unit_id', $intakeUnitIds);
                }
                if ($intakeSubjectIds->isNotEmpty()) {
                    $method = $intakeUnitIds->isNotEmpty() ? 'orWhereIn' : 'whereIn';
                    $query->{$method}('intake_subject_id', $intakeSubjectIds);
                }
            })
            ->get();

        $totalAssignments = $assignments->count();

        if ($totalAssignments === 0) {
            return [
                'score' => 0,
                'details' => ['total' => 0, 'note' => 'No assignments found'],
            ];
        }

        $lateCount = 0;
        $missedCount = 0;
        $submittedCount = 0;

        foreach ($assignments as $assignment) {
            $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
                ->where('student_id', $studentId)
                ->first();

            if (!$submission) {
                if ($assignment->due_date && Carbon::parse($assignment->due_date)->isPast()) {
                    $missedCount++;
                }
            } else {
                $submittedCount++;
                if ($assignment->due_date && $submission->created_at->gt(Carbon::parse($assignment->due_date))) {
                    $lateCount++;
                }
            }
        }

        $rawScore = ($missedCount * 30) + ($lateCount * 15);
        $score = min(100, (int) round($rawScore / $totalAssignments));

        return [
            'score' => $score,
            'details' => [
                'total' => $totalAssignments,
                'submitted' => $submittedCount,
                'late' => $lateCount,
                'missed' => $missedCount,
            ],
        ];
    }

    private function gradeScore(Collection $studentIntakeUnits, Collection $studentIntakeSubjects): array
    {
        $totalUnits = $studentIntakeUnits->count();
        $passed = 0;
        $failed = 0;
        $withdrawn = 0;
        $inProgress = 0;
        $inProgressUnitIds = [];

        foreach ($studentIntakeUnits as $unit) {
            $outcome = (string) $unit->outcome;
            if ($outcome === '3') {
                $passed++;
            } elseif ($outcome === '2') {
                $failed++;
            } elseif ($outcome === '1' || $outcome === '6') {
                $withdrawn++;
            } else {
                $inProgress++;
                $inProgressUnitIds[] = $unit->id;
            }
        }

        $completedUnits = $passed + $failed;
        $score = $completedUnits > 0 ? (int) round(($failed / $completedUnits) * 100) : 0;
        $failingMarksPenalty = 0;

        if (!empty($inProgressUnitIds)) {
            $marks = StudentIntakeUnitMark::whereIn('student_intake_unit_id', $inProgressUnitIds)
                ->where('status', 1)
                ->get();

            foreach ($marks as $mark) {
                if ((float) $mark->pass_marks > 0 && (float) $mark->obtain_marks < (float) $mark->pass_marks) {
                    $failingMarksPenalty += 20;
                }
            }
        }

        $subjectIds = $studentIntakeSubjects->pluck('id')->filter()->values();
        $subjectMarkCount = 0;
        if ($subjectIds->isNotEmpty()) {
            $subjectMarks = StudentIntakeSubjectMark::whereIn('student_intake_subject_id', $subjectIds)
                ->where('status', 1)
                ->get();

            $subjectMarkCount = $subjectMarks->count();
            foreach ($subjectMarks as $mark) {
                if ((float) $mark->pass_marks > 0 && (float) $mark->obtain_marks < (float) $mark->pass_marks) {
                    $failingMarksPenalty += 20;
                }
            }
        }

        if ($totalUnits === 0 && $studentIntakeSubjects->isEmpty()) {
            return [
                'score' => 0,
                'details' => ['total_units' => 0, 'total_subjects' => 0, 'note' => 'No active units or subjects'],
            ];
        }

        return [
            'score' => min(100, $score + $failingMarksPenalty),
            'details' => [
                'total_units' => $totalUnits,
                'total_subjects' => $studentIntakeSubjects->count(),
                'subject_marks' => $subjectMarkCount,
                'passed' => $passed,
                'failed' => $failed,
                'withdrawn' => $withdrawn,
                'in_progress' => $inProgress,
                'note' => $failingMarksPenalty > 0 ? 'Includes penalty for failing marks in active units/subjects.' : null,
            ],
        ];
    }

    private function feeScore(int $studentId, int $intakeCourseId): array
    {
        $fee = StudentIntakeCourseFee::where('student_id', $studentId)
            ->where('intake_course_id', $intakeCourseId)
            ->where('status', 1)
            ->first();

        if (!$fee) {
            return [
                'score' => 0,
                'details' => ['note' => 'No fee record found'],
            ];
        }

        $installments = StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $fee->id)
            ->where('parent_id', 0)
            ->get();

        $totalInstallments = $installments->count();

        if ($totalInstallments === 0) {
            return [
                'score' => 0,
                'details' => ['total' => 0, 'note' => 'No installments'],
            ];
        }

        $overdueCount = 0;
        $today = Carbon::today();

        foreach ($installments as $installment) {
            if ($installment->status == 1 && $installment->due_date && Carbon::parse($installment->due_date)->lt($today)) {
                $overdueCount++;
            }
        }

        return [
            'score' => (int) round(($overdueCount / $totalInstallments) * 100),
            'details' => [
                'total' => $totalInstallments,
                'overdue' => $overdueCount,
                'paid' => $installments->where('status', 2)->count(),
            ],
        ];
    }

    private function engagementScore(int $studentId): array
    {
        $since = Carbon::now()->subDays(14);

        $loginCount = ActivityLog::where('user_type', 'Student')
            ->where('user_id', $studentId)
            ->where('action', 'like', '%Logged In%')
            ->where('created_at', '>=', $since)
            ->count();

        if ($loginCount >= 6) {
            $score = 0;
        } elseif ($loginCount >= 3) {
            $score = 30;
        } elseif ($loginCount >= 1) {
            $score = 60;
        } else {
            $score = 100;
        }

        return [
            'score' => $score,
            'details' => [
                'login_count' => $loginCount,
                'period' => '14 days',
            ],
        ];
    }

    private function compositeScore(array $signals): array
    {
        $score = (int) round(
            ($signals['attendance']['score'] * $this->weightAttendance) +
            ($signals['assignment']['score'] * $this->weightAssignment) +
            ($signals['grade']['score'] * $this->weightGrade) +
            ($signals['fee']['score'] * $this->weightFee) +
            ($signals['engagement']['score'] * $this->weightEngagement)
        );

        $score = max(0, min(100, $score));

        if ($score >= 81) {
            $level = 'critical';
        } elseif ($score >= 61) {
            $level = 'high';
        } elseif ($score >= 31) {
            $level = 'medium';
        } else {
            $level = 'low';
        }

        return ['score' => $score, 'level' => $level];
    }
}
