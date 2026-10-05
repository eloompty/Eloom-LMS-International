<?php

namespace Modules\Course\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'unit_id',
        'type',
        'path',
        'due_date',
        'user_id',
        'status'
    ];

    function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\UnitAssignmentFactory::new();
    }
}
