<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    protected $fillable = ['author_name', 'content', 'image', 'is_approved'];

    protected $casts = ['is_approved' => 'boolean'];

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
