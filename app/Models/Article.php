<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = ['title','slug','excerpt','body','published_at','author','meta_description','published'];

    protected $casts = ['published_at' => 'datetime'];

    public function scopePublished(Builder $q)
    {
        return $q->where('published', true)->whereNotNull('published_at')->where('published_at','<=',now());
    }
}
