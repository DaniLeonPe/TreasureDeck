<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CardsVersion;
use App\Models\Card;
use App\Models\Deck;
use App\Models\DecksCard;
use App\Models\UserCard;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardsVersionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cards_version_relationships()
    {
        // Crear Card
        /** @var Card $card */
        $card = Card::factory()->create();

        // Crear CardsVersion relacionado con Card
        /** @var CardsVersion $cardsVersion */
        $cardsVersion = CardsVersion::factory()->create([
            'card_id' => $card->id,
        ]);

        // Crear Decks relacionados (hasMany decks)
        $deck = Deck::factory()->create([
            'leader_card_version_id' => $cardsVersion->id,
        ]);

        // Crear DecksCard relacionados (hasMany decksCards)
        $decksCard = DecksCard::factory()->create([
            'card_version_id' => $cardsVersion->id,
        ]);

        // Crear UserCard relacionados (hasMany userCards)
        $userCard = UserCard::factory()->create([
            'card_version_id' => $cardsVersion->id,
        ]);

        // Recargar relaciones para asegurar consistencia
        $cardsVersion->load('card', 'decks', 'decksCards', 'userCards');

        // Assert relaciones

        // belongsTo Card
        $this->assertInstanceOf(Card::class, $cardsVersion->card);
        $this->assertEquals($card->id, $cardsVersion->card->id);

        // hasMany Decks
        $this->assertCount(1, $cardsVersion->decks);
        $this->assertTrue($cardsVersion->decks->contains($deck));

        // hasMany DecksCards
        $this->assertCount(1, $cardsVersion->decksCards);
        $this->assertTrue($cardsVersion->decksCards->contains($decksCard));

        // hasMany UserCards
        $this->assertCount(1, $cardsVersion->userCards);
        $this->assertTrue($cardsVersion->userCards->contains($userCard));
    }
}
