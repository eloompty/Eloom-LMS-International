<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZohoLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'refresh_token',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\CRM\Database\factories\ZohoLeadFactory::new();
    }
}
