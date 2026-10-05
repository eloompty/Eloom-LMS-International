<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Address\Entities\Address;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Attendance\Entities\Attendance;
use Modules\Country\Entities\Country;
use Modules\Email\Entities\EmailUser;
use Modules\Social\Entities\Social;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'student';

    protected $fillable = [
        'salutation',
        'first_name',
        'family_name',
        'date_of_birth',
        'passport_no',
        'citizenship',
        'phone',
        'mobile',
        'email',
        'password',
        'image',
        'id_no',
        'status',
        'is_enrolled',
        'remarks',
        'file',
        'email_alternative',
        'gender',
        'citizenship_country',
        'allow_submission_after_due_date',
    ];

    function country()
    {
        return $this->belongsTo(Country::class, 'citizenship_country', 'id');
    }

    function overseasCountry()
    {
        return $this->belongsTo(Country::class, 'overseas_country_id', 'id');
    }

    function guardians()
    {
        return $this->hasMany(StudentGuardian::class);
    }

    function studentStatus()
    {
        return $this->hasMany(StudentStatus::class);
    }

    function address()
    {
        // Backward-compat: resolve to the primary row when present, falling back to any
        // legacy single row (is_primary = false).
        return $this->hasOne(Address::class, 'type_id', 'id')
            ->where('type', 'student')
            ->orderByDesc('is_primary');
    }

    function addresses()
    {
        return $this->hasMany(Address::class, 'type_id', 'id')->where('type', 'student');
    }

    function primaryAddress()
    {
        return $this->hasOne(Address::class, 'type_id', 'id')
            ->where('type', 'student')
            ->where('is_primary', true);
    }

    function intake()
    {
        return $this->hasMany(StudentIntakeCourse::class);
    }

    function credit()
    {
        return $this->hasMany(StudentCredit::class);
    }

    public function social()
    {
        return $this->hasMany(Social::class, 'user_id', 'id')->where('user_type', 'Student');
    }

    function devices()
    {
        return $this->hasMany(StudentDevice::class);
    }

    function deliverySite()
    {
        return $this->hasMany(StudentDeliverySite::class);
    }

    function assignmentSubmission()
    {
        return $this->hasMany(AssignmentSubmission::class)->orderBy('id', 'desc');
    }

    function notes()
    {
        return $this->hasMany(StudentNote::class);
    }

    function attendances()
    {
        return $this->hasMany(Attendance::class)->whereNotNull('intake_unit_id')->whereNull('intake_subject_id');
    }

    function fees()
    {
        return $this->hasMany(StudentIntakeCourseFee::class);
    }

    function studentAgent()
    {
        return $this->hasOne(StudentAgent::class);
    }

    function studentEmail()
    {
        return $this->hasMany(EmailUser::class, 'user_id', 'id')->where('user_type', 'Student');
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentFactory::new();
    }
}
