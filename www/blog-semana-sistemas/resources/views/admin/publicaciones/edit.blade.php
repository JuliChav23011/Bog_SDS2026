@extends('layouts.admin')

@section('title', 'Editar Publicación')
@section('header-title', 'Editar Publicación')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Editando: {{ $publicacion->titulo }}</h2>
            <a href="{{ route('admin.publicaciones.index') }}" class="btn btn-outline btn-sm">Volver al listado</a>
        </div>

        <form action="{{ route('admin.publicaciones.update', $publicacion) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="titulo">Título de la publicación *</label>
                    <input
                        type="text"
                        id="titulo"
                        name="titulo"
                        class="form-control"
                        value="{{ old('titulo', $publicacion->titulo) }}"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoría *</label>
                    <select id="categoria_id" name="categoria_id" class="form-control" required>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id', $publicacion->categoria_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="fecha_actividad">Fecha de la actividad *</label>
                    <input
                        type="date"
                        id="fecha_actividad"
                        name="fecha_actividad"
                        class="form-control"
                        value="{{ old('fecha_actividad', $publicacion->fecha_actividad->format('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="estado">Estado de la publicación *</label>
                    <select id="estado" name="estado" class="form-control" required>
                        <option value="publicado" {{ old('estado', $publicacion->estado) === 'publicado' ? 'selected' : '' }}>Publicado</option>
                        <option value="pendiente" {{ old('estado', $publicacion->estado) === 'pendiente' ? 'selected' : '' }}>Pendiente de revisión</option>
                        <option value="borrador" {{ old('estado', $publicacion->estado) === 'borrador' ? 'selected' : '' }}>Borrador</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Autor(es) de la publicación *</label>
                <div style="display: flex; gap: 14px; flex-wrap: wrap; padding: 12px; border: 1px solid var(--borde-input); border-radius: 9px; background: var(--fondo);">
                    @php
                        $autoresActuales = old('autores', $publicacion->autores->pluck('id')->toArray());
                    @endphp
                    @foreach ($usuarios as $usuario)
                        <label style="display: flex; align-items: center; gap: 6px; font-weight: 500; margin: 0; cursor: pointer;">
                            <input
                                type="checkbox"
                                name="autores[]"
                                value="{{ $usuario->id }}"
                                {{ in_array($usuario->id, $autoresActuales) ? 'checked' : '' }}
                            >
                            {{ $usuario->name }} ({{ $usuario->role }})
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="resumen">Resumen corto (opcional, máx. 300 caracteres)</label>
                <input
                    type="text"
                    id="resumen"
                    name="resumen"
                    class="form-control"
                    maxlength="300"
                    value="{{ old('resumen', $publicacion->resumen) }}"
                >
            </div>

            <div class="form-group">
                <label for="contenido">Contenido completo *</label>
                <textarea
                    id="contenido"
                    name="contenido"
                    class="form-control"
                    rows="7"
                    required
                >{{ old('contenido', $publicacion->contenido) }}</textarea>
            </div>

            @if ($publicacion->imagenes->isNotEmpty())
                <div class="form-group">
                    <label>Imágenes actuales de la publicación</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px;">
                        @foreach ($publicacion->imagenes as $img)
                            <div style="border: 1px solid var(--borde); border-radius: 12px; padding: 12px; background: var(--fondo);">
                                <img
                                    src="{{ asset('storage/' . $img->ruta) }}"
                                    alt="Imagen publicación"
                                    style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;"
                                >
                                @if ($img->es_principal)
                                    <div style="margin-bottom: 8px;"><span class="badge badge-verde">Imagen principal</span></div>
                                @endif

                                <label style="font-size: 12px; margin-bottom: 4px;">Reemplazar imagen:</label>
                                <input type="file" name="reemplazos[{{ $img->id }}]" class="form-control" style="padding: 6px; font-size: 12px;" accept=".jpg,.jpeg,.png,.webp">

                                <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--peligro); margin-top: 8px; cursor: pointer;">
                                    <input type="checkbox" name="eliminar_imagenes[]" value="{{ $img->id }}">
                                    Eliminar esta imagen
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label for="imagenes">Agregar nuevas imágenes (JPG, PNG o WEBP)</label>
                <input
                    type="file"
                    id="imagenes"
                    name="imagenes[]"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp"
                    multiple
                >
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Actualizar publicación</button>
                <a href="{{ route('admin.publicaciones.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection