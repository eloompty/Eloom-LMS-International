<?php

namespace Modules\University\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UniversityQualification extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'university_id', 'status'];

    function university()
    {
        return $this->belongsTo(University::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\University\Database\factories\UniversityQualificationFactory::new();
    }
}
