<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'first_name',
        'family_name',
        'email',
        'phone',
        'converted',
        'lead_status',
        'approval_state',
        'status'
    ];

    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\LeadFactory::new();
    }
}
