<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // Removed HasApiTokens from the line below:
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'role',
        'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Roles
    const ROLE_MEMBER       = 'member';
    const ROLE_MODERATOR    = 'moderator';
    const ROLE_RESOURCE_MGR = 'resource_manager';
    const ROLE_MEDIA_ADMIN  = 'media_admin';
    const ROLE_OUTREACH     = 'outreach_admin';
    const ROLE_SUPER_ADMIN  = 'super_admin';

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            self::ROLE_MODERATOR,
            self::ROLE_RESOURCE_MGR,
            self::ROLE_MEDIA_ADMIN,
            self::ROLE_OUTREACH,
            self::ROLE_SUPER_ADMIN,
        ]);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isMember(): bool
    {
        return $this->role === self::ROLE_MEMBER;
    }

    // Relationships
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'admin_id');
    }

    public function eventRsvps()
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function uploadedResources()
    {
        return $this->hasMany(Resource::class, 'uploaded_by');
    }
}
