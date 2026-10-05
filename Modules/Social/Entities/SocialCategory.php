<?php

namespace Modules\Social\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Social\Database\factories\SocialCategoryFactory::new();
    }
}
