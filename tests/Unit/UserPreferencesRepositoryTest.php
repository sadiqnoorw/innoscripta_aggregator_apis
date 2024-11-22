<?php

namespace Tests\Unit;

use App\Models\UserPreferences;
use App\Models\NewsSource;
use App\Repositories\Auth\UserPreferencesRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferencesRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserPreferencesRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize repository
        $this->repository = new UserPreferencesRepository();

        // Create a test user
        $this->user = \App\Models\User::factory()->create();
    }

    public function test_set_preferences(): void
    {
        $userId = 1;
        $preferences = [
            'preferred_sources' => ['source1', 'source2'],
            'preferred_categories' => ['category1', 'category2'],
        ];

        $userPreferences = $this->repository->setPreferences($userId, $preferences);

        $this->assertInstanceOf(UserPreferences::class, $userPreferences);
        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $userId,
            'preferred_sources' => json_encode($preferences['preferred_sources']),
            'preferred_categories' => json_encode($preferences['preferred_categories']),
        ]);
    }

    public function test_get_preferences(): void
    {
        $userPreferences = \App\Models\UserPreferences::factory()->create([
            'user_id' => $this->user->id,
            'preferred_sources' => ['source1', 'source2'],
            'preferred_categories' => ['category1', 'category2'],
        ]);

        $fetchedPreferences = $this->repository->getPreferences($this->user->id);

        $this->assertNotNull($fetchedPreferences);
        $this->assertEquals($userPreferences->toArray(), $fetchedPreferences->toArray());
    }

    public function test_get_preferences_returns_null_if_not_found(): void
    {
        $userPreferences = $this->repository->getPreferences(999);

        $this->assertNull($userPreferences);
    }

    public function test_get_personalized_news_feed(): void
    {
        \App\Models\UserPreferences::factory()->create([
            'user_id' => $this->user->id,
            'preferred_sources' => ['source1'],
            'preferred_categories' => ['category1'],
        ]);
    
        \App\Models\NewsSource::factory()->create([
            'api_source' => 'source1',
            'category' => 'category1',
        ]);
    
        \App\Models\NewsSource::factory()->create([
            'api_source' => 'source2',
            'category' => 'category2',
        ]);
    
        $newsFeed = $this->repository->getPersonalizedNewsFeed($this->user->id);
    
        $this->assertCount(1, $newsFeed);
        $this->assertEquals('source1', $newsFeed->first()->api_source);
        $this->assertEquals('category1', $newsFeed->first()->category);
    }

    public function test_get_personalized_news_feed_returns_empty_if_no_preferences(): void
    {
        $userId = 1;

        $newsFeed = $this->repository->getPersonalizedNewsFeed($userId);

        $this->assertCount(0, $newsFeed);
    }
}
