<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicaciones</title>
</head>
<body>
    <h1>Publicaciones de la Semana de Sistemas 2026</h1>

    <p>Bienvenido, {{ auth()->user()->name }}</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>
