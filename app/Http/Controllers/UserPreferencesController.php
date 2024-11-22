<?php

namespace App\Http\Controllers;

use App\Repositories\Auth\UserPreferencesRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferencesController extends Controller
{
    protected $preferencesRepo;

    public function __construct(UserPreferencesRepositoryInterface $preferencesRepo)
    {
        $this->preferencesRepo = $preferencesRepo;
    }

    /**
     * Set the user preferences for news sources, categories, and authors.
     */
    public function setPreferences(Request $request)
    {
        $validated = $request->validate([
            'preferred_sources' => 'array|nullable',
            'preferred_categories' => 'array|nullable',
        ]);

        $userPreferences = $this->preferencesRepo->setPreferences(Auth::id(), $validated);

        return response()->json(['message' => 'Preferences saved successfully.'], 200);
    }

    /**
     * Get the user preferences for news sources, categories, and authors.
     */
    public function getPreferences()
    {
        $userPreferences = $this->preferencesRepo->getPreferences(Auth::id());

        if (!$userPreferences) {
            return response()->json(['message' => 'Preferences not set'], 404);
        }

        return response()->json($userPreferences, 200);
    }

    /**
     * Fetch a personalized news feed based on user preferences.
     */
    public function getPersonalizedNewsFeed()
    {
        $newsFeed = $this->preferencesRepo->getPersonalizedNewsFeed(Auth::id());

        if ($newsFeed->isEmpty()) {
            return response()->json(['message' => 'No personalized news found'], 404);
        }

        return response()->json($newsFeed, 200);
    }
}
