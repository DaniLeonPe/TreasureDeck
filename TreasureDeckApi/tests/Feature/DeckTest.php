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
            'name' => 'Mi Deck Test',
        ]);

        $this->assertInstanceOf(Deck::class, $deck);
        $this->assertEquals('Mi Deck Test', $deck->name);
        $this->assertEquals($user->id, $deck->user_id);
    }

    public function test_relations()
{
    $deck = Deck::factory()->create();

    $this->assertInstanceOf(User::class, $deck->user);

    
    $deckCard = DecksCard::factory()->create(['deck_id' => $deck->id]);
    $this->assertTrue($deck->decksCards->contains($deckCard));
}

}
