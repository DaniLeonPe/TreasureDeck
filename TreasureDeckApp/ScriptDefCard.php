<?php
$token = "eyJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJjYXJkdHJhZGVyLXByb2R1Y3Rpb24iLCJzdWIiOiJhcHA6MTUyNDgiLCJhdWQiOiJhcHA6MTUyNDgiLCJleHAiOjQ5MDI4NDE3MzIsImp0aSI6ImNkZTY0MGI2LTlhNmYtNDU2OS1iODhmLTViMDVkMmJiMWRmZiIsImlhdCI6MTc0NzE2ODEzMiwibmFtZSI6IkRhbmlETFAgQXBwIDIwMjUwNTEzMjAyNTAyIn0.uG5XjGW-p3hm2NXb4s_637tWbKPkuW355Ayw_5n5c43v6riMyv8O0tQpLUgY-J1OxL4XVWKee_NaCX6ceRrDz_zxvAMOW7anwUgNh-D7S_p_FbZlBV9DT3laPOAvFNiQQCjZAzDzMHHpDeHeAek8MMGkDlzlBZJxuCHa3OP9EDNr2cY7w6PevwjKa_sKp5GwhQH5ETXF0dZLZ2jhyuWanTfi5gjhqiPCU4KZI6-LcOgXrZ0etfa_lJPW5aYuKaeYxzxNdFmrMg6qhvjkmQiQ_0G4qMHS1pFcaBb3Itg5HO-ZwrM84mJj_43UdSibNu1g_5TkwYDU-9wl6vWK0B_vVA"; // Reemplázalo con tu token real
$headers = [
    "Authorization: Bearer $token",
    "Accept: application/json"
];


$expansionsUrl = 'https://api.cardtrader.com/api/v2/expansions';
$expansions = json_decode(httpGet($expansionsUrl, $headers), true);

$finalCards = [];
$expansionsList = [];

foreach ($expansions as $expansion) {
    if ($expansion['game_id'] == 15) {
        $expansionId = $expansion['id'];
        $expansionName = $expansion['name'];

        // Guardamos datos clave de la expansión
        $expansionsList[] = [
            'id' => $expansionId,
            'name' => $expansionName,
            'abbr' => $expansion['abbr'] ?? '',
            'release_date' => $expansion['release_date'] ?? null
        ];

        echo "Procesando expansión: $expansionName (ID: $expansionId)\n";

        $cardsUrl = "https://api.cardtrader.com/api/v2/blueprints/export?expansion_id=$expansionId";
        $cards = json_decode(httpGet($cardsUrl, $headers), true);
        if (!is_array($cards)) continue;

        foreach ($cards as $card) {
            if (isset($card['category_id']) && $card['category_id'] == 192) {
                $blueprintId = $card['id'];
                $marketplaceUrl = "https://api.cardtrader.com/api/v2/marketplace/products?blueprint_id=$blueprintId&language=en";
                $marketplaceData = json_decode(httpGet($marketplaceUrl, $headers), true);

                if (!isset($marketplaceData[$blueprintId])) continue;

                $versions = [];

                foreach ($marketplaceData[$blueprintId] as $product) {
                    if (!isset($product['price']['formatted'])) continue;

                    $priceString = $product['price']['formatted'];
                    $price = preg_replace('/[^\d.]/', '', $priceString);

                    if (!is_numeric($price)) continue;

                    $price = floatval($price);
                    $version = $product['version'] ?? 'Unknown';

                    if (!isset($versions[$version])) {
                        $versions[$version] = [];
                    }

                    $versions[$version][] = $price;
                }

                foreach ($versions as $versionName => $priceList) {
                    if (count($priceList) === 0) continue;

                    $minPrice = min($priceList);
                    $avgPrice = round(array_sum($priceList) / count($priceList), 2);

                    $finalCards[] = [
                        'card_name' => $card['name'],
                        'expansion_name' => $expansionName,
                        'expansion_id' => $expansionId,
                        'collector_number' => $card['fixed_properties']['collector_number'] ?? '',
                        'rarity' => $card['fixed_properties']['onepiece_rarity'] ?? '',
                        'min_price' => $minPrice,
                        'avg_price' => $avgPrice,
                        'version' => $versionName,
                        'image_url' => $card['image_url'] ?? ''
                    ];
                }
            }
        }
    }
}

// Guardar expansiones y cartas
file_put_contents('expansions.json', json_encode($expansionsList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
file_put_contents('filtered_cards_by_version.json', json_encode($finalCards, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "Se guardaron " . count($expansionsList) . " expansiones en expansions.json\n";
echo "Se guardaron " . count($finalCards) . " cartas en filtered_cards_by_version.json\n";

// --- Función auxiliar para GET con cURL ---
function httpGet($url, $headers) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo "Error cURL: " . curl_error($ch) . "\n";
    }
    curl_close($ch);
    return $result;
}
?>