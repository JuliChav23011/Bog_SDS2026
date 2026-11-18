<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Semana de Sistemas</title>
    @vite('resources/css/style.css')
</head>
<body class="auth-body">
    <div class="auth-container">
        <a href="{{ route('home') }}" class="back-link">
            ← Volver al inicio
        </a>

        <div class="auth-card">
            <div class="auth-symbol">S</div>
            <h1>Bienvenido de nuevo</h1>
            <p>Ingresa para administrar el blog.</p>

            <form action="{{ route('login.procesar') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email"
                           placeholder="correo@ejemplo.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password"
                           placeholder="Tu contraseña" required>
                </div>

                <button type="submit" class="btn-primary btn-full">
                    Iniciar sesión
                </button>
            </form>

            <p class="auth-footer">
                ¿No tienes cuenta?
                <a href="{{ route('registro') }}">Regístrate aquí</a>
            </p>
        </div>
    </div>
</body>
</html>
