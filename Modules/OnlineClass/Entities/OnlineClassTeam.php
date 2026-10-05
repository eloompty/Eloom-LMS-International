<?php

namespace Modules\OnlineClass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Trainer\Entities\Trainer;
use Modules\User\Entities\User;

class OnlineClassTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'subject',
        'content',
        'join_url',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'created_user_id',
        'created_user_type',
        'intake_unit_id',
        'online_class_group_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'id')->where('created_user_type', 'Admin');
    }

    function trainer()
    {
        return $this->belongsTo(Trainer::class, 'created_user_id', 'id')->where('created_user_type', 'Trainer');
    }

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    function group()
    {
        return $this->belongsTo(OnlineClassGroup::class, 'online_class_group_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\OnlineClass\Database\factories\OnlineClassTeamFactory::new();
    }
}
