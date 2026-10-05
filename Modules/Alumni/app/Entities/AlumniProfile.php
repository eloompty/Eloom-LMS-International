<?php

namespace Modules\Alumni\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;

class AlumniProfile extends Model
{
    protected $fillable = [
        'student_id',
        'student_intake_course_id',
        'graduation_date',
        'employer_name',
        'job_title',
        'employment_start_date',
        'industry',
        'directory_visible',
        'interested_in_reenrollment',
        'reenrollment_notes',
        'status',
    ];

    protected $casts = [
        'directory_visible'           => 'boolean',
        'interested_in_reenrollment'  => 'boolean',
        'graduation_date'             => 'date',
        'employment_start_date'       => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function intakeCourse()
    {
        return $this->belongsTo(StudentIntakeCourse::class, 'student_intake_course_id');
    }

    /**
     * Auto-create an alumni profile when a student completes a course.
     * Called from wherever course completion is confirmed.
     */
    public static function createFromCompletion(int $studentId, int $studentIntakeCourseId): self
    {
        return self::firstOrCreate(
            ['student_id' => $studentId],
            [
                'student_intake_course_id' => $studentIntakeCourseId,
                'graduation_date'          => now()->toDateString(),
                'status'                   => 1,
            ]
        );
    }
}
