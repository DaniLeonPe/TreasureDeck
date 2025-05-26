<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\User;
use App\Models\CardsVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeckControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear y autenticar usuario para los tests
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_it_lists_decks()
    {
        // Crear decks para usuario autenticado
        Deck::factory()->count(2)->create(['user_id' => $this->user->id]);

        // Crear deck para otro usuario
        Deck::factory()->create();

        $response = $this->getJson('/api/v2/decks');

        // Solo debe listar los decks del usuario autenticado (2)
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_it_creates_a_deck()
    {
        $cardVersion = CardsVersion::factory()->create();

        $payload = [
            'name' => 'Mazo Dragón',
        ];

        $response = $this->postJson('/api/v2/decks', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'user_id' => $this->user->id,
                     'name' => 'Mazo Dragón',
                 ]);

        $this->assertDatabaseHas('decks', array_merge($payload, ['user_id' => $this->user->id]));
    }

    public function test_it_shows_a_deck()
    {
        $deck = Deck::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v2/decks/{$deck->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $deck->id,
                     'name' => $deck->name,
                 ]);
    }

    public function test_it_updates_a_deck()
    {
        $deck = Deck::factory()->create(['user_id' => $this->user->id]);

        $payload = [
            'name' => 'Mazo actualizado',
        ];

        $response = $this->putJson("/api/v2/decks/{$deck->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('decks', array_merge(['id' => $deck->id], $payload));
    }

    public function test_it_deletes_a_deck()
    {
        $deck = Deck::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/v2/decks/{$deck->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('decks', ['id' => $deck->id]);
    }
}
