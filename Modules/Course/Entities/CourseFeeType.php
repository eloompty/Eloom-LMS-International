<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseFeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_fee_id',
        'key',
        'value',
        'type',
        'status'
    ];

    function courseFee()
    {
        return $this->belongsTo(CourseFee::class);
    }

    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\CourseFeeTypeFactory::new();
    }
}
