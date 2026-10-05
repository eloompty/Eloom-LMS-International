<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'amount',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Fee\Database\factories\FeeTypeFactory::new();
    }
}
