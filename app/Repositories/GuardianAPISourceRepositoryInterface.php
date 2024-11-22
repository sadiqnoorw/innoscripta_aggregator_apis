<?php

namespace App\Repositories;

/**
 * Interface GuardianAPISourceRepositoryInterface
 * 
 * Defines the contract for a repository that interacts with the Guardian API to 
 * fetch and store news articles.
 */
interface GuardianAPISourceRepositoryInterface
{
    /**
     * Fetch and store news articles from the Guardian API.
     * 
     * - Fetches data from the Guardian API.
     * - Maps the data to a standard structure suitable for the `news_sources` table.
     * - Inserts or updates the data in the database.
     * 
     * @return void
     */
    public function fetchAndStoreNewsFromAPI(): void;
}
