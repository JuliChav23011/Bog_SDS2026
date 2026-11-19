@extends('layouts.admin')

@section('title', 'Publicaciones')
@section('header-title', 'Administración de Publicaciones')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Publicaciones registradas ({{ $publicaciones->count() }})</h2>
            <a href="{{ route('admin.publicaciones.create') }}" class="btn btn-success">+ Nueva publicación</a>
        </div>

        <form action="{{ route('admin.publicaciones.index') }}" method="GET" class="filter-bar">
            <input
                type="text"
                name="buscar"
                class="form-control"
                style="max-width: 280px;"
                placeholder="Buscar por título..."
                value="{{ request('buscar') }}"
            >

            <select name="categoria_id" class="form-control" style="max-width: 220px;">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                @endforeach
            </select>

            <select name="estado" class="form-control" style="max-width: 190px;">
                <option value="">Todos los estados</option>
                <option value="publicado" {{ request('estado') === 'publicado' ? 'selected' : '' }}>Publicado</option>
                <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="borrador" {{ request('estado') === 'borrador' ? 'selected' : '' }}>Borrador</option>
            </select>

            <button type="submit" class="btn btn-primary">Filtrar</button>

            @if (request()->hasAny(['buscar', 'categoria_id', 'estado']))
                <a href="{{ route('admin.publicaciones.index') }}" class="btn btn-outline">Limpiar</a>
            @endif
        </form>

        @if ($publicaciones->isEmpty())
            <p style="color: var(--texto-suave);">No se encontraron publicaciones con los criterios seleccionados.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Portada</th>
                            <th>Título y descripción</th>
                            <th>Categoría</th>
                            <th>Autor(es)</th>
                            <th>Fechas</th>
                            <th>Estado</th>
                            <th style="width: 180px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($publicaciones as $pub)
                            <tr>
                                <td>
                                    @if ($pub->imagenes->isNotEmpty())
                                        <img src="{{ asset('storage/' . $pub->imagenes->first()->ruta) }}" alt="Portada" class="img-thumb">
                                        <div style="font-size: 11px; color: var(--texto-suave); margin-top: 3px;">
                                            {{ $pub->imagenes->count() }} {{ $pub->imagenes->count() === 1 ? 'imagen' : 'imágenes' }}
                                        </div>
                                    @else
                                        <span class="badge badge-gris">Sin imagen</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $pub->titulo }}</strong>
                                    <div style="font-size: 12.5px; color: var(--texto-suave); margin-top: 4px; max-width: 300px;">
                                        {{ \Illuminate\Support\Str::limit($pub->resumen ?: $pub->contenido, 85) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-celeste">{{ $pub->categoria->nombre ?? 'Sin categoría' }}</span>
                                </td>
                                <td>
                                    @forelse ($pub->autores as $autor)
                                        <div style="font-size: 13px;">{{ $autor->name }}</div>
                                    @empty
                                        <span style="color: var(--texto-suave); font-size: 12px;">Sin autor</span>
                                    @endforelse
                                </td>
                                <td style="font-size: 12.5px;">
                                    <div><strong>Actividad:</strong> {{ $pub->fecha_actividad->format('d/m/Y') }}</div>
                                    <div style="color: var(--texto-suave);">
                                        <strong>Publicada:</strong>
                                        {{ $pub->fecha_publicacion ? $pub->fecha_publicacion->format('d/m/Y') : 'No publicada' }}
                                    </div>
                                </td>
                                <td>
                                    @if ($pub->estado === 'publicado')
                                        <span class="badge badge-verde">Publicado</span>
                                    @elseif ($pub->estado === 'pendiente')
                                        <span class="badge badge-celeste">Pendiente</span>
                                    @else
                                        <span class="badge badge-gris">Borrador</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                        <a href="{{ route('admin.publicaciones.edit', $pub) }}" class="btn btn-outline btn-sm">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.publicaciones.destroy', $pub) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar la publicación «{{ $pub->titulo }}» y todas sus imágenes asociadas?');">
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