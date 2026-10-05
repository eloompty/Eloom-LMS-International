<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPasswordReset extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'code', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\User\Database\factories\UserPasswordResetFactory::new();
    }
}
