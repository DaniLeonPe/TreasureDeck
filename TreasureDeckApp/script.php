<?php
// URL de la API
$apiUrl = "https://api.cardtrader.com/api/v2/expansions";

// Tu token de autenticación
$token = "eyJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJjYXJkdHJhZGVyLXByb2R1Y3Rpb24iLCJzdWIiOiJhcHA6MTUyNDgiLCJhdWQiOiJhcHA6MTUyNDgiLCJleHAiOjQ5MDI4NDE3MzIsImp0aSI6ImNkZTY0MGI2LTlhNmYtNDU2OS1iODhmLTViMDVkMmJiMWRmZiIsImlhdCI6MTc0NzE2ODEzMiwibmFtZSI6IkRhbmlETFAgQXBwIDIwMjUwNTEzMjAyNTAyIn0.uG5XjGW-p3hm2NXb4s_637tWbKPkuW355Ayw_5n5c43v6riMyv8O0tQpLUgY-J1OxL4XVWKee_NaCX6ceRrDz_zxvAMOW7anwUgNh-D7S_p_FbZlBV9DT3laPOAvFNiQQCjZAzDzMHHpDeHeAek8MMGkDlzlBZJxuCHa3OP9EDNr2cY7w6PevwjKa_sKp5GwhQH5ETXF0dZLZ2jhyuWanTfi5gjhqiPCU4KZI6-LcOgXrZ0etfa_lJPW5aYuKaeYxzxNdFmrMg6qhvjkmQiQ_0G4qMHS1pFcaBb3Itg5HO-ZwrM84mJj_43UdSibNu1g_5TkwYDU-9wl6vWK0B_vVA";  // Reemplázalo con tu token real

// Inicializar cURL
$ch = curl_init($apiUrl);

// Establecer los encabezados (incluyendo el token de autenticación)
$headers = [
    "Authorization: Bearer $token"  // Aquí se incluye el token en el encabezado
];

// Establecer opciones de cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  // Añadir los encabezados
$response = curl_exec($ch);
curl_close($ch);

// Verificar si la respuesta fue exitosa
if ($response === false) {
    die("Error al realizar la solicitud a la API");
}

// Convertir la respuesta JSON en un arreglo PHP
$expansions = json_decode($response, true);

// Verificar si hubo un error al decodificar el JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error al decodificar la respuesta JSON");
}

// Definir el game_id que quieres filtrar
$gameId = 15;  // Puedes cambiar este valor según el juego que desees

// Filtrar las expansiones por game_id
$filteredExpansions = array_filter($expansions, function($expansion) use ($gameId) {
    return isset($expansion['game_id']) && $expansion['game_id'] == $gameId;
});

// Guardar las expansiones filtradas en un archivo JSON
$file = 'expansions.json';  // Nombre del archivo donde se guardará el JSON

// Si el archivo ya existe, lo sobrescribe, si no, lo crea
file_put_contents($file, json_encode($filteredExpansions, JSON_PRETTY_PRINT));

echo "Las expansiones filtradas se han guardado en '$file'.";
?>