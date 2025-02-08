<?php


namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function test_getUsersSortedByPostViews()
    {
        $user1 = User::factory()->hasPosts(3, ['views_count' => 5])->create();
        $user2 = User::factory()->hasPosts(2, ['views_count' => 10])->create();
        $user3 = User::factory()->hasPosts(1, ['views_count' => 30])->create();

        $result = $this->userService->getUsersSortedByPostViews();

        $this->assertEquals($user3->id, $result->first()->id);
        $this->assertEquals($user1->id, $result->last()->id);
    }
}
