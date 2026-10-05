<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Entities\CompanyDeliverySite;

class UserDeliverySite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_delivery_site_id',
        'status'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function companyDeliverySite()
    {
        return $this->belongsTo(CompanyDeliverySite::class);
    }

    protected static function newFactory()
    {
        return \Modules\User\Database\factories\UserDeliverySiteFactory::new();
    }
}
