<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

class EventSession extends Model
{
    protected $fillable = ['event_id', 'title', 'starts_at', 'ends_at'];

    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime'];

    public function event()
    {
        return $this->belongsTo(LmsEvent::class, 'event_id');
    }
}
