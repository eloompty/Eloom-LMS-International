<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Trainer\Entities\Trainer;

class WorkPlacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'placement_company_name',
        'type', // 'course' for course table
        'type_id', // course_id for course table
        'contact_person',
        'contact_person_mobile',
        'position',
        'placement_hours',
        'placement_description',
        'site_name',
        'starting_date',
        'ending_date',
        'status',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'type_id', 'id');
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\WorkPlacementFactory::new();
    }
}
