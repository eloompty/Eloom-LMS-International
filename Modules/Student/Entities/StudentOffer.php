<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'offer_template_id',
        'intake_course_ids',
        'condition_title',
        'condition_description',
        'credit_title',
        'credit_description',
        'issue_date',
        'expiry_date',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function offerTemplate()
    {
        return $this->belongsTo(StudentOfferTemplate::class, 'offer_template_id');
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentOfferFactory::new();
    }
}
