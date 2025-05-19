<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Expansion;
use App\Models\Card;
use App\Models\CardsVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class ImportCardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_cards_command_creates_expansions_cards_and_versions()
    {
        // Simula la respuesta para la lista de expansiones
        Http::fake([
            'https://api.cardtrader.com/api/v2/expansions' => Http::response([
                [
                    'id' => 1,
                    'name' => 'Expansion Test',
                    'game_id' => 15, // el que acepta tu filtro
                ],
                [
                    'id' => 2,
                    'name' => 'Otra Expansion',
                    'game_id' => 99, // este se ignora
                ],
            ], 200),

            // Simula la respuesta de cartas para la expansión 1
            'https://api.cardtrader.com/api/v2/blueprints/export?expansion_id=1' => Http::response([
                [
                    'id' => 101,
                    'name' => 'Carta Test',
                    'category_id' => 192, // aceptado por el filtro
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
                    'category_id' => 100, // se ignora
                ],
            ], 200),

            // Simula respuesta del marketplace para blueprint_id 101
            'https://api.cardtrader.com/api/v2/marketplace/products?blueprint_id=101&language=en' => Http::response([
                101 => [
                    ['price' => ['formatted' => '$10.00']],
                    ['price' => ['formatted' => '$12.00']],
                    ['price' => ['formatted' => '$8.00']],
                ],
            ], 200),
        ]);

        // Ejecuta el comando
        $this->artisan('cards:import')
             ->expectsOutput('Descargando expansiones...')
             ->expectsOutput('Procesando expansión: Expansion Test (ID: 1)')
             ->expectsOutput('Importación completada.')
             ->assertExitCode(0);

        // Verifica que la expansión fue creada
        $this->assertDatabaseHas('expansions', [
            'id' => 1,
            'name' => 'Expansion Test',
        ]);

        // Verifica que la carta fue creada
        $this->assertDatabaseHas('cards', [
            'name' => 'Carta Test',
            'collector_number' => '123',
            'expansion_id' => 1,
            'rarity' => 'Rare',
        ]);

        // Verifica que la versión de carta fue creada con los precios calculados correctamente
        $this->assertDatabaseHas('cards_versions', [
            'card_id' => Card::where('name', 'Carta Test')->first()->id,
            'versions' => '1st Edition',
            'image_url' => 'http://example.com/image.jpg',
            'min_price' => 8.00,
            'avg_price' => 10.00, // promedio de 8, 10 y 12
        ]);
    }
}
