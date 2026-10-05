<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Modules\Attendance\Entities\Attendance;
use Modules\Report\Services\StudentRiskAnalyzer;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Covers StudentRiskAnalyzer::attendanceScore() — the excused/late weighting the spec
 * flagged as "hard to eyeball and expensive to get wrong".
 */
class AttendanceScoringTest extends TestCase
{
    use RefreshDatabase;

    private const STUDENT_ID = 1;
    private const UNIT_ID = 10;

    private function mark(string $status, int $daysAgo = 3): void
    {
        Attendance::create([
            'student_id' => self::STUDENT_ID,
            'date' => Carbon::now()->subDays($daysAgo)->toDateString(),
            'intake_unit_id' => self::UNIT_ID,
            'user_id' => 1,
            'user_type' => 'Admin',
            'status' => 1,
            'attendance_status' => $status,
        ]);
    }

    private function score(): array
    {
        $method = new ReflectionMethod(StudentRiskAnalyzer::class, 'attendanceScore');
        $method->setAccessible(true);
        return $method->invoke(new StudentRiskAnalyzer(), self::STUDENT_ID, collect([self::UNIT_ID]), collect());
    }

    public function test_late_counts_as_attended_and_excused_is_excluded_from_both_sides()
    {
        // 2 present, 1 late, 1 excused, 1 absent.
        $this->mark('present');
        $this->mark('present');
        $this->mark('late');
        $this->mark('excused');
        $this->mark('absent');

        $result = $this->score();

        // Excused excluded from denominator → required = present+late+absent = 4.
        $this->assertSame(4, $result['details']['required']);
        // present + late count as attended → 3.
        $this->assertSame(3, $result['details']['attended']);
        $this->assertSame(1, $result['details']['late']);
        $this->assertSame(1, $result['details']['excused']);
        // 3/4 = 75%.
        $this->assertSame(75.0, $result['details']['percentage']);
        // 70–79% band → score 50.
        $this->assertSame(50, $result['score']);
    }

    public function test_excused_only_yields_no_records()
    {
        $this->mark('excused');
        $this->mark('excused');

        $result = $this->score();

        $this->assertSame(0, $result['details']['required']);
        $this->assertNull($result['details']['percentage']);
        $this->assertSame(0, $result['score']);
    }

    public function test_all_present_is_full_marks_zero_risk()
    {
        $this->mark('present');
        $this->mark('present');
        $this->mark('present');

        $result = $this->score();

        $this->assertSame(100.0, $result['details']['percentage']);
        $this->assertSame(0, $result['score']);
    }

    public function test_poor_attendance_scores_maximum_risk()
    {
        // 1 present, 4 absent → 20% → below 60% band → score 100.
        $this->mark('present');
        $this->mark('absent');
        $this->mark('absent');
        $this->mark('absent');
        $this->mark('absent');

        $result = $this->score();

        $this->assertSame(20.0, $result['details']['percentage']);
        $this->assertSame(100, $result['score']);
    }

    public function test_rows_outside_the_30_day_window_are_ignored()
    {
        $this->mark('present', 3);
        $this->mark('absent', 45); // outside window

        $result = $this->score();

        $this->assertSame(1, $result['details']['required']);
        $this->assertSame(100.0, $result['details']['percentage']);
    }
}
