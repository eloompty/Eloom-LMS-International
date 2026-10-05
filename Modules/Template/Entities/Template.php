<?php

namespace Modules\Template\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'status'
    ];

    function templateData() {
        return $this->hasMany(TemplateData::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Template\Database\factories\TemplateFactory::new();
    }
}
