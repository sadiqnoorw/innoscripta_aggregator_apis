<?php

namespace App\Repositories\Auth;

use App\Models\UserPreferences;
use Illuminate\Support\Collection;

interface UserPreferencesRepositoryInterface
{
    public function setPreferences(int $userId, array $preferences): UserPreferences;

    public function getPreferences(int $userId): ?UserPreferences;

    public function getPersonalizedNewsFeed(int $userId): Collection;
}
