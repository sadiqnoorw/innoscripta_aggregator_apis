<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'description', 'url', 'category', 'language', 'api_source', 'fetched_at', 'country',
    ];

    // Optionally, set table name if not following naming conventions
    protected $table = 'news_sources';
}
