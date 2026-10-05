<?php

namespace Modules\Scholarship\Services;

use Modules\Intake\Entities\IntakeSemester;
use Modules\Scholarship\Entities\FeeDiscount;
use Modules\Scholarship\Entities\Scholarship;
use Modules\Scholarship\Entities\ScholarshipApplication;
use Modules\Scholarship\Entities\ScholarshipDisbursement;
use Modules\Student\Entities\StudentIntakeCourseFee;

class DisbursementService
{
    /**
     * Fee balance still available for discounting — the gross fee minus discounts already
     * released (active). This is what makes stacking compound against the remaining balance.
     */
    public function remainingBalance(StudentIntakeCourseFee $fee): float
    {
        $existing = (float) $fee->feeDiscounts()->sum('discount_amount');
        return round(max(0, $fee->fee - $existing), 2);
    }

    /**
     * Total award a scholarship yields against a fee, honouring stacking (percentage applies
     * to the remaining balance), the percentage cap, and the hard floor (never exceed the fee).
     */
    public function totalAward(Scholarship $scholarship, StudentIntakeCourseFee $fee): float
    {
        $remaining = $this->remainingBalance($fee);

        if ($scholarship->award_scope === 'full') {
            return $remaining;
        }

        if ($scholarship->value_type === 'fixed') {
            $amount = (float) $scholarship->value;
        } else {
            $amount = $remaining * ($scholarship->value / 100);
            if ($scholarship->max_value) {
                $amount = min($amount, (float) $scholarship->max_value);
            }
        }

        return round(min($amount, $remaining), 2);
    }

    /**
     * Generate disbursements for a freshly-approved application and release the first one
     * (one-off, or the first semester of a per-semester award).
     */
    public function generate(ScholarshipApplication $application): void
    {
        $fee = $application->studentIntakeCourseFee;
        $scholarship = $application->scholarship;
        $total = $this->totalAward($scholarship, $fee);

        $semesters = collect();
        if ($scholarship->disbursement === 'per_semester') {
            $semesters = IntakeSemester::where('intake_course_id', $fee->intake_course_id)
                ->where('status', 1)->orderBy('sequence')->get();
            if ($scholarship->max_semesters) {
                $semesters = $semesters->take($scholarship->max_semesters)->values();
            }
        }

        // One-off, or per-semester with no semesters defined → a single released disbursement.
        if ($semesters->isEmpty()) {
            $disbursement = ScholarshipDisbursement::create([
                'scholarship_application_id' => $application->id,
                'intake_semester_id' => null,
                'sequence' => null,
                'planned_amount' => $total,
                'state' => ScholarshipDisbursement::SCHEDULED,
            ]);
            $this->release($disbursement);
            return;
        }

        $count = $semesters->count();
        $perSemester = round($total / $count, 2);

        foreach ($semesters->values() as $index => $semester) {
            // Last semester absorbs any rounding remainder.
            $planned = $index === $count - 1
                ? round($total - $perSemester * ($count - 1), 2)
                : $perSemester;

            $disbursement = ScholarshipDisbursement::create([
                'scholarship_application_id' => $application->id,
                'intake_semester_id' => $semester->id,
                'sequence' => $index + 1,
                'planned_amount' => $planned,
                'state' => ScholarshipDisbursement::SCHEDULED,
            ]);

            // The first semester has no prior semester to gate on — release immediately.
            if ($index === 0) {
                $this->release($disbursement);
            }
        }
    }

    /**
     * Release a scheduled disbursement: materialise an active FeeDiscount (capped by the
     * current remaining balance) and mark the disbursement released.
     */
    public function release(ScholarshipDisbursement $disbursement, ?float $achievedPercentage = null): ?FeeDiscount
    {
        $application = $disbursement->scholarshipApplication;
        $fee = $application->studentIntakeCourseFee;
        $scholarship = $application->scholarship;

        $amount = round(max(0, min($disbursement->planned_amount, $this->remainingBalance($fee))), 2);

        $discount = FeeDiscount::create([
            'scholarship_application_id' => $application->id,
            'student_id' => $application->student_id,
            'student_intake_course_fee_id' => $fee->id,
            'type' => $scholarship->type,
            'value_type' => $scholarship->value_type,
            'value' => $scholarship->value,
            'discount_amount' => $amount,
            'justification' => $application->justification ?: $scholarship->name,
            'agent_id' => null,
            'approved_by' => $application->reviewed_by ?: $application->applied_by,
            'approved_at' => now(),
            'status' => 1,
        ]);

        $disbursement->update([
            'state' => ScholarshipDisbursement::RELEASED,
            'actual_amount' => $amount,
            'fee_discount_id' => $discount->id,
            'maintenance_percentage_achieved' => $achievedPercentage,
            'evaluated_at' => now(),
        ]);

        return $discount;
    }

    /**
     * Mark a disbursement withheld (failed maintenance).
     */
    public function withhold(ScholarshipDisbursement $disbursement, ?float $achievedPercentage, string $reason): void
    {
        $disbursement->update([
            'state' => ScholarshipDisbursement::WITHHELD,
            'maintenance_percentage_achieved' => $achievedPercentage,
            'state_reason' => $reason,
            'evaluated_at' => now(),
        ]);
    }
}
