@extends('layouts.admin')

@section('title', 'Imágenes de Publicaciones')
@section('header-title', 'Administración de Imágenes')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Subir imágenes a una publicación</h2>
        </div>

        @if ($publicaciones->isEmpty())
            <p style="color: var(--texto-suave);">
                Aún no existen publicaciones registradas.
                <a href="{{ route('admin.publicaciones.create') }}" style="color: var(--azul); font-weight: 700;">Crea primero una publicación</a>
                para poder asociarle imágenes.
            </p>
        @else
            <form action="{{ route('admin.imagenes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="publicacion_id">Publicación asociada *</label>
                        <select id="publicacion_id" name="publicacion_id" class="form-control" required>
                            <option value="">Selecciona una publicación...</option>
                            @foreach ($publicaciones as $pub)
                                <option value="{{ $pub->id }}" {{ old('publicacion_id', request('publicacion_id')) == $pub->id ? 'selected' : '' }}>
                                    #{{ $pub->id }} — {{ $pub->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="imagenes">Seleccionar imágenes (JPG, PNG, WEBP — máx. 4 MB c/u) *</label>
                        <input
                            type="file"
                            id="imagenes"
                            name="imagenes[]"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                            multiple
                            required
                        >
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="descripcion">Descripción o pie de foto (opcional)</label>
                        <input
                            type="text"
                            id="descripcion"
                            name="descripcion"
                            class="form-control"
                            maxlength="255"
                            value="{{ old('descripcion') }}"
                            placeholder="Ej. Fotografía grupal durante la ponencia..."
                        >
                    </div>

                    <div class="form-group" style="justify-content: center;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-top: 22px;">
                            <input type="checkbox" name="marcar_principal" value="1" {{ old('marcar_principal') ? 'checked' : '' }}>
                            Establecer la primera imagen subida como portada principal
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Subir imágenes</button>
            </form>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Imágenes registradas ({{ $imagenes->count() }})</h2>
        </div>

        <form action="{{ route('admin.imagenes.index') }}" method="GET" class="filter-bar">
            <select name="publicacion_id" class="form-control" style="max-width: 340px;">
                <option value="">Todas las publicaciones</option>
                @foreach ($publicaciones as $pub)
                    <option value="{{ $pub->id }}" {{ request('publicacion_id') == $pub->id ? 'selected' : '' }}>
                        #{{ $pub->id }} — {{ $pub->titulo }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Filtrar</button>

            @if (request()->filled('publicacion_id'))
                <a href="{{ route('admin.imagenes.index') }}" class="btn btn-outline">Ver todas</a>
            @endif
        </form>

        @if ($imagenes->isEmpty())
            <p style="color: var(--texto-suave);">No hay imágenes registradas para mostrar.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Vista previa</th>
                            <th>Publicación</th>
                            <th>Portada</th>
                            <th>Descripción, orden y reemplazo de archivo</th>
                            <th style="width: 110px;">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($imagenes as $img)
                            @php
                                $archivoExiste = \Illuminate\Support\Facades\Storage::disk('public')->exists($img->ruta);
                            @endphp
                            <tr>
                                <td>
                                    @if ($archivoExiste)
                                        <a href="{{ asset('storage/' . $img->ruta) }}" target="_blank" title="Ver imagen completa">
                                            <img src="{{ asset('storage/' . $img->ruta) }}" alt="Imagen #{{ $img->id }}" class="img-thumb" style="width: 84px; height: 60px;">
                                        </a>
                                    @else
                                        <span class="badge badge-rojo">Archivo no encontrado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($img->publicacion)
                                        <a href="{{ route('admin.publicaciones.edit', $img->publicacion) }}" style="color: var(--azul); font-weight: 700;">
                                            {{ $img->publicacion->titulo }}
                                        </a>
                                        <div style="font-size: 12px; color: var(--texto-suave);">
                                            {{ $img->publicacion->categoria->nombre ?? 'Sin categoría' }}
                                        </div>
                                    @else
                                        <span class="badge badge-rojo">Sin publicación</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($img->es_principal)
                                        <span class="badge badge-verde">Principal</span>
                                    @else
                                        <form action="{{ route('admin.imagenes.principal', $img) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline btn-sm">Hacer principal</button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.imagenes.update', $img) }}" method="POST" enctype="multipart/form-data"
                                          style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                        @csrf
                                        @method('PUT')

                                        <input
                                            type="text"
                                            name="descripcion"
                                            class="form-control"
                                            style="max-width: 210px; padding: 7px 10px; font-size: 13px;"
                                            value="{{ $img->descripcion }}"
                                            placeholder="Descripción..."
                                            maxlength="255"
                                        >

                                        <input
                                            type="number"
                                            name="orden"
                                            class="form-control"
                                            style="width: 72px; padding: 7px 10px; font-size: 13px;"
                                            value="{{ $img->orden }}"
                                            min="0"
                                            title="Orden de visualización"
                                        >

                                        <input
                                            type="file"
                                            name="archivo"
                                            class="form-control"
                                            style="max-width: 205px; padding: 5px 8px; font-size: 12px;"
                                            accept=".jpg,.jpeg,.png,.webp"
                                            title="Seleccionar archivo para reemplazar la imagen actual"
                                        >

                                        <button type="submit" class="btn btn-celeste btn-sm">Guardar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.imagenes.destroy', $img) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta imagen de forma permanente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection