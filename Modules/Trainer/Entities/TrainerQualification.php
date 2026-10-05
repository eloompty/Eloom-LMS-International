<?php

namespace Modules\Trainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Country\Entities\Country;

class TrainerQualification extends Model
{
    use HasFactory;

    protected $fillable = ['trainer_id', 'name', 'award_university', 'award_year', 'country_id', 'status'];

    function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Trainer\Database\factories\TrainerQualificationFactory::new();
    }
}
