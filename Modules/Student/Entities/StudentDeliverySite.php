<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Entities\CompanyDeliverySite;

class StudentDeliverySite extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'company_delivery_site_id', 'status'];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    function companyDeliverySite()
    {
        return $this->belongsTo(CompanyDeliverySite::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentDeliverySiteFactory::new();
    }
}
