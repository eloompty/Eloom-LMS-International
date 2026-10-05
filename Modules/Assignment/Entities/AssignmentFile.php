<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentFile extends Model
{
    use HasFactory;

    protected $fillable = ['assignment_id', 'path', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentFileFactory::new();
    }
}
