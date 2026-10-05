<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Intake\Entities\IntakeSemester;
use Modules\Scholarship\Entities\FeeDiscount;
use Modules\Scholarship\Entities\Scholarship;
use Modules\Scholarship\Entities\ScholarshipApplication;
use Modules\Scholarship\Entities\ScholarshipDisbursement;
use Modules\Scholarship\Services\DisbursementService;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Tests\TestCase;

/**
 * Covers DisbursementService — the scholarship stacking + per-semester split the spec
 * flagged as needing tests "before they touch production data".
 */
class DisbursementServiceTest extends TestCase
{
    use RefreshDatabase;

    private DisbursementService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DisbursementService();
    }

    private function fee(float $amount = 10000): StudentIntakeCourseFee
    {
        return StudentIntakeCourseFee::create([
            'student_id' => 1,
            'intake_course_id' => 1,
            'name' => 'Tuition',
            'fee' => $amount,
            'installments' => 1,
            'due_date' => now()->toDateString(),
            'status' => 1,
        ]);
    }

    private function scholarship(array $attrs = []): Scholarship
    {
        return Scholarship::create(array_merge([
            'name' => 'Test',
            'type' => 'merit',
            'award_scope' => 'partial',
            'disbursement' => 'one_off',
            'value_type' => 'percentage',
            'value' => 50,
            'status' => 1,
        ], $attrs));
    }

    private function activeDiscount(StudentIntakeCourseFee $fee, float $amount): void
    {
        FeeDiscount::create([
            'student_id' => 1,
            'student_intake_course_fee_id' => $fee->id,
            'type' => 'merit',
            'value_type' => 'percentage',
            'value' => 0,
            'discount_amount' => $amount,
            'justification' => 'seed',
            'approved_by' => 1,
            'approved_at' => now(),
            'status' => 1,
        ]);
    }

    public function test_remaining_balance_subtracts_active_discounts_only()
    {
        $fee = $this->fee(10000);
        $this->activeDiscount($fee, 2000);
        // An inactive discount must be ignored.
        FeeDiscount::create([
            'student_id' => 1, 'student_intake_course_fee_id' => $fee->id, 'type' => 'merit',
            'value_type' => 'percentage', 'value' => 0, 'discount_amount' => 5000, 'justification' => 'x',
            'approved_by' => 1, 'approved_at' => now(), 'status' => 0,
        ]);

        $this->assertSame(8000.0, $this->service->remainingBalance($fee->fresh()));
    }

    public function test_percentage_award_computes_against_balance()
    {
        $fee = $this->fee(10000);
        $this->assertSame(5000.0, $this->service->totalAward($this->scholarship(['value' => 50]), $fee));
    }

    public function test_fixed_award_is_capped_by_the_fee()
    {
        $fee = $this->fee(10000);
        $huge = $this->scholarship(['value_type' => 'fixed', 'value' => 999999]);
        $this->assertSame(10000.0, $this->service->totalAward($huge, $fee)); // hard floor
    }

    public function test_percentage_cap_is_respected()
    {
        $fee = $this->fee(10000);
        $capped = $this->scholarship(['value' => 50, 'max_value' => 3000]);
        $this->assertSame(3000.0, $this->service->totalAward($capped, $fee));
    }

    public function test_full_award_is_the_whole_remaining_balance()
    {
        $fee = $this->fee(10000);
        $this->activeDiscount($fee, 1000);
        $full = $this->scholarship(['award_scope' => 'full']);
        $this->assertSame(9000.0, $this->service->totalAward($full, $fee->fresh()));
    }

    public function test_stacking_compounds_against_remaining_balance()
    {
        $fee = $this->fee(10000);
        // First 50% award already released → 5000 discount active.
        $this->activeDiscount($fee, 5000);

        // A second 50% award must apply to the remaining 5000, not the gross 10000.
        $second = $this->scholarship(['value' => 50]);
        $this->assertSame(2500.0, $this->service->totalAward($second, $fee->fresh()));
    }

    public function test_per_semester_generation_splits_and_releases_first_only()
    {
        $fee = $this->fee(10000);
        foreach ([1, 2] as $seq) {
            IntakeSemester::create([
                'intake_course_id' => 1, 'semester_id' => $seq,
                'starting_date' => now()->addMonths($seq)->toDateString(),
                'ending_date' => now()->addMonths($seq + 3)->toDateString(),
                'due_date' => now()->addMonths($seq)->toDateString(),
                'sequence' => $seq, 'status' => 1,
            ]);
        }

        $scholarship = $this->scholarship(['disbursement' => 'per_semester', 'value' => 40]);
        $application = ScholarshipApplication::create([
            'scholarship_id' => $scholarship->id, 'student_id' => 1, 'intake_course_id' => 1,
            'student_intake_course_fee_id' => $fee->id, 'applied_by' => 1, 'reviewed_by' => 1, 'status' => 1,
        ]);

        $this->service->generate($application);

        $disbursements = ScholarshipDisbursement::where('scholarship_application_id', $application->id)
            ->orderBy('sequence')->get();

        // 40% of 10000 = 4000, split across 2 semesters = 2000 each.
        $this->assertCount(2, $disbursements);
        $this->assertSame(2000.0, (float) $disbursements[0]->planned_amount);
        $this->assertSame(2000.0, (float) $disbursements[1]->planned_amount);
        // Only the first semester releases immediately.
        $this->assertSame(ScholarshipDisbursement::RELEASED, $disbursements[0]->state);
        $this->assertSame(ScholarshipDisbursement::SCHEDULED, $disbursements[1]->state);
        // The released portion materialised exactly one active discount.
        $this->assertSame(2000.0, (float) $fee->fresh()->feeDiscounts()->sum('discount_amount'));
    }

    public function test_one_off_generation_releases_a_single_disbursement()
    {
        $fee = $this->fee(10000);
        $scholarship = $this->scholarship(['disbursement' => 'one_off', 'value' => 25]);
        $application = ScholarshipApplication::create([
            'scholarship_id' => $scholarship->id, 'student_id' => 1, 'intake_course_id' => 1,
            'student_intake_course_fee_id' => $fee->id, 'applied_by' => 1, 'reviewed_by' => 1, 'status' => 1,
        ]);

        $this->service->generate($application);

        $disbursements = ScholarshipDisbursement::where('scholarship_application_id', $application->id)->get();
        $this->assertCount(1, $disbursements);
        $this->assertSame(ScholarshipDisbursement::RELEASED, $disbursements[0]->state);
        $this->assertSame(2500.0, (float) $disbursements[0]->actual_amount);
    }
}
