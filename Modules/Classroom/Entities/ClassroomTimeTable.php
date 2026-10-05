<?php

namespace Modules\Classroom\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassroomTimeTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'day',
        'from',
        'to',
        'status'
    ];

    function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    protected static function newFactory()
    {
        return \Modules\Classroom\Database\factories\ClassroomTimeTableFactory::new();
    }
}
