<?php

namespace Modules\Template\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateData extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'key',
        'value',
        'status'
    ];

    function template()
    {
        return $this->belongsTo(Template::class);
    }

    protected static function newFactory()
    {
        return \Modules\Template\Database\factories\TemplateDataFactory::new();
    }
}
