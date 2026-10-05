<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitAssignmentFile extends Model
{
    use HasFactory;

    protected $fillable = ['unit_assignment_id', 'path', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\UnitAssignmentFileFactory::new();
    }
}
