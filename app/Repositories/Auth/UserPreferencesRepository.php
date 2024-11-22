<?php

namespace App\Repositories\Auth;

use App\Models\UserPreferences;
use Illuminate\Support\Collection;

class UserPreferencesRepository implements UserPreferencesRepositoryInterface
{
    /**
     * Set or update user preferences.
     *
     * @param int $userId
     * @param array $preferences
     * @return UserPreferences
     */
    public function setPreferences(int $userId, array $preferences): UserPreferences
    {
        return UserPreferences::updateOrCreate(
            ['user_id' => $userId],
            $preferences
        );
    }

    /**
     * Get user preferences by user ID.
     *
     * @param int $userId
     * @return UserPreferences|null
     */
    public function getPreferences(int $userId): ?UserPreferences
    {
        return UserPreferences::where('user_id', $userId)->first();
    }

    /**
     * Get personalized news feed based on user preferences.
     *
     * @param int $userId
     * @return Collection
     */
    public function getPersonalizedNewsFeed(int $userId): Collection
    {
        $userPreferences = $this->getPreferences($userId);

        if (!$userPreferences) {
            return collect(); // Return an empty collection if no preferences are found.
        }

        $sources = $userPreferences->preferred_sources;
        $categories = $userPreferences->preferred_categories;

        $newsFeed = \App\Models\NewsSource::query();

        if ($sources) {
            $newsFeed->whereIn('api_source', $sources);
        }

        if ($categories) {
            $newsFeed->whereIn('category', $categories);
        }

        return $newsFeed->latest()->get(); // Or paginate if needed
    }
}
