<?php

namespace Modules\Marking\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarkingType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'full_marks',
        'pass_marks',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Marking\Database\factories\MarkingTypeFactory::new();
    }
}
