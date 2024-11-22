<?php

namespace Database\Factories;

use App\Models\UserPreferences;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPreferencesFactory extends Factory
{
    protected $model = UserPreferences::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'preferred_sources' => ['source1', 'source2'],
            'preferred_categories' => ['category1', 'category2'],
        ];
    }
}
