<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'registrant_type',
        'registrant_id',
        'status',
        'registered_at',
        'attended_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'attended_at'   => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(LmsEvent::class, 'event_id');
    }
}
