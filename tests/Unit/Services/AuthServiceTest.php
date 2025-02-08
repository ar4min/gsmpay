<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    public function test_register_user_creates_a_user_and_returns_token()
    {
        $data = [
            'name' => 'Test User',
            'phone' => '09121110000',
            'password' => 'secret'
        ];

        $token = $this->authService->registerUser($data);

        $this->assertIsString($token);
        $this->assertDatabaseHas('users', ['phone' => '09121110000']);
    }

    public function test_login_user_returns_token_or_null()
    {
        $user = User::factory()->create([
            'phone'    => '09121110000',
            'password' => bcrypt('secret')
        ]);

        $token = $this->authService->loginUser([
            'phone'    => '09121110000',
            'password' => 'secret'
        ]);

        $this->assertIsString($token);

        $wrongToken = $this->authService->loginUser([
            'phone'    => '09121110000',
            'password' => 'wrong'
        ]);

        $this->assertNull($wrongToken);
    }
}
