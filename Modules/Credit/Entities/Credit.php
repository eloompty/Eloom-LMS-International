<?php

namespace Modules\Credit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Credit\Database\factories\CreditFactory::new();
    }
}
