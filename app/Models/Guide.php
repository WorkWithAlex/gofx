<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Guide extends Model
{
    protected $table = 'guides';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'author',
        'meta_description',
        'published',
        'published_at',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // scope for published items
    public function scopePublished(Builder $query)
    {
        return $query->where('published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }
}
