@extends('layouts.admin')

@section('title', 'Nueva Publicación')
@section('header-title', 'Crear Publicación')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Nueva publicación de la Semana de Sistemas</h2>
            <a href="{{ route('admin.publicaciones.index') }}" class="btn btn-outline btn-sm">Volver al listado</a>
        </div>

        <form action="{{ route('admin.publicaciones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="titulo">Título de la publicación *</label>
                    <input
                        type="text"
                        id="titulo"
                        name="titulo"
                        class="form-control"
                        value="{{ old('titulo') }}"
                        placeholder="Ej. Inauguración y Ponencia Principal"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoría *</label>
                    <select id="categoria_id" name="categoria_id" class="form-control" required>
                        <option value="">Selecciona una categoría...</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
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
                        value="{{ old('fecha_actividad', date('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="estado">Estado de la publicación *</label>
                    <select id="estado" name="estado" class="form-control" required>
                        <option value="publicado" {{ old('estado') === 'publicado' ? 'selected' : '' }}>Publicado</option>
                        <option value="pendiente" {{ old('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente de revisión</option>
                        <option value="borrador" {{ old('estado', 'borrador') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Autor(es) de la publicación *</label>
                <div style="display: flex; gap: 14px; flex-wrap: wrap; padding: 12px; border: 1px solid var(--borde-input); border-radius: 9px; background: var(--fondo);">
                    @php
                        $autoresSeleccionados = old('autores', [auth()->id()]);
                    @endphp
                    @foreach ($usuarios as $usuario)
                        <label style="display: flex; align-items: center; gap: 6px; font-weight: 500; margin: 0; cursor: pointer;">
                            <input
                                type="checkbox"
                                name="autores[]"
                                value="{{ $usuario->id }}"
                                {{ in_array($usuario->id, $autoresSeleccionados) ? 'checked' : '' }}
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
                    value="{{ old('resumen') }}"
                    placeholder="Breve descripción que aparecerá en la vista previa..."
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
                    placeholder="Redacta los detalles de la actividad, participantes y resultados..."
                >{{ old('contenido') }}</textarea>
            </div>

            <div class="form-group">
                <label for="imagenes">Adjuntar imágenes (JPG, PNG o WEBP - máx. 4 MB cada una)</label>
                <input
                    type="file"
                    id="imagenes"
                    name="imagenes[]"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp"
                    multiple
                >
                <small style="color: var(--texto-suave);">La primera imagen seleccionada se marcará automáticamente como portada principal.</small>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Guardar publicación</button>
                <a href="{{ route('admin.publicaciones.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection