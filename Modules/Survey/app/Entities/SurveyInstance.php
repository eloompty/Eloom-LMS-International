<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class SurveyInstance extends Model
{
    protected $fillable = ['survey_template_id','target_type','target_id','dispatch_at','closes_at','notified_at','created_by_id','created_by_type','status'];

    protected $casts = ['dispatch_at' => 'datetime', 'closes_at' => 'datetime', 'notified_at' => 'datetime'];

    public function template()
    {
        return $this->belongsTo(SurveyTemplate::class, 'survey_template_id');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function isOpen(): bool
    {
        $now = now();
        $dispatched = $this->dispatch_at === null || $now->gte($this->dispatch_at);
        $notClosed  = $this->closes_at === null || $now->lte($this->closes_at);
        return $this->status === 1 && $dispatched && $notClosed;
    }
}
