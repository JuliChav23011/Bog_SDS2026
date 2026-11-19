@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('header-title', 'Editar Usuario')

@section('content')
    <div class="card" style="max-width: 680px;">
        <div class="card-header">
            <h2 class="card-title">Editando: {{ $usuario->name }}</h2>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline btn-sm">Volver al listado</a>
        </div>

        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nombre completo *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $usuario->name) }}"
                    required
                >
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="email">Correo electrónico *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $usuario->email) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="role">Rol del sistema *</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="user" {{ old('role', $usuario->role) === 'user' ? 'selected' : '' }}>Usuario estándar (user)</option>
                        <option value="admin" {{ old('role', $usuario->role) === 'admin' ? 'selected' : '' }}>Administrador (admin)</option>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="password">Nueva contraseña (dejar vacío para conservar)</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        minlength="8"
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar nueva contraseña</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        minlength="8"
                    >
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection