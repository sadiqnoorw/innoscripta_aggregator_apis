<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\NewsAPISourceRepository;
use App\Repositories\GuardianAPISourceRepository;

class FetchAndStoreNewsFromAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-and-store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news from external API and store in the database';

    /**
     * The NewsAPISourceRepository instance.
     *
     * @var \App\Repositories\NewsAPISourceRepository
     */
    protected $newsAPISourceRepository;

    /**
     * The GuardianAPISourceRepository instance.
     *
     * @var \App\Repositories\GuardianAPISourceRepository
     */
    protected $guardianAPISourceRepository;

    /**
     * Create a new command instance.
     *
     * @param \App\Repositories\NewsAPISourceRepository $newsAPISourceRepository
     * @param \App\Repositories\GuardianAPISourceRepository $guardianAPISourceRepository
     * @return void
     */
    public function __construct(
        NewsAPISourceRepository $newsAPISourceRepository,
        GuardianAPISourceRepository $guardianAPISourceRepository
    ){
        parent::__construct();
        $this->newsAPISourceRepository = $newsAPISourceRepository; 
        $this->guardianAPISourceRepository = $guardianAPISourceRepository; 
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Call the method to fetch and store news
        $this->newsAPISourceRepository->fetchAndStoreNewsFromAPI();
        $this->guardianAPISourceRepository->fetchAndStoreNewsFromAPI();
        $this->info('News fetched and stored successfully.');
    }
}
