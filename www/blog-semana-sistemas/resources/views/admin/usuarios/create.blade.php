@extends('layouts.admin')

@section('title', 'Nuevo Usuario')
@section('header-title', 'Registrar Usuario')

@section('content')
    <div class="card" style="max-width: 680px;">
        <div class="card-header">
            <h2 class="card-title">Datos de la nueva cuenta</h2>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline btn-sm">Volver al listado</a>
        </div>

        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nombre completo *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Ej. Carlos Mendoza"
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
                        value="{{ old('email') }}"
                        placeholder="usuario@ejemplo.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="role">Rol del sistema *</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>Usuario estándar (user)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador (admin)</option>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="password">Contraseña (mín. 8 caracteres) *</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        minlength="8"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña *</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        minlength="8"
                        required
                    >
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Crear usuario</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection