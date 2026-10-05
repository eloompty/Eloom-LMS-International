<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = ['survey_template_id','question','type','options','sequence','required'];

    protected $casts = ['options' => 'array', 'required' => 'boolean'];

    public function template()
    {
        return $this->belongsTo(SurveyTemplate::class);
    }
}
