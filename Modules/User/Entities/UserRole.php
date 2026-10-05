<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_type', 'key', 'value', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\User\Database\factories\UserRoleFactory::new();
    }
}
