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
        'category_id',  // FK to news_categories
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

    // Relation to category
    public function category()
    {
        return $this->belongsTo(\App\Models\NewsCategory::class, 'category_id');
    }

    // Scope for category (accepts id or slug)
    public function scopeByCategory($query, $category)
    {
        // If numeric, treat as category_id
        if (is_numeric($category)) {
            return $query->where('category_id', $category);
        }
        // Try resolve slug to id
        $cat = \App\Models\NewsCategory::where('slug', $category)->first();
        if ($cat) {
            return $query->where('category_id', $cat->id);
        }
        return $query;
    }

    // Get excerpt or truncated content
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 150);
    }

    // Get category label: prefer related category name
    public function getCategoryLabelAttribute()
    {
        if ($this->category_id && ($this->relationLoaded('category') ? $this->category : $this->category()->exists())) {
            $related = $this->category ?? $this->category()->first();
            if ($related && !empty($related->name)) {
                return $related->name; // use admin-defined category name
            }
        }

        return 'Uncategorized';
    }

    // Derive a slug for category for styling (prefer related slug)
    public function getCategorySlugAttribute()
    {
        if ($this->category_id && ($this->relationLoaded('category') ? $this->category : $this->category()->exists())) {
            $related = $this->category ?? $this->category()->first();
            if ($related && !empty($related->slug)) {
                return $related->slug;
            }
        }
        return 'uncategorized';
    }

    // Map category to badge class used in views
    public function getCategoryBadgeClassAttribute()
    {
        $slug = $this->category_slug;
        return match ($slug) {
            'alert' => 'danger',
            'tips' => 'info',
            'update' => 'warning',
            'news' => 'primary',
            default => 'primary',
        };
    }
}
