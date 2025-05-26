<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ImportCardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_cards_command_creates_some_expansions_and_cards()
    {
        Http::fake([
            'https://api.cardtrader.com/api/v2/expansions' => Http::response([
                ['id' => 1, 'name' => 'Expansion Test', 'game_id' => 15],
                ['id' => 2, 'name' => 'Otra Expansion', 'game_id' => 99],
            ], 200),

            'https://api.cardtrader.com/api/v2/blueprints/export?expansion_id=1' => Http::response([
                [
                    'id' => 101,
                    'name' => 'Carta Test',
                    'category_id' => 192,
                    'editable_properties' => [
                        [
                            'name' => 'onepiece_language',
                            'default_value' => 'en',
                        ],
                    ],
                    'fixed_properties' => [
                        'collector_number' => '123',
                        'onepiece_rarity' => 'Rare',
                    ],
                    'version' => '1st Edition',
                    'image_url' => 'http://example.com/image.jpg',
                ],
                [
                    'id' => 102,
                    'name' => 'Carta Ignorada',
                    'category_id' => 100,
                ],
            ], 200),

            'https://api.cardtrader.com/api/v2/marketplace/products?blueprint_id=101&language=en' => Http::response([
                101 => [
                    ['price' => ['formatted' => '$10.00']],
                    ['price' => ['formatted' => '$12.00']],
                    ['price' => ['formatted' => '$8.00']],
                ],
            ], 200),
        ]);

        $this->artisan('cards:import')
            ->expectsOutput('Descargando expansiones...')
            ->expectsOutput('Procesando expansión: Expansion Test (ID: 1)')
            ->expectsOutput('Importación completada.')
            ->assertExitCode(0);

        $countExpansions = DB::table('expansions')->count();
        $this->assertGreaterThan(0, $countExpansions, 'No se creó ninguna expansión');

        $countCards = DB::table('cards')->count();
        $this->assertGreaterThan(0, $countCards, 'No se creó ninguna carta');
    }
}
