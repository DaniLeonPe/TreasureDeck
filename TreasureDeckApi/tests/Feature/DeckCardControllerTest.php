<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\DecksCard;
use App\Models\CardsVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeckCardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_deck_cards()
    {
        DecksCard::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/deckCards');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_deck_card()
    {
        $deck = Deck::factory()->create();
        $cardVersion = CardsVersion::factory()->create();

        $payload = [
            'deck_id' => $deck->id,
            'card_version_id' => $cardVersion->id,
            'quantity' => 3,
        ];

        $response = $this->postJson('/api/v2/deckCards', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'deck_id' => $deck->id,
                     'card_version_id' => $cardVersion->id,
                     'quantity' => 3,
                 ]);

        $this->assertDatabaseHas('decks_cards', $payload);
    }

    public function test_it_shows_a_deck_card()
    {
        $deckCard = DecksCard::factory()->create();

        $response = $this->getJson("/api/v2/deckCards/{$deckCard->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $deckCard->id,
                 ]);
    }

    public function test_it_updates_a_deck_card()
    {
        $deckCard = DecksCard::factory()->create();

        $payload = [
            'quantity' => 2,
        ];

        $response = $this->putJson("/api/v2/deckCards/{$deckCard->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('decks_cards', array_merge(['id' => $deckCard->id], $payload));
    }

    public function test_it_deletes_a_deck_card()
    {
        $deckCard = DecksCard::factory()->create();

        $response = $this->deleteJson("/api/v2/deckCards/{$deckCard->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('decks_cards', ['id' => $deckCard->id]);
    }
}
