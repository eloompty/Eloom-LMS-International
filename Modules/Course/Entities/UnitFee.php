<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'name',
        'fee',
        'status'
    ];

    function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\UnitFeeFactory::new();
    }
}
