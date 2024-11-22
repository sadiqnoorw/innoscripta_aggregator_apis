<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'url',
        'category',
        'language',
        'api_source',
        'fetched_at',
        'country'
    ];
    protected $table = 'news_sources';
}
