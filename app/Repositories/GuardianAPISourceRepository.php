<?php 

namespace App\Repositories;

use App\Models\NewsSource;
use Guardian\GuardianAPI;
use Illuminate\Support\Collection;

/**
 * Class GuardianAPISourceRepository
 * 
 * Responsible for interacting with the Guardian API, fetching news data, and storing it 
 * in the database. Implements the GuardianAPISourceRepositoryInterface.
 */
class GuardianAPISourceRepository implements GuardianAPISourceRepositoryInterface
{
    /**
     * @var NewsSource The Eloquent model instance for managing the `news_sources` table.
     */
    protected $model;

    /**
     * @var GuardianAPI The service for interacting with the Guardian API.
     */
    protected $guardianAPI;

    /**
     * GuardianAPISourceRepository constructor.
     * 
     * @param NewsSource $newsSource The `NewsSource` model for database operations.
     * @param GuardianAPI $guardianAPI The `GuardianAPI` client for fetching news data.
     */
    public function __construct(NewsSource $newsSource, GuardianAPI $guardianAPI)
    {
        $this->model = $newsSource;
        $this->guardianAPI = $guardianAPI;
    }

    /**
     * Fetch and store news articles from the Guardian API.
     * 
     * - Retrieves data from the Guardian API using the `content` endpoint.
     * - Maps the data into a standardized format compatible with the `news_sources` table.
     * - Upserts the data into the database, ensuring existing records are updated, and new records are added.
     * 
     * @return void
     */
    public function fetchAndStoreNewsFromAPI(): void
    {
        // Fetch data from the Guardian API
        $responses = $this->guardianAPI->content()->setOrderBy("relevance")->fetch();

        // Transform the API response into a collection of formatted articles
        $data = collect($responses->response->results)->map(function ($article) {
            return [
                'slug' => str_replace("/", "-", $article->id) ?? 'new-api-slag',
                'name' => $article->sectionName,
                'description' => $article->webTitle,
                'url' => $article->webUrl,
                'category' => $article->pillarName ?? 'general',
                'language' => 'en',
                'country' => 'us',
                'api_source' => 'guardian_api',
                'fetched_at' => date('Y-m-d H:i:s', strtotime($article->webPublicationDate)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // Perform upsert operation to store the data in the database
        $this->model->upsert(
            $data,
            ['slug'], // Unique key for upsert
            ['name', 'description', 'url', 'category', 'language', 'country', 'fetched_at', 'updated_at'] // Columns to update
        );
    }
}
