<?php
$inputDir = "cards";  // Carpeta con los archivos por expansión
$outputFile = "filtered_cards.json";
$filteredCards = [];

foreach (glob("$inputDir/*.json") as $file) {
    $cards = json_decode(file_get_contents($file), true);

    if (!is_array($cards)) {
        echo "Archivo inválido: $file\n";
        continue;
    }

    foreach ($cards as $card) {
        if (isset($card['category_id']) && $card['category_id'] == 192) {
            $filteredCards[] = $card;
        }
    }
}

file_put_contents($outputFile, json_encode($filteredCards, JSON_PRETTY_PRINT));
echo "Se guardaron " . count($filteredCards) . " cartas con category_id 192 en $outputFile\n";
?>
