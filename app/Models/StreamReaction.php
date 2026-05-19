<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreamReaction extends Model
{
    protected $fillable = ['livestream_id', 'user_id', 'emoji'];

    public function user()       { return $this->belongsTo(User::class); }
    public function livestream() { return $this->belongsTo(Livestream::class); }
}
