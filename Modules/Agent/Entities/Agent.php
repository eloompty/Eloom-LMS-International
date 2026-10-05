<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Country\Entities\Country;
use Modules\Student\Entities\StudentAgent;

class Agent extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'agent';

    protected $fillable = [
        'email',
        'password',
        'name',
        'company_name',
        'company_registration',
        'address',
        'city',
        'country_id',
        'office_phone',
        'url',
        'mobile',
        'image',
        'admin_id',
        'status',
        'rate',
    ];

    function branch()
    {
        return $this->hasMany(AgentBranch::class);
    }

    function country()
    {
        return $this->belongsTo(Country::class);
    }

    function main_branch()
    {
        return $this->hasOne(AgentBranch::class)->orderBy('id', 'asc');
    }

    function enrolled_student()
    {
        return $this->hasMany(StudentAgent::class);
    }

    function applied_student()
    {
        return $this->hasMany(StudentAgent::class);
    }

    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\AgentFactory::new();
    }
}
