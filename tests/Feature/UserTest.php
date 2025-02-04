<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_user_has_posts_relationship()
    {
        $user = User::factory()->hasPosts(3)->create();
        $this->assertCount(3, $user->posts);
    }
}

