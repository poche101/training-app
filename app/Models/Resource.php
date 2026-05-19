<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'category_id', 'file_url',
        'file_type', 'file_size', 'download_count', 'is_public', 'uploaded_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'category_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function incrementDownload(): void
    {
        $this->increment('download_count');
    }

    public function getFileIconAttribute(): string
    {
        return match ($this->file_type) {
            'pdf'  => '📄',
            'docx' => '📝',
            'ppt', 'pptx' => '📊',
            'zip'  => '🗜️',
            default => '📁',
        };
    }
}
