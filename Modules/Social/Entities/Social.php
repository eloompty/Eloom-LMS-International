<?php

namespace Modules\Social\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\Student;

class Social extends Model
{
    use HasFactory;

    protected $fillable = ['social_category_id', 'user_type', 'user_id', 'social_link', 'status'];

    function social_category()
    {
        return $this->belongsTo(SocialCategory::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'id');
    }
    
    protected static function newFactory()
    {
        return \Modules\Social\Database\factories\SocialFactory::new();
    }
}
