<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_role()
    {
        DB::statement('PRAGMA foreign_keys=OFF;'); // Deshabilita FK

    Role::truncate();

    DB::statement('PRAGMA foreign_keys=ON;');
        Role::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/role');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_role()
    {
        $payload = ['name' => 'Administrador'];

        $response = $this->postJson('/api/v2/role', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('role', $payload);
    }

    public function test_it_shows_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->getJson("/api/v2/role/{$role->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $role->id,
                     'name' => $role->name,
                 ]);
    }

    public function test_it_updates_a_role()
    {
        $role = Role::factory()->create();

        $payload = ['name' => 'Editor'];

        $response = $this->putJson("/api/v2/role/{$role->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('role', ['id' => $role->id, 'name' => 'Editor']);
    }

    public function test_it_deletes_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson("/api/v2/role/{$role->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('role', ['id' => $role->id]);
    }
}
