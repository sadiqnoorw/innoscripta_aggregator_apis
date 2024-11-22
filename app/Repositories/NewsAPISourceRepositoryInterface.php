<?php

namespace App\Repositories;

/**
 * Interface NewsAPISourceRepositoryInterface
 * 
 * Defines the contract for a repository that interacts with the NewsAPI.
 */
interface NewsAPISourceRepositoryInterface
{
    /**
     * Fetch and store news sources from the NewsAPI.
     * 
     * - Fetches data from the external NewsAPI.
     * - Processes and persists the data into the database.
     * 
     * @return void
     */
    public function fetchAndStoreNewsFromAPI(): void;
}
