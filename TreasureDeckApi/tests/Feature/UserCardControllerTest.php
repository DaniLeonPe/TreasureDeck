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

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear y autenticar usuario para los tests
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_it_lists_user_cards()
    {
        // Crear user cards para el usuario autenticado y también para otro usuario
        UserCard::factory()->count(2)->create(['user_id' => $this->user->id]);
        UserCard::factory()->count(1)->create(); // otra user card para otro usuario

        $response = $this->getJson('/api/v2/userCards');

        // Solo debe listar las que pertenecen al usuario autenticado (2)
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_it_creates_a_user_card()
    {
        $cardVersion = CardsVersion::factory()->create();

        $payload = [
            'card_version_id' => $cardVersion->id,
            'quantity' => 5,
        ];

        $response = $this->postJson('/api/v2/userCards', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'user_id' => $this->user->id,
                     'card_version_id' => $cardVersion->id,
                     'quantity' => 5,
                 ]);

        $this->assertDatabaseHas('user_cards', array_merge($payload, ['user_id' => $this->user->id]));
    }

    public function test_it_shows_a_user_card()
    {
        $userCard = UserCard::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v2/userCards/{$userCard->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $userCard->id,
                     'quantity' => $userCard->quantity,
                 ]);
    }

    public function test_it_updates_a_user_card()
    {
        $userCard = UserCard::factory()->create(['user_id' => $this->user->id, 'quantity' => 2]);

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
        $userCard = UserCard::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/v2/userCards/{$userCard->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('user_cards', [
            'id' => $userCard->id,
        ]);
    }
}
