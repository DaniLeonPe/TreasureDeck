<?php

namespace Tests\Unit;

use App\Models\Deck;
use App\Models\DecksStat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecksStatTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_decks_stat()
    {
        $deck = Deck::factory()->create();

        $decksStat = DecksStat::create([
            'deck_id' => $deck->id,
            'wins' => 5,
            'losses' => 3,
            'dice' => 2,
        ]);

        $this->assertDatabaseHas('decks_stats', [
            'id' => $decksStat->id,
            'deck_id' => $deck->id,
            'wins' => 5,
            'losses' => 3,
            'dice' => 2,
        ]);

        // Relaciones
        $this->assertInstanceOf(Deck::class, $decksStat->deck);
    }
}
