<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['livestream_id', 'user_id', 'message', 'is_pinned', 'is_hidden'];

    protected $casts = ['is_pinned' => 'boolean', 'is_hidden' => 'boolean'];

    public function user()       { return $this->belongsTo(User::class); }
    public function livestream() { return $this->belongsTo(Livestream::class); }
}
