<?php 

namespace App\Repositories;

use App\Models\NewsSource;
use jcobhams\NewsApi\NewsApi;
use Illuminate\Support\Collection;

/**
 * Class NewsAPISourceRepository
 * 
 * A repository that interacts with the NewsAPI to fetch and store news sources.
 */
class NewsAPISourceRepository implements NewsAPISourceRepositoryInterface
{
    /**
     * @var NewsSource The model instance used for interacting with the database.
     */
    protected $model;

    /**
     * @var NewsApi The NewsAPI client instance.
     */
    protected $newsApi;

    /**
     * NewsAPISourceRepository constructor.
     *
     * @param NewsSource $newsSource The NewsSource model.
     * @param NewsApi $newsApi The NewsAPI client instance.
     */
    public function __construct(NewsSource $newsSource, NewsApi $newsApi)
    {
        $this->model = $newsSource;
        $this->newsApi = $newsApi; // Dependency Injection
    }

    /**
     * Fetch and store news sources from the NewsAPI.
     * 
     * - Fetches data from the NewsAPI.
     * - Maps the data to a standardized structure suitable for storage.
     * - Performs an upsert operation to insert new records or update existing ones.
     * 
     * @return void
     */
    public function fetchAndStoreNewsFromAPI(): void
    {
        // Use the injected NewsApi instance to get the sources
        $getSources = $this->newsApi->getSources('general', 'en', 'us');

        // Transform and structure the data
        $data = collect($getSources->sources)->map(function ($article) {
            return [
                'slug' => $article->id ?? 'new-api-slag',
                'name' => $article->name,
                'description' => $article->description,
                'url' => $article->url,
                'category' => $article->category ?? 'general',
                'language' => $article->language ?? 'en',
                'country' => $article->country ?? 'us',
                'api_source' => 'new_api',
                'fetched_at' => date('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // Upsert the transformed data into the database
        $this->model->upsert($data, ['slug'], ['name', 'description', 'url', 'category', 'language', 'country', 'fetched_at', 'updated_at']);
    }
}