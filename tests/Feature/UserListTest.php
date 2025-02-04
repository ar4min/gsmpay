<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserListTest extends TestCase
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
    public function it_returns_users_sorted_by_total_post_views()
    {
        $auth = $this->authenticate();

        $user1 = User::factory()->hasPosts(3, ['views_count' => 5])->create();
        $user2 = User::factory()->hasPosts(2, ['views_count' => 10])->create();
        $user3 = User::factory()->hasPosts(1, ['views_count' => 20])->create();

        $response = $this->withHeader('Authorization', $auth['Authorization'])
            ->getJson('/api/users-by-views');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user3->id, 'total_views' => 20])
            ->assertJsonFragment(['id' => $user2->id, 'total_views' => 20])
            ->assertJsonFragment(['id' => $user1->id, 'total_views' => 15]);
    }
}

