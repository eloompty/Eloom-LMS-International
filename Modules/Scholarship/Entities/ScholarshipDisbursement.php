<?php

namespace Modules\Scholarship\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeSemester;

class ScholarshipDisbursement extends Model
{
    use HasFactory;

    const SCHEDULED = 'scheduled';
    const RELEASED  = 'released';
    const WITHHELD  = 'withheld';
    const CANCELLED = 'cancelled';

    protected $fillable = [
        'scholarship_application_id',
        'intake_semester_id',
        'sequence',
        'planned_amount',
        'actual_amount',
        'fee_discount_id',
        'state',
        'maintenance_percentage_achieved',
        'state_reason',
        'evaluated_at',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
    ];

    public function scholarshipApplication()
    {
        return $this->belongsTo(ScholarshipApplication::class);
    }

    public function intakeSemester()
    {
        return $this->belongsTo(IntakeSemester::class);
    }

    public function feeDiscount()
    {
        return $this->belongsTo(FeeDiscount::class);
    }

    public function getStateLabelAttribute()
    {
        return ucfirst($this->state);
    }

    public function getStateClassAttribute()
    {
        return match ($this->state) {
            self::RELEASED  => 'success',
            self::SCHEDULED => 'info',
            self::WITHHELD  => 'warning',
            self::CANCELLED => 'secondary',
            default         => 'secondary',
        };
    }
}
