<?php

namespace Modules\University\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Country\Entities\Country;

class University extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id', 'status'];

    function qualification()
    {
        return $this->hasMany(UniversityQualification::class);
    }

    function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\University\Database\factories\UniversityFactory::new();
    }
}
