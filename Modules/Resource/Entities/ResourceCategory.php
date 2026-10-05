<?php

namespace Modules\Resource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResourceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_type', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Resource\Database\factories\ResourceCategoryFactory::new();
    }
}
