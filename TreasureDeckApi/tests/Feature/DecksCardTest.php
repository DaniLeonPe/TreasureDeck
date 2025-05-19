<?php

namespace Tests\Unit;

use App\Models\CardsVersion;
use App\Models\Deck;
use App\Models\DecksCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecksCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_decks_card()
    {
        // Creamos un deck y una cardsVersion con factories (debes tener factories definidas)
        $deck = Deck::factory()->create();
        $cardsVersion = CardsVersion::factory()->create();

        // Crear DecksCard
        $decksCard = DecksCard::create([
            'deck_id' => $deck->id,
            'card_version_id' => $cardsVersion->id,
            'quantity' => 2,
        ]);

        // Assert básico que se guardó
        $this->assertDatabaseHas('decks_cards', [
            'id' => $decksCard->id,
            'deck_id' => $deck->id,
            'card_version_id' => $cardsVersion->id,
            'quantity' => 2,
        ]);

        // Relaciones
        $this->assertInstanceOf(Deck::class, $decksCard->deck);
        $this->assertInstanceOf(CardsVersion::class, $decksCard->cardsVersion);
    }
}
