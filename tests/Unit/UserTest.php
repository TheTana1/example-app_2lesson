<?php


use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_get_list(): void
    {
        $response = User::query()->get(route('user.index'));
        $response->assertStatus(200);
    }
}
