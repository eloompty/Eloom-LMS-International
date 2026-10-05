<?php

namespace Modules\Classroom\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Trainer\Entities\Trainer;

class ClassroomTrainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'trainer_id',
        'status'
    ];

    function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    protected static function newFactory()
    {
        return \Modules\Classroom\Database\factories\ClassroomTrainerFactory::new();
    }
}
