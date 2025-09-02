<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'category',
        'is_published',
        'slug',
        'excerpt'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto generate slug when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });

        static::updating(function ($news) {
            if ($news->isDirty('title')) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    // Scope for published news
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope for category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Get excerpt or truncated content
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 150);
    }

    // Get category label
    public function getCategoryLabelAttribute()
    {
        $labels = [
            'alert' => 'SECURITY ALERT',
            'tips' => 'SECURITY GUIDELINE',
            'news' => 'THREAT INTEL',
            'update' => 'SYSTEM UPDATE'
        ];

        return $labels[$this->category] ?? 'THREAT INTEL';
    }
}
