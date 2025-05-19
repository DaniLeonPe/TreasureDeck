<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_role_and_relationship_with_users()
    {
        $role = Role::create([
            'name' => 'Admin',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->assertDatabaseHas('role', [
            'id' => $role->id,
            'name' => 'Admin',
        ]);

        $this->assertTrue($role->users->contains($user));
        $this->assertInstanceOf(User::class, $role->users->first());
    }
}
