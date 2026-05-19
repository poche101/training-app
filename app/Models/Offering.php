<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offering extends Model
{
    protected $fillable = [
        'title', 'description', 'account_name', 'account_number',
        'bank_name', 'payment_link', 'payment_provider', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
