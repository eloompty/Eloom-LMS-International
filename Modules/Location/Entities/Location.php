<?php

namespace Modules\Location\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_id',
        'status'
    ];

    function sublocation()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    function locations()
    {
        return $this->hasMany(Location::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Location\Database\factories\LocationFactory::new();
    }
}
