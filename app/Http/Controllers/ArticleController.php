<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class ArticleController
 * 
 * Handles article-related operations, such as fetching articles with filters and pagination,
 * retrieving article details by slug, or by ID.
 */
class ArticleController extends Controller
{
    /**
     * @var ArticleRepositoryInterface
     */
    protected $articleRepository;

    /**
     * ArticleController constructor.
     * 
     * @param ArticleRepositoryInterface $articleRepository The repository for managing articles.
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Fetch articles with pagination and optional filters.
     * 
     * This endpoint allows filtering articles by keyword, date, category, or source, and 
     * returns paginated results.
     * 
     * @param Request $request The HTTP request instance containing filter parameters.
     * 
     * @queryParam keyword string Optional. Filter articles by keyword in name or description.
     * @queryParam date string Optional. Filter articles by a specific fetched date (YYYY-MM-DD).
     * @queryParam category string Optional. Filter articles by category.
     * @queryParam source string Optional. Filter articles by API source.
     * @queryParam per_page int Optional. Number of articles per page. Default is 10.
     * 
     * @return \Illuminate\Http\JsonResponse JSON response containing paginated articles.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'date', 'category', 'source']);
        $perPage = $request->get('per_page', 10); // Default to 10 per page

        // Fetch articles with the filters and pagination
        $articles = $this->articleRepository->fetchArticles($filters, $perPage);

        return response()->json($articles);
    }

    /**
     * Retrieve details of a single article by slug.
     * 
     * This endpoint fetches an article's details based on its unique slug.
     * 
     * @param string $slug The unique slug of the article.
     * 
     * @return \Illuminate\Http\JsonResponse JSON response containing the article details, 
     *                                       or a 404 error if the article is not found.
     */
    public function showBySlug($slug)
    {
        $article = $this->articleRepository->getArticleBySlug($slug);

        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        return response()->json($article);
    }

    /**
     * Retrieve details of a single article by ID.
     * 
     * This endpoint fetches an article's details based on its unique numeric ID.
     * 
     * @param mixed $id The unique ID of the article. Must be numeric.
     * 
     * @return \Illuminate\Http\JsonResponse JSON response containing the article details,
     *                                       or an error message if not found or if the ID is invalid.
     * 
     * @response 400 {
     *    "error": "Invalid ID format. ID must be a number."
     * }
     * 
     * @response 404 {
     *    "error": "Article not found."
     * }
     */
    public function showById($id)
    {
        // Validate that the ID is numeric
        if (!is_numeric($id)) {
            return response()->json([
                'error' => 'Invalid ID format. ID must be a number.',
            ], 400); // HTTP 400 Bad Request
        }

        // Fetch the article
        $article = $this->articleRepository->getArticleById((int) $id);

        // If no article is found, return a 404 error
        if (!$article) {
            return response()->json([
                'error' => 'Article not found.',
            ], 404);
        }

        // Return the article details
        return response()->json($article);
    }
}
