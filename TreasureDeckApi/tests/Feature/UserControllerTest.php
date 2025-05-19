<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_todos_los_usuarios()
    {
        DB::statement('PRAGMA foreign_keys=OFF;'); // Deshabilita FK

    User::truncate();

    DB::statement('PRAGMA foreign_keys=ON;');
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/users');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
public function puede_crear_un_usuario()
{
    $role = Role::factory()->create(); 

    $payload = [
        'name' => 'Juan Pérez',
        'email' => 'juan@example.com',
        'password' => 'secret123',
        'role_id' => $role->id,
    ];

    $response = $this->postJson('/api/v2/users', $payload);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'role_id' => $role->id,
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'juan@example.com',
        'role_id' => $role->id,
    ]);

    $user = User::where('email', 'juan@example.com')->first();
    $this->assertTrue(Hash::check('secret123', $user->password));
}


    /** @test */
    public function puede_mostrar_un_usuario_por_id()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/v2/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'email' => $user->email,
                'role_id' => $user->role_id, 
            ]);
    }

    /** @test */
    public function puede_actualizar_un_usuario()
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Nombre actualizado',
            'email' => 'nuevo@example.com',
            'password' => 'nuevaclave123',
            'role_id' =>  $user->role_id, 

        ];

        $response = $this->putJson("/api/v2/users/{$user->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Nombre actualizado',
                'email' => 'nuevo@example.com',
                'role_id' => $user->role_id,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nombre actualizado',
            'email' => 'nuevo@example.com',
            'role_id' => $user->role_id,
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check('nuevaclave123', $user->password));
    }

    /** @test */
    public function puede_eliminar_un_usuario()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/v2/users/{$user->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
