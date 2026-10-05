<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

// Named LmsEvent to avoid clashing with Laravel's built-in Event facade
class LmsEvent extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'title',
        'description',
        'type',
        'location_id',
        'online_link',
        'capacity',
        'registration_deadline',
        'waiting_list_enabled',
        'issue_certificate',
        'certificate_template_id',
        'created_by_user_id',
        'status',
    ];

    protected $casts = [
        'waiting_list_enabled' => 'boolean',
        'issue_certificate'    => 'boolean',
        'registration_deadline'=> 'datetime',
    ];

    public function sessions()
    {
        return $this->hasMany(EventSession::class, 'event_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }

    public function confirmedRegistrations()
    {
        return $this->hasMany(EventRegistration::class, 'event_id')->where('status', 'registered');
    }

    public function isFull(): bool
    {
        if ($this->capacity === null) {
            return false;
        }
        return $this->confirmedRegistrations()->count() >= $this->capacity;
    }
}
