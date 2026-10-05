<?php

namespace Modules\OnlineClass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OnlineClassRecording extends Model
{
    use HasFactory;

    protected $fillable = [
        'online_class_id',
        'share_url',
        'file_type',
        'file_size',
        'play_url',
        'download_url',
        'password',
        'status'
    ];

    public function onlineClass()
    {
        return $this->belongsTo(OnlineClass::class);
    }

    protected static function newFactory()
    {
        return \Modules\OnlineClass\Database\factories\OnlineClassRecordingFactory::new();
    }
}
