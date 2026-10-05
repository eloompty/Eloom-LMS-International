<?php

namespace Modules\Announcement\Entities;

use Illuminate\Database\Eloquent\Model;

class AnnouncementAcknowledgement extends Model
{
    protected $fillable = [
        'announcement_id',
        'reader_type',
        'reader_id',
        'acknowledged_at',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}
