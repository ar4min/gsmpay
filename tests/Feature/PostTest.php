<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
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

    public function test_authenticated_user_can_list_his_posts()
    {
        $auth = $this->authenticate();

        Post::factory(3)->create([
            'user_id' => $auth['user']->id
        ]);

        $response = $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'server_time'])
            ->assertJsonCount(3, 'data');
    }

    public function test_authenticated_user_can_create_post()
    {
        $auth = $this->authenticate();

        $response = $this->withHeader('Authorization', $auth['Authorization'])
            ->postJson('/api/posts', [
                'title' => 'New Post',
                'body'  => 'Post Body'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'title', 'body', 'views_count'], 'server_time']);
    }

    public function test_views_count_should_increase_only_once_per_ip()
    {
        $auth = $this->authenticate();

        $post = Post::factory()->create(['user_id' => $auth['user']->id]);
        $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson("/api/posts/{$post->id}")
            ->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'id'          => $post->id,
            'views_count' => 1
        ]);

        $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson("/api/posts/{$post->id}")
            ->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'id'          => $post->id,
            'views_count' => 1
        ]);
    }
}
