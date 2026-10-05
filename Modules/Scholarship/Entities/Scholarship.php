<?php

namespace Modules\Scholarship\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'award_scope',
        'disbursement',
        'requires_maintenance',
        'maintenance_min_percentage',
        'max_semesters',
        'value_type',
        'value',
        'max_value',
        'quota',
        'description',
        'eligibility_criteria',
        'status',
    ];

    public function applications()
    {
        return $this->hasMany(ScholarshipApplication::class);
    }

    public function activeApplicationsCount()
    {
        return $this->applications()->where('status', 1)->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'merit'             => 'Merit',
            'need_based'        => 'Need Based',
            'staff'             => 'Staff',
            'early_enrollment'  => 'Early Enrollment',
            'agent_negotiated'  => 'Agent Negotiated',
        ];
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getValueDisplayAttribute()
    {
        if ($this->value_type === 'percentage') {
            return $this->value . '%' . ($this->max_value ? ' (max $' . number_format($this->max_value, 2) . ')' : '');
        }
        return '$' . number_format($this->value, 2);
    }

    public function getAwardScopeLabelAttribute()
    {
        return $this->award_scope === 'full' ? 'Full' : 'Partial';
    }

    public function getDisbursementLabelAttribute()
    {
        return $this->disbursement === 'per_semester' ? 'Per Semester' : 'One-off';
    }
}
