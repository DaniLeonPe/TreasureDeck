<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserCard;
use App\Models\CardsVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_card_creation_and_relations()
    {
        // Crear usuario y cardsVersion para las relaciones
        $user = User::factory()->create();
        $cardsVersion = CardsVersion::factory()->create();

        $userCard = UserCard::create([
            'user_id' => $user->id,
            'card_version_id' => $cardsVersion->id,
            'quantity' => 3,
        ]);

        $this->assertDatabaseHas('user_cards', [
            'user_id' => $user->id,
            'card_version_id' => $cardsVersion->id,
            'quantity' => 3,
        ]);

        // Verificar relaciones
        $this->assertInstanceOf(User::class, $userCard->user);
        $this->assertInstanceOf(CardsVersion::class, $userCard->cardsVersion);
    }
}
