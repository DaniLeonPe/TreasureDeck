<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Deck;
use App\Models\User;
use App\Models\CardsVersion;
use App\Models\DecksCard;
use App\Models\DecksStat;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeckTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_deck()
    {
        $user = User::factory()->create();
        $cardsVersion = CardsVersion::factory()->create();

        $deck = Deck::factory()->create([
            'user_id' => $user->id,
            'leader_card_version_id' => $cardsVersion->id,
            'name' => 'Mi Deck Test',
        ]);

        $this->assertInstanceOf(Deck::class, $deck);
        $this->assertEquals('Mi Deck Test', $deck->name);
        $this->assertEquals($user->id, $deck->user_id);
        $this->assertEquals($cardsVersion->id, $deck->leader_card_version_id);
    }

    public function test_relations()
    {
        $deck = Deck::factory()->create();

        // Relación user
        $this->assertInstanceOf(User::class, $deck->user);

        // Relación cardsVersion
        $this->assertInstanceOf(CardsVersion::class, $deck->cardsVersion);

        // Relación decksCards (hasMany)
        $deckCard = DecksCard::factory()->create(['deck_id' => $deck->id]);
        $this->assertTrue($deck->decksCards->contains($deckCard));

        // Relación decksStats (hasMany)
        $deckStat = DecksStat::factory()->create(['deck_id' => $deck->id]);
        $this->assertTrue($deck->decksStats->contains($deckStat));
    }
}
