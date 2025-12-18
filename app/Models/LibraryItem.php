<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryItem extends Model
{
    protected $table = 'library_items';

    protected $fillable = [
        'title',
        'slug',
        'type',
        'url',
        'file_path',
        'file_name',
        'file_size',
        'summary',
        'public',
    ];

    protected $casts = [
        'public' => 'boolean',
    ];

    // helper to return a link (url or file path)
    public function link()
    {
        return $this->url ?: ($this->file_path ? asset($this->file_path) : null);
    }
}
