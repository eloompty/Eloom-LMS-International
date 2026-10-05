<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Address\Entities\Address;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_ceo',
        'email',
        'phone',
        'logo',
        'status'
    ];

    function address()
    {
        return $this->hasOne(Address::class, 'type_id', 'id')->where('type', 'company');
    }
    
    protected static function newFactory()
    {
        return \Modules\Company\Database\factories\CompanyFactory::new();
    }
}
