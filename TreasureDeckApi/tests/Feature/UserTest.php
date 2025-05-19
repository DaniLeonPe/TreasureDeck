<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_and_jwt_methods_work()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        // JWT Identifier
        $this->assertEquals($user->getKey(), $user->getJWTIdentifier());

        // JWT Custom Claims contains 'rol' and 'name'
        $claims = $user->getJWTCustomClaims();
        $this->assertArrayHasKey('rol', $claims);
        $this->assertEquals('user', $claims['rol']);
        $this->assertArrayHasKey('name', $claims);
        $this->assertEquals('Test User', $claims['name']);
    }
}
