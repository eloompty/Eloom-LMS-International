<?php

namespace Modules\Trainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainerProfessionalDevelopment extends Model
{
    use HasFactory;

    protected $fillable = ['trainer_id', 'title', 'duration', 'start_date', 'end_date', 'status'];

    function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Trainer\Database\factories\TrainerProfessionalDevelopmentFactory::new();
    }
}
