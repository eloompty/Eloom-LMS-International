<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class StudentOfferTemplate extends Model
{
    protected $fillable = [
        'name',
        'layout',
        'status',
    ];
}
