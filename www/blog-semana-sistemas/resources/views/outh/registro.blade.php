<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Semana de Sistemas</title>
    @vite('resources/css/style.css')
</head>
<body class="auth-body">
    <div class="auth-container">
        <a href="{{ route('home') }}" class="back-link">
            ← Volver al inicio
        </a>

        <div class="auth-card">
            <div class="auth-symbol">S</div>
            <h1>Crear una cuenta</h1>
            <p>Regístrate para formar parte del blog.</p>

            <form action="{{ route('registro.procesar') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre completo</label>
                    <input type="text" id="name" name="name"
                           placeholder="Tu nombre completo" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email"
                           placeholder="correo@ejemplo.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password"
                           placeholder="Mínimo 8 caracteres" minlength="8" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Repite tu contraseña" required>
                </div>

                <button type="submit" class="btn-primary btn-full">
                    Crear cuenta
                </button>
            </form>

            <p class="auth-footer">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}">Inicia sesión</a>
            </p>
        </div>
    </div>
</body>
</html>
