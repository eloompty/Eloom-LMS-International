<?php

namespace Modules\Trainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainerPasswordReset extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'code', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Trainer\Database\factories\TrainerPasswordResetFactory::new();
    }
}
