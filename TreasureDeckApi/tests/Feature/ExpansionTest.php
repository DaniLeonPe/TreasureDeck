<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\Expansion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpansionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_expansion_and_relationship_with_cards()
    {
        $expansion = Expansion::create([
            'name' => 'Test Expansion',
        ]);

        $card = Card::factory()->create([
            'expansion_id' => $expansion->id,
        ]);

        $this->assertDatabaseHas('expansions', [
            'id' => $expansion->id,
            'name' => 'Test Expansion',
        ]);

        $this->assertTrue($expansion->cards->contains($card));
        $this->assertInstanceOf(Card::class, $expansion->cards->first());
    }
}
