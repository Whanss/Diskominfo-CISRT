<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'description',
        'start_at',
        'end_at',
        'location',
        'slug',
        'is_published',
        'image',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('start_at', '>=', now());
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $model) {
            if (empty($model->slug) && !empty($model->title)) {
                $base = Str::slug($model->title);
                $slug = $base;
                $i = 1;
                while (self::where('slug', $slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                    $slug = $base.'-'.(++$i);
                }
                $model->slug = $slug;
            }
        });
    }
}
