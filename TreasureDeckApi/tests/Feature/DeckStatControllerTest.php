<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\DecksStat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeckStatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_deck_stats()
    {
        DecksStat::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/deckStats');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_deck_stat()
    {
        $deck = Deck::factory()->create();

        $payload = [
            'deck_id' => $deck->id,
            'wins' => 10,
            'losses' => 5,
            'dice' => true
        ];

        $response = $this->postJson('/api/v2/deckStats', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('decks_stats', $payload);
    }

    public function test_it_shows_a_deck_stat()
    {
        $deckStat = DecksStat::factory()->create();

        $response = $this->getJson("/api/v2/deckStats/{$deckStat->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $deckStat->id,
                     'deck_id' => $deckStat->deck_id,
                 ]);
    }

    public function test_it_updates_a_deck_stat()
    {
        $deckStat = DecksStat::factory()->create();

        $payload = [
            'wins' => 12,
            'losses' => 6,
            'dice' => true
        ];

        $response = $this->putJson("/api/v2/deckStats/{$deckStat->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('decks_stats', array_merge(['id' => $deckStat->id], $payload));
    }

    public function test_it_deletes_a_deck_stat()
    {
        $deckStat = DecksStat::factory()->create();

        $response = $this->deleteJson("/api/v2/deckStats/{$deckStat->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('decks_stats', ['id' => $deckStat->id]);
    }
}
