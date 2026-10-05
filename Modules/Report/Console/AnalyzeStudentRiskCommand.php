<?php

namespace Modules\Report\Console;

use Illuminate\Console\Command;
use Modules\Notification\Entities\Notification;
use Modules\Report\Entities\StudentRiskScore;
use Modules\Report\Services\StudentRiskAnalyzer;
use Modules\Student\Entities\Student;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;

class AnalyzeStudentRiskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:analyze-risk
                            {--student= : Analyze a specific student by ID}
                            {--notify : Send notifications for newly critical/high risk students}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze all active students and compute risk scores';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $analyzer = new StudentRiskAnalyzer();
        $studentId = $this->option('student');
        $shouldNotify = $this->option('notify');

        $previousLevels = [];
        if ($shouldNotify) {
            $previousLevels = StudentRiskScore::pluck('risk_level', 'id')->toArray();
        }

        if ($studentId) {
            $student = Student::find($studentId);
            if (!$student) {
                $this->error("Student with ID {$studentId} not found.");
                return 1;
            }

            $scores = $analyzer->analyzeStudent($student);
            $this->info("Analyzed student {$studentId}: " . $scores->count() . " intake course(s).");
            foreach ($scores as $score) {
                $this->line("  Course #{$score->student_intake_course_id}: Score={$score->overall_score}, Level={$score->risk_level}");
            }
        } else {
            $this->info('Starting risk analysis for all active students...');
            $count = $analyzer->analyzeAll();
            $this->info("Analyzed {$count} student-course records.");
        }

        $summary = StudentRiskScore::selectRaw('risk_level, COUNT(*) as count')
            ->groupBy('risk_level')
            ->pluck('count', 'risk_level')
            ->toArray();

        $this->table(
            ['Risk Level', 'Count'],
            collect(['critical', 'high', 'medium', 'low'])->map(function ($level) use ($summary) {
                return [ucfirst($level), $summary[$level] ?? 0];
            })
        );

        if ($shouldNotify) {
            $this->sendRiskNotifications($previousLevels);
        }

        return 0;
    }

    private function sendRiskNotifications(array $previousLevels): void
    {
        $escalatedScores = StudentRiskScore::whereIn('risk_level', ['critical', 'high'])
            ->with(['student', 'studentIntakeCourse.intakeCourse.course'])
            ->get();

        $allowedUserTypes = UserRole::where(function ($query) {
            $query->where('key', 'student_risk_report')
                ->orWhere('key', 'student_report');
        })
            ->where('value', 'view')
            ->where('status', 1)
            ->pluck('user_type')
            ->unique();

        if ($allowedUserTypes->isEmpty()) {
            $allowedUserTypes = collect(['Super Admin']);
        }

        $admins = User::whereIn('user_type', $allowedUserTypes)
            ->where('status', 1)
            ->get();

        $notified = 0;
        foreach ($escalatedScores as $score) {
            $previousLevel = $previousLevels[$score->id] ?? null;

            if ($previousLevel === $score->risk_level) {
                continue;
            }
            if ($previousLevel === 'critical' && $score->risk_level === 'high') {
                continue;
            }

            $studentName = userName('Student', $score->student_id);
            $courseName = optional(optional(optional($score->studentIntakeCourse)->intakeCourse)->course)->course_name ?? 'N/A';

            if (!$admins->isEmpty()) {
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_type' => 'Admin',
                        'user_id' => $admin->id,
                        'sender_type' => 'System',
                        'sender_id' => 0,
                        'title' => 'Student At-Risk Alert',
                        'body' => "{$studentName} has been flagged as " . strtoupper($score->risk_level) . " risk in {$courseName} (Score: {$score->overall_score}/100)",
                        'type' => 'risk_alert',
                        'link' => '/admin/report/risk',
                        'status' => 1,
                    ]);
                }
            }

            if ($score->student) {
                Notification::create([
                    'user_type'   => 'Student',
                    'user_id'     => $score->student_id,
                    'sender_type' => 'System',
                    'sender_id'   => 0,
                    'title'       => 'Your Learning Progress Alert',
                    'body'        => 'Your academic risk score has been updated to ' . strtoupper($score->risk_level) . ' in ' . $courseName . '. Please speak with your trainer or student support.',
                    'type'        => 'risk_alert',
                    'link'        => '/student/notification',
                    'status'      => 1,
                ]);
            }

            $notified++;
        }

        if ($notified > 0) {
            $this->info("Sent risk notifications for {$notified} student(s).");
        }
    }
}
