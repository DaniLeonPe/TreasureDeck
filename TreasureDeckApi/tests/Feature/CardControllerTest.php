<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\Expansion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
        use RefreshDatabase;

    protected Expansion $expansion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->expansion = Expansion::factory()->create();
    }

    /** @test */
    public function it_lists_cards_with_pagination()
    {
        Card::factory()->count(20)->create(['expansion_id' => $this->expansion->id]);

        $response = $this->getJson('/api/v2/cards?per_page=10');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'meta', 'links']);
    }

    /** @test */
    public function it_creates_a_card()
    {
        $payload = [
            'name' => 'Black Lotus',
            'collector_number' => '001',
            'rarity' => 'Mythic',
            'expansion_id' => $this->expansion->id,
        ];

        $response = $this->postJson('/api/v2/cards', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Black Lotus']);
        $this->assertDatabaseHas('cards', ['name' => 'Black Lotus']);
    }

    /** @test */
    public function it_shows_a_card()
    {
        $card = Card::factory()->create(['expansion_id' => $this->expansion->id]);

        $response = $this->getJson("/api/v2/cards/{$card->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $card->id]);
    }

    /** @test */
    public function it_updates_a_card()
    {
        $card = Card::factory()->create(['name' => 'Old Name', 'expansion_id' => $this->expansion->id]);

        $response = $this->putJson("/api/v2/cards/{$card->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);
        $this->assertDatabaseHas('cards', ['id' => $card->id, 'name' => 'Updated Name']);
    }

    /** @test */
    public function it_deletes_a_card()
    {
        $card = Card::factory()->create(['expansion_id' => $this->expansion->id]);

        $response = $this->deleteJson("/api/v2/cards/{$card->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
    }
}
