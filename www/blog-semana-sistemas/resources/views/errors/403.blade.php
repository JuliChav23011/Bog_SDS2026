<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado | Semana de Sistemas 2026</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="error-page">
    <div class="error-card">
        <div class="error-code">403</div>
        <h1>Acceso Denegado</h1>
        <p>{{ $exception->getMessage() ?: 'No tienes permisos de administrador para acceder a esta sección.' }}</p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn btn-primary">Volver al inicio</a>
            @auth
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">Cerrar sesión</button>
                </form>
            @endauth
        </div>
    </div>
</body>
</html>