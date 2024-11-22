<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface ArticleRepositoryInterface
 * 
 * Defines the contract for the article repository, providing methods for fetching articles 
 * with filters and pagination, and retrieving individual articles by slug or ID.
 */
interface ArticleRepositoryInterface
{
    /**
     * Fetch a paginated list of articles with optional filters.
     * 
     * This method supports filtering by keyword, date, category, and API source.
     * 
     * @param array $filters Associative array of filters:
     * - `keyword` (string): Keyword to search in the article name or description.
     * - `date` (string): Filter articles by the fetched date (YYYY-MM-DD).
     * - `category` (string): Filter articles by category.
     * - `source` (string): Filter articles by API source.
     * @param int $perPage The number of articles to return per page.
     * 
     * @return LengthAwarePaginator A paginated collection of articles matching the specified filters.
     */
    public function fetchArticles(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Retrieve a single article by its unique slug identifier.
     * 
     * @param string $slug The unique slug of the article.
     * 
     * @return array|null The article details as an associative array, or null if not found.
     */
    public function getArticleBySlug(string $slug): ?array;

    /**
     * Retrieve a single article by its unique numeric ID.
     * 
     * @param int $id The unique ID of the article.
     * 
     * @return array|null The article details as an associative array, or null if not found.
     */
    public function getArticleById(int $id): ?array;
}
