<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Address\Entities\Address;

class CompanyDeliverySite extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'site_name',
        'phone',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    function address()
    {
        return $this->hasOne(Address::class, 'type_id', 'id')->where('type', 'company_delivery_site');
    }
    
    protected static function newFactory()
    {
        return \Modules\Company\Database\factories\CompanyDeliverySiteFactory::new();
    }
}
