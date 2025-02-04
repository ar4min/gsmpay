<?php

namespace Tests\Unit;

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
            'Authorization' => "Bearer $token",
            'user' => $user
        ];
    }

    /** @test */
    public function authenticated_user_can_create_a_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'body' => 'This is a test post body.',
        ]);


        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'title', 'body', 'views_count']);
    }

    /** @test */
    public function authenticated_user_can_get_their_posts()
    {
        $auth = $this->authenticate();
        Post::factory(3)->create(['user_id' => $auth['user']->id]);

        $response = $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function views_count_should_increase_when_post_is_viewed()
    {
        $auth = $this->authenticate();
        $post = Post::factory()->create(['user_id' => $auth['user']->id]);

        $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson("/api/posts/{$post->id}");

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'views_count' => 1
        ]);

        $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson("/api/posts/{$post->id}");

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'views_count' => 1
        ]);
    }
}

