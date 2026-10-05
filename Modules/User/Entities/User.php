<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Country\Entities\Country;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'user';

    protected $fillable = ['first_name', 'family_name', 'email', 'phone', 'image', 'user_type', 'country_id', 'password', 'status', 'theme'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    function userType()
    {
        return $this->belongsTo((UserRole::class), 'user_type', 'user_type');
    }

    function userDeliverySites()
    {
        return $this->hasMany(UserDeliverySite::class);
    }

    protected static function newFactory()
    {
        return \Modules\User\Database\factories\UserFactory::new();
    }
}
