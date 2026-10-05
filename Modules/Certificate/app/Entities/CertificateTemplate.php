<?php

namespace Modules\Certificate\Entities;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'name',
        'type',
        'layout',
        'is_default',
        'status',
    ];

    protected $casts = [
        'layout'     => 'array',
        'is_default' => 'boolean',
    ];

    public function issuedCertificates()
    {
        return $this->hasMany(IssuedCertificate::class);
    }
}
