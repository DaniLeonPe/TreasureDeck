<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Card;
use App\Models\CardsVersion;
use App\Models\Expansion;
use Illuminate\Support\Facades\Http;

class ImportCards extends Command
{
    protected $signature = 'cards:import';
    protected $description = 'Importar cartas y versiones desde CardTrader';

    public function handle()
    {
        $token = "eyJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJjYXJkdHJhZGVyLXByb2R1Y3Rpb24iLCJzdWIiOiJhcHA6MTUyNDgiLCJhdWQiOiJhcHA6MTUyNDgiLCJleHAiOjQ5MDI4NDE3MzIsImp0aSI6ImNkZTY0MGI2LTlhNmYtNDU2OS1iODhmLTViMDVkMmJiMWRmZiIsImlhdCI6MTc0NzE2ODEzMiwibmFtZSI6IkRhbmlETFAgQXBwIDIwMjUwNTEzMjAyNTAyIn0.uG5XjGW-p3hm2NXb4s_637tWbKPkuW355Ayw_5n5c43v6riMyv8O0tQpLUgY-J1OxL4XVWKee_NaCX6ceRrDz_zxvAMOW7anwUgNh-D7S_p_FbZlBV9DT3laPOAvFNiQQCjZAzDzMHHpDeHeAek8MMGkDlzlBZJxuCHa3OP9EDNr2cY7w6PevwjKa_sKp5GwhQH5ETXF0dZLZ2jhyuWanTfi5gjhqiPCU4KZI6-LcOgXrZ0etfa_lJPW5aYuKaeYxzxNdFmrMg6qhvjkmQiQ_0G4qMHS1pFcaBb3Itg5HO-ZwrM84mJj_43UdSibNu1g_5TkwYDU-9wl6vWK0B_vVA";
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
        ];

        $this->info("Descargando expansiones...");
        $expansions = Http::withHeaders($headers)->get('https://api.cardtrader.com/api/v2/expansions')->json();

        foreach ($expansions as $expansion) {
            if ($expansion['game_id'] != 15) continue; // Filtra solo juego 15
            
            $expansionId = $expansion['id'];
            $expansionName = $expansion['name'];

            $expansionModel = Expansion::updateOrCreate(
                ['id' => $expansionId],
                ['name' => $expansionName]
            );

            $this->info("Procesando expansión: $expansionName (ID: $expansionId)");

            // Aquí se obtienen las cartas para esa expansión
            $cards = Http::withHeaders($headers)
                ->get("https://api.cardtrader.com/api/v2/blueprints/export?expansion_id=$expansionId")
                ->json();

            if (!is_array($cards)) continue;

            foreach ($cards as $cardData) {
                if (($cardData['category_id'] ?? null) != 192) continue; // Filtra cartas con categoría 192

                $card = Card::updateOrCreate(
                    [
                        'name' => $cardData['name'],
                        'collector_number' => $cardData['fixed_properties']['collector_number'] ?? '',
                        'expansion_id' => $expansionId,
                    ],
                    [
                        'rarity' => $cardData['fixed_properties']['onepiece_rarity'] ?? '',
                    ]
                );

                $blueprintId = $cardData['id'];
                $market = Http::withHeaders($headers)
                    ->get("https://api.cardtrader.com/api/v2/marketplace/products?blueprint_id=$blueprintId&language=en")
                    ->json();

                if (!isset($market[$blueprintId])) continue;

                $versionsGrouped = [];

                foreach ($market[$blueprintId] as $product) {
                    $price = floatval(preg_replace('/[^\d.]/', '', $product['price']['formatted'] ?? ''));
                    if ($price <= 0) continue;

                    $version = $cardData['version'] ?? 'Unknown';
                    $versionsGrouped[$version][] = $price;
                }

                foreach ($versionsGrouped as $versionName => $prices) {
                    if (empty($prices)) continue;

                    sort($prices);
                    $topPrices = array_slice($prices, 0, 10);

                    $minPrice = min($topPrices);
                    $avgPrice = round(array_sum($topPrices) / count($topPrices), 2);

                    CardsVersion::updateOrCreate(
                        [
                            'card_id' => $card->id,
                            'versions' => $versionName,
                        ],
                        [
                            'image_url' => $cardData['image_url'] ?? '',
                            'min_price' => $minPrice,
                            'avg_price' => $avgPrice,
                        ]
                    );
                }
            }
        }

        $this->info("Importación completada.");
    }
}
