<?php

namespace Modules\Document\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentDocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Document\Database\factories\StudentDocumentTypeFactory::new();
    }
}
