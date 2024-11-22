<?php

namespace Database\Factories;

use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsSourceFactory extends Factory
{
    public function definition()
    {
        return [
            'slug' => $this->faker->slug,
            'name' => $this->faker->company,
            'description' => $this->faker->sentence,
            'url' => $this->faker->url,
            'category' => $this->faker->randomElement(['category1', 'category2']),
            'language' => $this->faker->languageCode,
            'country' => $this->faker->countryCode,
            'api_source' => $this->faker->randomElement(['source1', 'source2']),
            'fetched_at' => $this->faker->dateTime,
        ];
    }
}
