<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'location', 'banner',
        'start_date', 'end_date', 'requires_rsvp', 'max_attendees', 'created_by',
    ];

    protected $casts = [
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
        'requires_rsvp' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rsvps()
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function isUpcoming(): bool
    {
        return $this->start_date->isFuture();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())->orderBy('start_date');
    }
}
