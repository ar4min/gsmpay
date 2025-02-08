<?php

namespace Tests\Unit\Services;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PostService $postService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postService = new PostService();
        $this->user = User::factory()->create();
    }

    public function test_get_user_posts_returns_paginated_data()
    {

        Auth::shouldReceive('id')->andReturn($this->user->id);

        Post::factory()->count(5)->create(['user_id' => $this->user->id]);

        $posts = $this->postService->getUserPosts(3);

        $this->assertCount(3, $posts->items(), 'Expected 3 posts to be returned.');
    }


    public function test_storePost_creates_new_post()
    {
        $user = User::factory()->create();

        Auth::shouldReceive('id')->once()->andReturn($user->id);

        $post = $this->postService->storePost([
            'title' => 'Test Title',
            'body'  => 'Test Body',
        ]);

        $this->assertDatabaseHas('posts', [
            'id'      => $post->id,
            'user_id' => $user->id,
            'title'   => 'Test Title',
        ]);

    }

    public function test_getPostById_throws_model_not_found_if_invalid()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->postService->getPostById(9999);
    }

    public function test_incrementView_increments_views_if_not_cached()
    {
        $post = Post::factory()->create(['views_count' => 0]);
        $ip   = '127.0.0.1';

        $this->postService->incrementView($post, $ip);

        $this->assertEquals(1, $post->fresh()->views_count);
        $key = "post_view_{$post->id}_{$ip}";
        $this->assertTrue(Cache::has($key));

        $this->postService->incrementView($post, $ip);
        $this->assertEquals(1, $post->fresh()->views_count);
    }
}
