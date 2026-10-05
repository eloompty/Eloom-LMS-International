<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Entities\CompanyDeliverySite;

class CourseDeliverySite extends Model
{
    use HasFactory;

    protected $fillable = ['company_delivery_site_id', 'course_id'];

    function course()
    {
        return $this->belongsTo(Course::class);
    }

    function companyDeliverySite()
    {
        return $this->belongsTo(CompanyDeliverySite::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\CourseDeliverySiteFactory::new();
    }
}
