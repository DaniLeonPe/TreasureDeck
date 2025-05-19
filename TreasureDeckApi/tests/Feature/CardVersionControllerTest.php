<?php

namespace Tests\Feature;

use App\Models\CardsVersion;
use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardVersionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_card_versions()
    {
        CardsVersion::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/cardsVersion');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_card_version()
{
    $card = Card::factory()->create();

    $payload = [
        'card_id' => $card->id,
        'image_url' => 'https://example.com/card.png',
        'min_price' => 1.5,
        'avg_price' => 2.3,
        'versions' => 'v1.0',  
    ];

    $response = $this->postJson('/api/v2/cardsVersion', $payload);

    $response->assertStatus(201)
             ->assertJsonFragment([
                 'card_id' => $card->id,
                 'image_url' => 'https://example.com/card.png',
                 'versions' => 'v1.0', 
             ]);

    $this->assertDatabaseHas('cards_versions', $payload);
}


    public function test_it_shows_a_card_version()
    {
        $cardVersion = CardsVersion::factory()->create();

        $response = $this->getJson("/api/v2/cardsVersion/{$cardVersion->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $cardVersion->id,
                 ]);
    }

    public function test_it_updates_a_card_version()
{
    $cardVersion = CardsVersion::factory()->create();

    $payload = [
        'image_url' => 'https://example.com/updated-card.png',
        'min_price' => 2.0,
        'avg_price' => 3.0,
        'versions' => 'v2.0',  
    ];

    $response = $this->putJson("/api/v2/cardsVersion/{$cardVersion->id}", $payload);

    $response->assertStatus(200)
             ->assertJsonFragment($payload);

    $this->assertDatabaseHas('cards_versions', array_merge(['id' => $cardVersion->id], $payload));
}


    public function test_it_deletes_a_card_version()
    {
        $cardVersion = CardsVersion::factory()->create();

        $response = $this->deleteJson("/api/v2/cardsVersion/{$cardVersion->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('cards_versions', ['id' => $cardVersion->id]);
    }
}
