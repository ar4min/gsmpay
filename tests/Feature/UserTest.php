<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        return [
            'user' => $user,
            'Authorization' => "Bearer $token",
        ];
    }

    public function test_get_profile_with_valid_token()
    {
        $auth = $this->authenticate();

        $response = $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'phone'], 'server_time']);
    }

    public function test_user_can_upload_profile_image()
    {
        Storage::fake('public');
        $auth = $this->authenticate();

        $response = $this->withHeader('Authorization', $auth['Authorization'])
            ->postJson('/api/profile/image', [
                'profile_image' => UploadedFile::fake()->image('avatar.jpg')
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'profile_image'],
                'message',
                'server_time'
            ]);

        $path = $response->json('data.profile_image');
        Storage::disk('public')->assertExists($path);
    }

    public function test_get_users_by_views()
    {
        $auth = $this->authenticate();

        $user1 = User::factory()->hasPosts(3, ['views_count' => 5])->create();
        $user2 = User::factory()->hasPosts(2, ['views_count' => 10])->create();
        $user3 = User::factory()->hasPosts(1, ['views_count' => 20])->create();

        $response = $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson('/api/users-by-views');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data', 'server_time'
            ]);

        $response->assertJsonFragment(['id' => $user3->id]);
        $response->assertJsonFragment(['id' => $user2->id]);
        $response->assertJsonFragment(['id' => $user1->id]);
    }
}
