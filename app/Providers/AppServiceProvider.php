<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Auth\AuthRepository;

use App\Repositories\NewsAPISourceRepositoryInterface;
use App\Repositories\NewsAPISourceRepository;

use App\Repositories\GuardianAPISourceRepositoryInterface;
use App\Repositories\GuardianAPISourceRepository;

use jcobhams\NewsApi\NewsApi;
use Guardian\GuardianAPI;

use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\ArticleRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the AuthRepositoryInterface to the concrete AuthRepository class
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        
        // Bind the  to the concrete NewsAPISourceRepository class
        $this->app->bind(NewsAPISourceRepositoryInterface::class, NewsAPISourceRepository::class);

        // Bind the ArticleRepositoryInterface to the concrete ArticleRepository class
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);

        // Bind the GuardianAPISourceRepositoryInterface to the concrete GuardianAPISourceRepository class
        $this->app->bind(GuardianAPISourceRepositoryInterface::class, GuardianAPISourceRepository::class);

        // Bind NewsApi to a singleton and pass the API key to the constructor
        $this->app->singleton(NewsApi::class, function ($app) {
            // Get API key from .env or provide a default one
            $apiKey = env('NEWS_API_KEY', 'f9f3c51ede6e4b67a7264cd80b343bdb');  // Ensure you have this key in .env

            // Return an instance of NewsApi with the API key
            return new NewsApi($apiKey);
        });

        // GuardianAPI to a singleton and pass the API key to the constructor
        $this->app->singleton(GuardianAPI::class, function ($app) {
            // Get API key from .env or provide a default one
            $guardianapiKey = env('GUARDIAN_API_KEY', '842d07aa-80dc-4554-9d1f-2daf75bd7b31');  // Ensure you have this key in .env

            // Return an instance of NewsApi with the API key
            return new GuardianAPI($guardianapiKey);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
