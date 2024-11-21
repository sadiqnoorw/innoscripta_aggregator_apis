<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

     /** @test */
    //  public function user_can_register()
    //  {
    //      $response = $this->postJson('/api/auth/register', [
    //          'name' => 'Test User',
    //          'email' => 'test@example.com',
    //          'password' => 'password',
    //          'password_confirmation' => 'password',
    //      ]);
 
    //      $response->assertStatus(201)->assertJsonStructure([
    //          'user' => ['id', 'name', 'email'],
    //      ]);
    //  }

    /**
     * Test user registration.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@sexample.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email', 'created_at']]);
        $this->assertDatabaseHas('users', ['email' => 'john@sexample.com']);
    }

    /**
     * Test user registration with invalid data.
     *
     * @return void
     */
    public function test_user_registration_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure(['errors' => ['name', 'email', 'password']]);
    }

    /**
     * Test user login.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'john@sexample.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'john@sexample.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    /**
     * Test user login with invalid credentials.
     *
     * @return void
     */
    public function test_user_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'john@sexample.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Invalid credentials']);
    }

    /**
     * Test user logout.
     *
     * @return void
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Logged out successfully']);
    }

    /**
     * Test password reset with invalid email.
     *
     * @return void
     */
    public function test_password_reset_fails_with_invalid_email()
    {
        $response = $this->postJson('/api/auth/reset-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure(['errors' => ['email']]);
    }
}