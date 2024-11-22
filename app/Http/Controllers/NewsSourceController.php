<?php

namespace App\Http\Controllers;

use App\Repositories\NewsAPISourceRepositoryInterface;
use App\Repositories\GuardianAPISourceRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\NewsSource;

/**
 * Class NewsSourceController
 * 
 * This controller handles fetching and displaying news sources.
 * It integrates with multiple API repositories to fetch news and provides endpoints for listing stored news sources.
 */
class NewsSourceController extends Controller
{
    /**
     * @var NewsAPISourceRepositoryInterface
     */
    protected $newsAPISourceRepository;

    /**
     * @var GuardianAPISourceRepositoryInterface
     */
    protected $GuardianAPISourceRepository;

    /**
     * NewsSourceController constructor.
     * 
     * @param NewsAPISourceRepositoryInterface $newsAPISourceRepository Repository for interacting with the NewsAPI source.
     * @param GuardianAPISourceRepositoryInterface $GuardianAPISourceRepository Repository for interacting with the Guardian API source.
     */
    public function __construct(
        NewsAPISourceRepositoryInterface $newsAPISourceRepository,
        GuardianAPISourceRepositoryInterface $GuardianAPISourceRepository
    ) {
        $this->newsAPISourceRepository = $newsAPISourceRepository;
        $this->GuardianAPISourceRepository = $GuardianAPISourceRepository;
    }

    /**
     * Fetch and store news from multiple APIs.
     * 
     * This method triggers the fetch and storage process for news data from both
     * NewsAPI and Guardian API sources. Results are stored in the database.
     * 
     * @return \Illuminate\Http\JsonResponse JSON response indicating success.
     */
    public function fetchNews()
    {
        // Fetch news from both APIs
        $this->newsAPISourceRepository->fetchAndStoreNewsFromAPI();
        $this->GuardianAPISourceRepository->fetchAndStoreNewsFromAPI();

        return response()->json(['message' => 'News fetched and stored from multiple APIs'], 200);
    }

    /**
     * Retrieve all stored news sources.
     * 
     * This method fetches all news sources from the database and returns them in a JSON response.
     * 
     * @return \Illuminate\Http\JsonResponse JSON response containing the list of news sources.
     */
    public function index()
    {
        $newsSources = NewsSource::all();

        return response()->json($newsSources, 200);
    }
}
