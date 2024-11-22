<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'url', 'category', 'language', 'api_source', 'fetched_at', 'country',
    ];

    // Optionally, set table name if not following naming conventions
    protected $table = 'news_sources';
}
