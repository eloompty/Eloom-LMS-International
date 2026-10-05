<?php

namespace Modules\Trainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Address\Entities\Address;
use Modules\Course\Entities\WorkPlacement;

class Trainer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'trainer';

    protected $fillable = [
        'salutation',
        'first_name',
        'family_name',
        'date_of_birth',
        'phone',
        'mobile',
        'email',
        'password',
        'image',
        'id_no',
        'status',
    ];

    function address()
    {
        return $this->hasOne(Address::class, 'type_id', 'id')->where('type', 'trainer');
    }

    function qualification()
    {
        return $this->hasMany(TrainerQualification::class);
    }

    function profession()
    {
        return $this->hasMany(TrainerProfessionalDevelopment::class);
    }

    function intake()
    {
        return $this->hasMany(TrainerIntake::class);
    }

    function placement()
    {
        return $this->hasMany(WorkPlacement::class, 'type_id', 'id')->where('type', 'Trainer');
    }

    function devices()
    {
        return $this->hasMany(TrainerDevice::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Trainer\Database\factories\TrainerFactory::new();
    }
}
