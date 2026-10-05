<?php

namespace Modules\OnlineClass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Trainer\Entities\Trainer;

class OnlineClassGroupTrainer extends Model
{
    use HasFactory;

    protected $fillable = ['online_class_group_id', 'trainer_id', 'status'];

    function group()
    {
        return $this->belongsTo(OnlineClassGroup::class, 'online_class_group_id', 'id');
    }

    function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\OnlineClass\Database\factories\OnlineClassGroupTrainerFactory::new();
    }
}
