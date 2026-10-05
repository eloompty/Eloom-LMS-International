<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'header',
        'footer',
        'layout',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Report\Database\factories\ReportTemplateFactory::new();
    }
}
