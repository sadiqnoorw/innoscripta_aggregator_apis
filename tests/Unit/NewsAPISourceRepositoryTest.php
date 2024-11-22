<?php

namespace Tests\Unit\Repositories;

use App\Models\NewsSource;
use App\Repositories\NewsAPISourceRepository;
use jcobhams\NewsApi\NewsApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class NewsAPISourceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;
    protected $newsSourceMock;
    protected $newsApiMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mocks for dependencies
        $this->newsSourceMock = Mockery::mock(NewsSource::class);
        $this->newsApiMock = Mockery::mock(NewsApi::class);

        // Instantiate the repository with mocks
        $this->repository = new NewsAPISourceRepository(
            $this->newsSourceMock,
            $this->newsApiMock
        );
    }

    public function testFetchAndStoreNewsFromAPI()
    {
        // Mock response from NewsApi's `getSources` method
        $apiResponse = (object)[
            'sources' => [
                (object)[
                    'id' => 'abc-news',
                    'name' => 'ABC News',
                    'description' => 'Your trusted source for breaking news.',
                    'url' => 'https://abcnews.go.com',
                    'category' => 'general',
                    'language' => 'en',
                    'country' => 'us',
                ],
                (object)[
                    'id' => 'cnn',
                    'name' => 'CNN',
                    'description' => 'Breaking news and analysis from CNN.',
                    'url' => 'https://cnn.com',
                    'category' => 'general',
                    'language' => 'en',
                    'country' => 'us',
                ],
            ]
        ];

        // Mock the NewsApi's `getSources` method
        $this->newsApiMock
            ->shouldReceive('getSources')
            ->with('general', 'en', 'us')
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

                    // Check the first record
                    $this->assertEquals('abc-news', $data[0]['slug']);
                    $this->assertEquals('ABC News', $data[0]['name']);
                    $this->assertEquals('Your trusted source for breaking news.', $data[0]['description']);
                    $this->assertEquals('https://abcnews.go.com', $data[0]['url']);
                    $this->assertEquals('general', $data[0]['category']);
                    $this->assertEquals('en', $data[0]['language']);
                    $this->assertEquals('us', $data[0]['country']);

                    // Check the second record
                    $this->assertEquals('cnn', $data[1]['slug']);
                    $this->assertEquals('CNN', $data[1]['name']);

                    return true; // Must return true for Mockery to pass
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
