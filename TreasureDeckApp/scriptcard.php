<?php
$token = "eyJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJjYXJkdHJhZGVyLXByb2R1Y3Rpb24iLCJzdWIiOiJhcHA6MTUyNDgiLCJhdWQiOiJhcHA6MTUyNDgiLCJleHAiOjQ5MDI4NDE3MzIsImp0aSI6ImNkZTY0MGI2LTlhNmYtNDU2OS1iODhmLTViMDVkMmJiMWRmZiIsImlhdCI6MTc0NzE2ODEzMiwibmFtZSI6IkRhbmlETFAgQXBwIDIwMjUwNTEzMjAyNTAyIn0.uG5XjGW-p3hm2NXb4s_637tWbKPkuW355Ayw_5n5c43v6riMyv8O0tQpLUgY-J1OxL4XVWKee_NaCX6ceRrDz_zxvAMOW7anwUgNh-D7S_p_FbZlBV9DT3laPOAvFNiQQCjZAzDzMHHpDeHeAek8MMGkDlzlBZJxuCHa3OP9EDNr2cY7w6PevwjKa_sKp5GwhQH5ETXF0dZLZ2jhyuWanTfi5gjhqiPCU4KZI6-LcOgXrZ0etfa_lJPW5aYuKaeYxzxNdFmrMg6qhvjkmQiQ_0G4qMHS1pFcaBb3Itg5HO-ZwrM84mJj_43UdSibNu1g_5TkwYDU-9wl6vWK0B_vVA";  // Reemplázalo con tu token real
$expansionsFile = "expansions.json";
$outputDir = "cards";  // Carpeta donde se guardarán los JSON de cartas

// Crear carpeta si no existe
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// Cargar expansiones desde archivo
$expansions = json_decode(file_get_contents($expansionsFile), true);

// Verificar si se cargaron correctamente
if (!is_array($expansions)) {
    die("No se pudo cargar el archivo de expansiones o está vacío.");
}

foreach ($expansions as $expansion) {
    $expansionId = $expansion["id"];
    $expansionName = preg_replace('/[^a-z0-9_\-]/i', '_', $expansion["name"]); // Limpieza para nombre de archivo

    $url = "https://api.cardtrader.com/api/v2/blueprints/export?expansion_id=$expansionId";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "Error al obtener cartas para expansión ID $expansionId\n";
        continue;
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Respuesta inválida para expansión ID $expansionId\n";
        continue;
    }

    $fileName = "$outputDir/{$expansionId}_$expansionName.json";
    file_put_contents($fileName, json_encode($data, JSON_PRETTY_PRINT));
    echo "Cartas de '{$expansion["name"]}' guardadas en $fileName\n";
}
?>
