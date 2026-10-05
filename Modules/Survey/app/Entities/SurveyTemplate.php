<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class SurveyTemplate extends Model
{
    protected $fillable = ['name','description','created_by_type','created_by_id','is_anonymous','status'];

    protected $casts = ['is_anonymous' => 'boolean'];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('sequence');
    }

    public function instances()
    {
        return $this->hasMany(SurveyInstance::class);
    }
}
