<?php

namespace Tests\Feature;

use App\Models\CardsVersion;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_user_cards()
    {
        UserCard::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/userCards');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_user_card()
    {
        $user = User::factory()->create();
        $cardVersion = CardsVersion::factory()->create();

        $payload = [
            'user_id' => $user->id,
            'card_version_id' => $cardVersion->id,
            'quantity' => 5,
        ];

        $response = $this->postJson('/api/v2/userCards', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'user_id' => $user->id,
                     'card_version_id' => $cardVersion->id,
                     'quantity' => 5,
                 ]);

        $this->assertDatabaseHas('user_cards', $payload);
    }

    public function test_it_shows_a_user_card()
    {
        $userCard = UserCard::factory()->create();

        $response = $this->getJson("/api/v2/userCards/{$userCard->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $userCard->id,
                     'quantity' => $userCard->quantity,
                 ]);
    }

    public function test_it_updates_a_user_card()
    {
        $userCard = UserCard::factory()->create(['quantity' => 2]);

        $response = $this->putJson("/api/v2/userCards/{$userCard->id}", [
            'quantity' => 7,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['quantity' => 7]);

        $this->assertDatabaseHas('user_cards', [
            'id' => $userCard->id,
            'quantity' => 7,
        ]);
    }

    public function test_it_deletes_a_user_card()
    {
        $userCard = UserCard::factory()->create();

        $response = $this->deleteJson("/api/v2/userCards/{$userCard->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('user_cards', [
            'id' => $userCard->id,
        ]);
    }
}
