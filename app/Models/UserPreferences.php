<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreferences extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_sources',
        'preferred_categories',
    ];

    protected $casts = [
        'preferred_sources' => 'array',
        'preferred_categories' => 'array',
    ];

    // Relationship to User (assuming User model exists)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
