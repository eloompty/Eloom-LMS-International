<?php

namespace Modules\Announcement\Entities;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'body',
        'audience_type',
        'audience_id',
        'priority',
        'require_acknowledgement',
        'is_pinned',
        'publish_at',
        'expires_at',
        'created_by_user_id',
        'created_by_type',
        'status',
    ];

    protected $casts = [
        'require_acknowledgement' => 'boolean',
        'is_pinned'               => 'boolean',
        'publish_at'              => 'datetime',
        'expires_at'              => 'datetime',
    ];

    public function acknowledgements()
    {
        return $this->hasMany(AnnouncementAcknowledgement::class);
    }

    // Announcements visible right now (published and not expired)
    public function scopeVisible($query)
    {
        return $query->where('status', 1)
            ->where(fn ($q) => $q->whereNull('publish_at')->orWhere('publish_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    // Scoped to a specific audience (all, or matching type/id)
    public function scopeForAudience($query, string $audienceType, ?int $audienceId = null)
    {
        return $query->where(function ($q) use ($audienceType, $audienceId) {
            $q->where('audience_type', 'all')
              ->orWhere(function ($q2) use ($audienceType, $audienceId) {
                  $q2->where('audience_type', $audienceType);
                  if ($audienceId !== null) {
                      $q2->where('audience_id', $audienceId);
                  }
              });
        });
    }
}
