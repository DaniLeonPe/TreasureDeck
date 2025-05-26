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

        $cardsInserted = 0;
        $cardsVersionInserted = 0;

        $this->info("Descargando expansiones...");
        $expansions = Http::withHeaders($headers)->get('https://api.cardtrader.com/api/v2/expansions')->json();

        function expansionPriority($code) {
            if (str_starts_with($code, 'op-') || str_starts_with($code, 'OP-')) return 1;
            if (str_starts_with($code, 'st-') || str_starts_with($code, 'ST-')) return 2;
            return 3;
        }

        usort($expansions, function($a, $b) {
            $aCode = strtolower($a['code'] ?? '');
            $bCode = strtolower($b['code'] ?? '');

            $aPriority = expansionPriority($aCode);
            $bPriority = expansionPriority($bCode);

            if ($aPriority === $bPriority) {
                return strcmp($aCode, $bCode);
            }

            return $aPriority - $bPriority;
        });

        foreach ($expansions as $expansion) {
            if (($expansion['game_id'] ?? null) != 15) continue;

            $expansionId = $expansion['id'];
            $expansionName = htmlspecialchars($expansion['name'] ?? '', ENT_QUOTES, 'UTF-8');

            Expansion::updateOrCreate(
                ['id' => $expansionId],
                ['name' => $expansionName]
            );

            $this->info("Procesando expansión: $expansionName (ID: $expansionId)");

            $cards = Http::withHeaders($headers)
                ->get("https://api.cardtrader.com/api/v2/blueprints/export?expansion_id=$expansionId")
                ->json();

            if (!is_array($cards)) continue;

            foreach ($cards as $cardData) {
                if (($cardData['category_id'] ?? null) != 192) continue;

                $editableProps = $cardData['editable_properties'] ?? [];
                $hasOnePieceEnglish = false;
                foreach ($editableProps as $prop) {
                    if (
                        isset($prop['name'], $prop['default_value']) &&
                        $prop['name'] === 'onepiece_language' &&
                        $prop['default_value'] === 'en'
                    ) {
                        $hasOnePieceEnglish = true;
                        break;
                    }
                }
                if (!$hasOnePieceEnglish) continue;

                $cardName = htmlspecialchars($cardData['name'] ?? '', ENT_QUOTES, 'UTF-8');
                $collectorNumber = htmlspecialchars($cardData['fixed_properties']['collector_number'] ?? '', ENT_QUOTES, 'UTF-8');
                $rarity = htmlspecialchars($cardData['fixed_properties']['onepiece_rarity'] ?? '', ENT_QUOTES, 'UTF-8');
                $imageUrl = htmlspecialchars($cardData['image_url'] ?? '', ENT_QUOTES, 'UTF-8');

                $card = Card::updateOrCreate(
                    [
                        'name' => $cardName,
                        'collector_number' => $collectorNumber,
                        'expansion_id' => $expansionId,
                    ],
                    [
                        'rarity' => $rarity,
                    ]
                );

                $cardsInserted++;

                $blueprintId = $cardData['id'];

                $market = Http::withHeaders($headers)
                    ->get("https://api.cardtrader.com/api/v2/marketplace/products?blueprint_id=$blueprintId&language=en")
                    ->json();

                if (!isset($market[$blueprintId])) continue;

                $versionsGrouped = [];

                foreach ($market[$blueprintId] as $product) {
                    // Quitamos filtro de idioma
                    // if (($product['properties_hash']['onepiece_language'] ?? '') !== 'en') continue;

                    $price = floatval(preg_replace('/[^\d.]/', '', $product['price']['formatted'] ?? '0'));
                    // Quitamos filtro de precio
                    // if ($price <= 0) continue;

                    $version = isset($product['properties_hash']['onepiece_rarity'])
                        ? htmlspecialchars($product['properties_hash']['onepiece_rarity'], ENT_QUOTES, 'UTF-8')
                        : null;

                    $versionsGrouped[$version][] = $price;
                }

                foreach ($versionsGrouped as $versionName => $prices) {
                    if (empty($prices)) continue;

                    sort($prices);
                    $topPrices = array_slice($prices, 0, 10);

                    $minPrice = min($topPrices);
                    $avgPrice = round(array_sum($topPrices) / count($topPrices), 2);

                    if (is_null($versionName)) {
                        $cardsVersion = CardsVersion::where('card_id', $card->id)
                            ->whereNull('versions')
                            ->first();

                        if ($cardsVersion) {
                            $cardsVersion->update([
                                'image_url' => $imageUrl,
                                'min_price' => $minPrice,
                                'avg_price' => $avgPrice,
                            ]);
                        } else {
                            CardsVersion::create([
                                'card_id' => $card->id,
                                'versions' => null,
                                'image_url' => $imageUrl,
                                'min_price' => $minPrice,
                                'avg_price' => $avgPrice,
                            ]);
                        }
                    } else {
                        CardsVersion::updateOrCreate(
                            [
                                'card_id' => $card->id,
                                'versions' => $versionName,
                            ],
                            [
                                'image_url' => $imageUrl,
                                'min_price' => $minPrice,
                                'avg_price' => $avgPrice,
                            ]
                        );
                    }

                    $cardsVersionInserted++;
                }
            }
        }

        $this->info("Importación completada.");
        $this->info("Cartas insertadas o actualizadas: $cardsInserted");
        $this->info("Versiones de cartas insertadas o actualizadas: $cardsVersionInserted");
    }
}
