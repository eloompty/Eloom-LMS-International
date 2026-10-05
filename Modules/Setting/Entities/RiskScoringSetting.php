<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class RiskScoringSetting extends Model
{
    protected $table = 'risk_scoring_settings';

    protected $fillable = [
        'attendance_weight',
        'assignment_weight',
        'grade_weight',
        'fee_weight',
        'engagement_weight',
    ];
}
