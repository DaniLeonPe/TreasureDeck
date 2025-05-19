<!DOCTYPE html>
<html>
<head>
    <title>Verifica tu cuenta</title>
</head>
<body>
    <h1>Hola, {{ $user->name }}</h1>
    <p>Por favor, verifica tu correo haciendo clic en el siguiente enlace:</p>
    <a href="{{ $verificationUrl }}">Verificar correo</a>
</body>
</html>
