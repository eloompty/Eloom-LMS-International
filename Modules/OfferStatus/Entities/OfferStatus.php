<?php

namespace Modules\OfferStatus\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfferStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\OfferStatus\Database\factories\OfferStatusFactory::new();
    }
}
