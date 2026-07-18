<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestream extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'stream_url', 'thumbnail',
        'status', 'platform', 'scheduled_at', 'started_at', 'ended_at', 'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at'   => 'datetime',
        'ended_at'     => 'datetime',
    ];

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_LIVE      = 'live';
    const STATUS_ENDED     = 'ended';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function reactions()
    {
        return $this->hasMany(StreamReaction::class);
    }

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public function scopeLive($query)
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
                     ->where('scheduled_at', '>', now())
                     ->orderBy('scheduled_at');
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if (!$this->stream_url) return null;

        if ($this->platform === 'youtube') {
            preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->stream_url, $m);
            if (isset($m[1])) {
                return "https://www.youtube.com/embed/{$m[1]}?autoplay=1";
            }
        }

        if ($this->platform === 'vimeo') {
            preg_match('/vimeo\.com\/(\d+)/', $this->stream_url, $m);
            if (isset($m[1])) {
                return "https://player.vimeo.com/video/{$m[1]}?autoplay=1";
            }
        }

        return $this->stream_url;
    }

    public function comments()
{
    return $this->hasMany(StreamComment::class);
}

public function topLevelComments()
{
    return $this->comments()->whereNull('parent_id')->with('replies')->orderBy('created_at');
}
}
