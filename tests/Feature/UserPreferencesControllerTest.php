<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPreferencesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authentication
        $this->user = User::factory()->create();
    }

    public function test_set_preferences_successfully()
    {
        $data = [
            'preferred_sources' => ['source1', 'source2'],
            'preferred_categories' => ['category1', 'category2'],
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/auth/preferences', $data);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Preferences saved successfully.']);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $this->user->id,
            'preferred_sources' => json_encode($data['preferred_sources']),
        ]);
    }

    public function test_get_preferences_successfully()
    {
        $this->user->preferences()->create([
            'preferred_sources' => ['source1', 'source2'],
            'preferred_categories' => ['category1', 'category2'],
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/auth/preferences');

        $response->assertStatus(200)
                 ->assertJsonStructure(['preferred_sources', 'preferred_categories'])
                 ->assertJson([
                     'preferred_sources' => ['source1', 'source2'],
                     'preferred_categories' => ['category1', 'category2'],
                 ]);
    }

    public function test_unauthenticated_user_cannot_access_preferences()
    {
        $response = $this->getJson('/api/auth/preferences');
        $response->assertStatus(401); // Unauthorized
    }
}
