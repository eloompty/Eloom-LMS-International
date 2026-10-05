<?php

namespace Modules\OnlineClass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OnlineClassGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    function onlineClassGroupStudent()
    {
        return $this->hasMany(OnlineClassGroupStudent::class);
    }

    protected static function newFactory()
    {
        return \Modules\OnlineClass\Database\factories\OnlineClassGroupFactory::new();
    }
}
