@extends('layouts.admin')

@section('title', 'Usuarios y Roles')
@section('header-title', 'Gestión de Usuarios y Roles')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Usuarios registrados ({{ $usuarios->count() }})</h2>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-success">+ Nuevo usuario</a>
        </div>

        <form action="{{ route('admin.usuarios.index') }}" method="GET" class="filter-bar">
            <input
                type="text"
                name="buscar"
                class="form-control"
                style="max-width: 290px;"
                placeholder="Buscar por nombre o correo..."
                value="{{ request('buscar') }}"
            >

            <select name="role" class="form-control" style="max-width: 190px;">
                <option value="">Todos los roles</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administradores</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Usuarios estándar</option>
            </select>

            <button type="submit" class="btn btn-primary">Filtrar</button>

            @if (request()->hasAny(['buscar', 'role']))
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">Limpiar</a>
            @endif
        </form>

        @if ($usuarios->isEmpty())
            <p style="color: var(--texto-suave);">No se encontraron usuarios con los criterios seleccionados.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol actual</th>
                            <th>Actividad asociada</th>
                            <th>Registro</th>
                            <th style="width: 270px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr>
                                <td>#{{ $usuario->id }}</td>
                                <td>
                                    <strong>{{ $usuario->name }}</strong>
                                    @if ($usuario->id === auth()->id())
                                        <span class="badge badge-celeste" style="margin-left: 4px;">Tú</span>
                                    @endif
                                    <div style="font-size: 13px; color: var(--texto-suave);">{{ $usuario->email }}</div>
                                </td>
                                <td>
                                    @if ($usuario->role === 'admin')
                                        <span class="badge badge-verde">Administrador</span>
                                    @else
                                        <span class="badge badge-gris">Usuario</span>
                                    @endif
                                </td>
                                <td style="font-size: 12.5px;">
                                    <div>{{ $usuario->publicaciones_count }} {{ $usuario->publicaciones_count === 1 ? 'publicación' : 'publicaciones' }}</div>
                                    <div style="color: var(--texto-suave);">{{ $usuario->evidencias_count }} {{ $usuario->evidencias_count === 1 ? 'evidencia' : 'evidencias' }}</div>
                                </td>
                                <td style="font-size: 13px; color: var(--texto-suave);">
                                    {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : '—' }}
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                        <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-outline btn-sm">
                                            Editar
                                        </a>

                                        @if ($usuario->id !== auth()->id())
                                            <form action="{{ route('admin.usuarios.rol', $usuario) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-celeste btn-sm">
                                                    {{ $usuario->role === 'admin' ? 'Hacer user' : 'Hacer admin' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST"
                                                  onsubmit="return confirm('¿Estás segura de eliminar al usuario «{{ $usuario->name }}»?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection