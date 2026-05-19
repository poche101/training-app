<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'admin_id', 'action', 'description', 'model_type', 'model_id', 'ip_address',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function record(string $action, string $description = '', $model = null): void
    {
        static::create([
            'admin_id'    => auth()->id(),
            'action'      => $action,
            'description' => $description,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model?->id,
            'ip_address'  => request()->ip(),
        ]);
    }
}
