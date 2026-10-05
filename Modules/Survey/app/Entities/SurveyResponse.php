<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $fillable = ['survey_instance_id','respondent_type','respondent_id','answers','submitted_at'];

    protected $casts = ['answers' => 'array', 'submitted_at' => 'datetime'];

    public function instance()
    {
        return $this->belongsTo(SurveyInstance::class);
    }
}
