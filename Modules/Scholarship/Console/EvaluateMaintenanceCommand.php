<?php

namespace Modules\Scholarship\Console;

use Illuminate\Console\Command;
use Modules\Gradebook\Services\GradebookService;
use Modules\Intake\Entities\IntakeSemester;
use Modules\Scholarship\Entities\ScholarshipDisbursement;
use Modules\Scholarship\Services\DisbursementService;
use Modules\Student\Entities\StudentIntakeCourse;

class EvaluateMaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarships:evaluate-maintenance
                            {--dry-run : Report actions without releasing or withholding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release or withhold scheduled per-semester scholarship disbursements based on prior-semester performance';

    public function handle(): int
    {
        $today = now()->toDateString();
        $dryRun = (bool) $this->option('dry-run');
        $service = new DisbursementService();
        $gradebook = new GradebookService();

        $due = ScholarshipDisbursement::with(['scholarshipApplication.scholarship', 'intakeSemester'])
            ->where('state', ScholarshipDisbursement::SCHEDULED)
            ->whereNotNull('intake_semester_id')
            ->get();

        $released = 0;
        $withheld = 0;
        $skipped = 0;

        foreach ($due as $disbursement) {
            $application = $disbursement->scholarshipApplication;
            $semester = $disbursement->intakeSemester;

            // Skip if the award is no longer approved, or the semester hasn't started yet.
            if (!$application || $application->status !== 1 || !$semester) {
                $skipped++;
                continue;
            }
            if ($semester->starting_date && $semester->starting_date > $today) {
                $skipped++;
                continue;
            }

            $scholarship = $application->scholarship;

            // No maintenance requirement → release once the semester has started.
            if (!$scholarship->requires_maintenance) {
                $released++;
                if (!$dryRun) {
                    $service->release($disbursement);
                }
                continue;
            }

            // Evaluate the immediately-prior semester's overall percentage.
            $prior = IntakeSemester::where('intake_course_id', $semester->intake_course_id)
                ->where('sequence', $semester->sequence - 1)
                ->where('status', 1)->first();

            if (!$prior) {
                // No prior semester to gate on — release.
                $released++;
                if (!$dryRun) {
                    $service->release($disbursement);
                }
                continue;
            }

            $studentIntakeCourse = StudentIntakeCourse::where('student_id', $application->student_id)
                ->where('intake_course_id', $semester->intake_course_id)->first();
            if (!$studentIntakeCourse) {
                $skipped++;
                continue;
            }

            $achieved = $gradebook->calculateForSemester($application->student_id, $studentIntakeCourse->id, $prior->id);
            $threshold = (float) $scholarship->maintenance_min_percentage;

            if ($achieved !== null && $achieved >= $threshold) {
                $released++;
                if (!$dryRun) {
                    $service->release($disbursement, $achieved);
                }
            } else {
                $withheld++;
                $reason = $achieved === null
                    ? 'No grades recorded for the prior semester'
                    : 'Achieved ' . $achieved . '% (below required ' . $threshold . '%)';
                if (!$dryRun) {
                    $service->withhold($disbursement, $achieved, $reason);
                }
            }
        }

        $prefix = $dryRun ? '[dry-run] ' : '';
        $this->info("{$prefix}Evaluated {$due->count()} scheduled disbursement(s): released {$released}, withheld {$withheld}, skipped {$skipped}.");
        return 0;
    }
}
