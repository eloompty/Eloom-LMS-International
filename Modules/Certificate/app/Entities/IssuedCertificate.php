<?php

namespace Modules\Certificate\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;

class IssuedCertificate extends Model
{
    protected $fillable = [
        'uuid',
        'certificate_template_id',
        'student_id',
        'student_intake_course_id',
        'student_intake_unit_id',
        'trigger_type',
        'issued_date',
        'issued_by_user_id',
        'file_path',
        'emailed',
        'revoked',
        'revoke_reason',
        'status',
    ];

    protected $casts = [
        'emailed' => 'boolean',
        'revoked' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function intakeCourse()
    {
        return $this->belongsTo(StudentIntakeCourse::class, 'student_intake_course_id');
    }

    public function getVerifyUrlAttribute(): string
    {
        return route('certificate.verify', $this->uuid);
    }
}
