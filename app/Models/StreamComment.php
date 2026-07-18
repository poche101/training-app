<?php

// app/Models/StreamComment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreamComment extends Model
{
    protected $fillable = ['livestream_id', 'parent_id', 'name', 'body', 'is_admin', 'admin_id'];

    protected $casts = ['is_admin' => 'boolean'];

    public function livestream()
    {
        return $this->belongsTo(Livestream::class);
    }

    public function replies()
    {
        return $this->hasMany(StreamComment::class, 'parent_id')->orderBy('created_at');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

