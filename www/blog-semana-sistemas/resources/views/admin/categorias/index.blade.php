@extends('layouts.admin')

@section('title', 'Categorías')
@section('header-title', 'Administración de Categorías')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Categorías registradas ({{ $categorias->count() }})</h2>
            <a href="{{ route('admin.categorias.create') }}" class="btn btn-success">+ Nueva categoría</a>
        </div>

        @if ($categorias->isEmpty())
            <p style="color: var(--texto-suave);">No hay categorías registradas todavía. Crea la primera para comenzar a organizar las publicaciones.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Slug</th>
                            <th>Descripción</th>
                            <th>Publicaciones</th>
                            <th style="width: 190px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr>
                                <td>#{{ $categoria->id }}</td>
                                <td><strong>{{ $categoria->nombre }}</strong></td>
                                <td><code>{{ $categoria->slug }}</code></td>
                                <td>{{ $categoria->descripcion ?: 'Sin descripción' }}</td>
                                <td>
                                    <span class="badge {{ $categoria->publicaciones_count > 0 ? 'badge-celeste' : 'badge-gris' }}">
                                        {{ $categoria->publicaciones_count }} {{ $categoria->publicaciones_count === 1 ? 'publicación' : 'publicaciones' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                        <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-outline btn-sm">
                                            Editar
                                        </a>

                                        <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST"
                                              onsubmit="return confirm('¿Estás segura de eliminar la categoría «{{ $categoria->nombre }}»?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Eliminar
                                            </button>
                                        </form>
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