<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Country\Entities\Country;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'province',
        'district',
        'local_body',
        'ward',
        'tole',
        'address',
        'address_format',
        'address_line_1',
        'address_line_2',
        'building_name',
        'building_number',
        'flat_unit',
        'street_no',
        'street_address',
        'p_o_box',
        'suburb',
        'city',
        'state',
        'state_region',
        'county',
        'postal_code',
        'zip_code',
        'area',
        'emirate',
        'type',
        'type_id',
        'label',
        'is_primary',
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    protected static function newFactory()
    {
        return \Modules\Address\Database\factories\AddressFactory::new();
    }
}
