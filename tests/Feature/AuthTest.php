<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'phone' => '09121110000',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['token'],
                'server_time'
            ]);
    }

    public function test_user_can_login_and_receive_token()
    {
        $user = User::factory()->create([
            'phone' => '09121110000',
            'password' => bcrypt('secret')
        ]);

        $response = $this->postJson('/api/login', [
            'phone' => '09121110000',
            'password' => 'secret'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['token'],
                'server_time'
            ]);
    }

    public function test_user_cannot_login_with_wrong_credentials()
    {
        $user = User::factory()->create([
            'phone' => '09121110000',
            'password' => bcrypt('secret')
        ]);

        $response = $this->postJson('/api/login', [
            'phone' => '09121110000',
            'password' => 'wrong-secret'
        ]);

        $response->assertStatus(401)
            ->assertJsonFragment(['error' => 'Unauthorized']);
    }
}
