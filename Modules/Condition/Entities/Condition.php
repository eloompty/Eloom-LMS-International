<?php

namespace Modules\Condition\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Condition\Database\factories\ConditionFactory::new();
    }
}
