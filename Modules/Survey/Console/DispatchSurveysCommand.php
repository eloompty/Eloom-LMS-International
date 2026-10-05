<?php

namespace Modules\Survey\Console;

use Illuminate\Console\Command;
use Modules\Notification\Entities\Notification;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Survey\Entities\SurveyInstance;

class DispatchSurveysCommand extends Command
{
    protected $signature = 'survey:dispatch';
    protected $description = 'Send in-app notifications for survey instances whose dispatch_at time has passed';

    public function handle(): int
    {
        $pending = SurveyInstance::where('status', 1)
            ->whereNull('notified_at')
            ->where(fn ($q) => $q->whereNull('dispatch_at')->orWhere('dispatch_at', '<=', now()))
            ->with('template')
            ->get();

        if ($pending->isEmpty()) {
            $this->info('No surveys pending dispatch.');
            return 0;
        }

        foreach ($pending as $instance) {
            $studentIds = $this->resolveStudentIds($instance);

            foreach ($studentIds as $studentId) {
                Notification::create([
                    'user_type'   => 'Student',
                    'user_id'     => $studentId,
                    'sender_type' => 'System',
                    'sender_id'   => 0,
                    'title'       => 'New Survey Available',
                    'body'        => 'You have a new survey to complete: ' . ($instance->template->name ?? 'Survey'),
                    'type'        => 'Survey',
                    'link'        => (string) $instance->id,
                    'status'      => 1,
                ]);
            }

            $instance->update(['notified_at' => now()]);
            $this->line("Dispatched survey instance #{$instance->id} to " . count($studentIds) . " student(s).");
        }

        $this->info("Done. Dispatched {$pending->count()} survey instance(s).");
        return 0;
    }

    private function resolveStudentIds(SurveyInstance $instance): array
    {
        return match ($instance->target_type) {
            'intake' => StudentIntakeCourse::whereHas(
                'intakeCourse', fn ($q) => $q->where('intake_id', $instance->target_id)
            )->where('is_enrolled', 1)->where('status', 1)->pluck('student_id')->unique()->toArray(),

            'course' => StudentIntakeCourse::whereHas(
                'intakeCourse', fn ($q) => $q->where('course_id', $instance->target_id)
            )->where('is_enrolled', 1)->where('status', 1)->pluck('student_id')->unique()->toArray(),

            'event' => StudentIntakeCourse::where('is_enrolled', 1)->where('status', 1)
                ->pluck('student_id')->unique()->toArray(),

            default => Student::where('status', 1)->where('is_enrolled', 1)->pluck('id')->toArray(),
        };
    }
}
