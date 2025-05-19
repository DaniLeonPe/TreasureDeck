<?php

namespace Tests\Feature;

use App\Models\Expansion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpansionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_expansions()
    {
        Expansion::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/expansions');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_an_expansion()
    {
        $payload = [
            'name' => 'Nueva Expansión',
        ];

        $response = $this->postJson('/api/v2/expansions', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('expansions', $payload);
    }

    public function test_it_shows_an_expansion()
    {
        $expansion = Expansion::factory()->create();

        $response = $this->getJson("/api/v2/expansions/{$expansion->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $expansion->id,
                     'name' => $expansion->name,
                 ]);
    }

    public function test_it_updates_an_expansion()
    {
        $expansion = Expansion::factory()->create();

        $payload = [
            'name' => 'Nombre Actualizado',
        ];

        $response = $this->putJson("/api/v2/expansions/{$expansion->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('expansions', array_merge(['id' => $expansion->id], $payload));
    }

    public function test_it_deletes_an_expansion()
    {
        $expansion = Expansion::factory()->create();

        $response = $this->deleteJson("/api/v2/expansions/{$expansion->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('expansions', ['id' => $expansion->id]);
    }
}
