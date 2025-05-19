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

    public function test_it_lists_decks()
    {
        Deck::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/decks');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_deck()
    {
        $user = User::factory()->create();
        $cardVersion = CardsVersion::factory()->create();

        $payload = [
            'user_id' => $user->id,
            'leader_card_version_id' => $cardVersion->id,
            'name' => 'Mazo Dragón',
        ];

        $response = $this->postJson('/api/v2/decks', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'user_id' => $user->id,
                     'leader_card_version_id' => $cardVersion->id,
                     'name' => 'Mazo Dragón',
                 ]);

        $this->assertDatabaseHas('decks', $payload);
    }

    public function test_it_shows_a_deck()
    {
        $deck = Deck::factory()->create();

        $response = $this->getJson("/api/v2/decks/{$deck->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $deck->id,
                     'name' => $deck->name,
                 ]);
    }

    public function test_it_updates_a_deck()
    {
        $deck = Deck::factory()->create();

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
        $deck = Deck::factory()->create();

        $response = $this->deleteJson("/api/v2/decks/{$deck->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('decks', ['id' => $deck->id]);
    }
}
