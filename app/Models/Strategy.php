<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Strategy extends Model
{
    protected $table = 'strategies';

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

    public function scopePublished(Builder $query)
    {
        return $query->where('published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }
}
