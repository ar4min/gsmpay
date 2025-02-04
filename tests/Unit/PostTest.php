<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_can_be_created()
    {
        $post = Post::factory()->create();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_post_belongs_to_a_user()
    {
        $post = Post::factory()->create();
        $this->assertInstanceOf(User::class, $post->user);
    }
}
