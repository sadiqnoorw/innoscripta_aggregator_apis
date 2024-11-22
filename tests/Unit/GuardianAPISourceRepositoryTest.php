<?php

namespace Tests\Unit\Repositories;

use App\Models\NewsSource;
use App\Repositories\GuardianAPISourceRepository;
use Guardian\GuardianAPI;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class GuardianAPISourceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;
    protected $newsSourceMock;
    protected $guardianAPIMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mocks for dependencies
        $this->newsSourceMock = Mockery::mock(NewsSource::class);
        $this->guardianAPIMock = Mockery::mock(GuardianAPI::class);

        // Instantiate the repository with mocks
        $this->repository = new GuardianAPISourceRepository(
            $this->newsSourceMock,
            $this->guardianAPIMock
        );
    }

    public function testFetchAndStoreNewsFromAPI()
    {
        // Mock response from GuardianAPI
        $apiResponse = (object)[
            'response' => (object)[
                'results' => [
                    (object)[
                        'id' => 'world/2024/11/22/some-news',
                        'sectionName' => 'World News',
                        'webTitle' => 'Some news title',
                        'webUrl' => 'https://example.com/some-news',
                        'pillarName' => 'News',
                        'webPublicationDate' => '2024-11-22T14:30:00Z',
                    ],
                    (object)[
                        'id' => 'tech/2024/11/22/another-news',
                        'sectionName' => 'Tech News',
                        'webTitle' => 'Another news title',
                        'webUrl' => 'https://example.com/another-news',
                        'pillarName' => 'Technology',
                        'webPublicationDate' => '2024-11-22T15:30:00Z',
                    ],
                ]
            ]
        ];

        // Mock GuardianAPI's `content()->setOrderBy()->fetch()`
        $this->guardianAPIMock
            ->shouldReceive('content')
            ->once()
            ->andReturnSelf();
        $this->guardianAPIMock
            ->shouldReceive('setOrderBy')
            ->with('relevance')
            ->once()
            ->andReturnSelf();
        $this->guardianAPIMock
            ->shouldReceive('fetch')
            ->once()
            ->andReturn($apiResponse);

        // Capture data passed to `upsert()` for assertions
        $this->newsSourceMock
            ->shouldReceive('upsert')
            ->once()
            ->with(
                Mockery::on(function ($data) {
                    // Validate data structure and content
                    $this->assertCount(2, $data); // Ensure 2 records
                    $this->assertEquals('world-2024-11-22-some-news', $data[0]['slug']);
                    $this->assertEquals('Tech News', $data[1]['name']);
                    return true; // Must return true for the Mock to pass
                }),
                ['slug'], // Unique constraint columns
                ['name', 'description', 'url', 'category', 'language', 'country', 'fetched_at', 'updated_at'] // Updateable columns
            );

        // Execute the method
        $this->repository->fetchAndStoreNewsFromAPI();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
