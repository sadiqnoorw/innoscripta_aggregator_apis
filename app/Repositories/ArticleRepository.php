<?php

namespace App\Repositories;

use App\Models\NewsSource; // Assuming NewsSource is the model for your articles
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ArticleRepository
 * 
 * Implements the `ArticleRepositoryInterface` for managing article data.
 * Provides methods to fetch articles with filters and pagination, as well as retrieve individual articles by ID or slug.
 */
class ArticleRepository implements ArticleRepositoryInterface
{
    /**
     * @var NewsSource The NewsSource model instance.
     */
    protected $model;

    /**
     * ArticleRepository constructor.
     * 
     * @param NewsSource $newsSource The model representing articles in the database.
     */
    public function __construct(NewsSource $newsSource)
    {
        $this->model = $newsSource;
    }

    /**
     * Fetch articles with pagination and optional filters.
     * 
     * This method supports filtering by keyword, date, category, and source, and returns paginated results.
     * 
     * @param array $filters Associative array of filters:
     * - `keyword` (string): Keyword to search in the article name or description.
     * - `date` (string): Filter articles by the fetched date (YYYY-MM-DD).
     * - `category` (string): Filter articles by category.
     * - `source` (string): Filter articles by API source.
     * @param int $perPage The number of articles to return per page. Default is 10.
     * 
     * @return LengthAwarePaginator Paginated list of articles matching the filters.
     */
    public function fetchArticles(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Filter by keyword in name and description
        if (isset($filters['keyword']) && $filters['keyword']) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%" . $filters['keyword'] . "%")
                  ->orWhere('description', 'like', "%" . $filters['keyword'] . "%");
            });
        }

        // Filter by date (fetched_at)
        if (isset($filters['date']) && $filters['date']) {
            $query->whereDate('fetched_at', $filters['date']);
        }

        // Filter by category
        if (isset($filters['category']) && $filters['category']) {
            $query->where('category', $filters['category']);
        }

        // Filter by source (api_source)
        if (isset($filters['source']) && $filters['source']) {
            $query->where('api_source', $filters['source']);
        }

        // Return paginated results
        return $query->paginate($perPage);
    }

    /**
     * Get a single article by its slug.
     * 
     * This method retrieves an article based on its unique slug identifier.
     * 
     * @param string $slug The unique slug of the article.
     * 
     * @return array|null The article details as an associative array, or null if not found.
     */
    public function getArticleBySlug(string $slug): ?array
    {
        return $this->model->where('slug', $slug)->first()?->toArray();
    }

    /**
     * Get a single article by its ID.
     * 
     * This method retrieves an article based on its unique numeric ID.
     * 
     * @param int $id The unique ID of the article.
     * 
     * @return array|null The article details as an associative array, or null if not found.
     */
    public function getArticleById(int $id): ?array
    {
        return $this->model->find($id)?->toArray();
    }
}
