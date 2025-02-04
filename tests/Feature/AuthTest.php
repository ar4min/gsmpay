<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_and_receive_jwt_token()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/api/login', [
            'phone' => $user->phone,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function authenticated_user_can_upload_profile_image()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/profile/image', [
                'profile_image' => UploadedFile::fake()->image('avatar.jpg')
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'profile_image']);

        Storage::disk('public')->assertExists($response->json('profile_image'));

    }
}
