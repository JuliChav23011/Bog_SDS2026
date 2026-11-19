@extends('layouts.admin')

@section('title', 'Evidencias')
@section('header-title', 'Administración de Evidencias')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Registrar nueva evidencia</h2>
        </div>

        @if ($publicaciones->isEmpty())
            <p style="color: var(--texto-suave);">
                Aún no existen publicaciones registradas.
                <a href="{{ route('admin.publicaciones.create') }}" style="color: var(--azul); font-weight: 700;">Crea primero una publicación</a>
                para poder asociarle evidencias.
            </p>
        @else
            <form action="{{ route('admin.evidencias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="publicacion_id">Publicación / Actividad asociada *</label>
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
                        <label for="usuario_id">Usuario / Participante responsable *</label>
                        <select id="usuario_id" name="usuario_id" class="form-control" required>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('usuario_id', auth()->id()) == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="fecha_evidencia">Fecha de la evidencia *</label>
                        <input
                            type="date"
                            id="fecha_evidencia"
                            name="fecha_evidencia"
                            class="form-control"
                            value="{{ old('fecha_evidencia', date('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="archivos">Archivos de evidencia (JPG, PNG, WEBP o PDF — máx. 5 MB c/u) *</label>
                        <input
                            type="file"
                            id="archivos"
                            name="archivos[]"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                            multiple
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción de la evidencia (opcional)</label>
                    <input
                        type="text"
                        id="descripcion"
                        name="descripcion"
                        class="form-control"
                        maxlength="255"
                        value="{{ old('descripcion') }}"
                        placeholder="Ej. Lista de asistencia firmada, captura de participación o fotografía del taller..."
                    >
                </div>

                <button type="submit" class="btn btn-success">Guardar evidencia</button>
            </form>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Evidencias registradas ({{ $evidencias->count() }})</h2>
        </div>

        <form action="{{ route('admin.evidencias.index') }}" method="GET" class="filter-bar">
            <select name="publicacion_id" class="form-control" style="max-width: 300px;">
                <option value="">Todas las publicaciones</option>
                @foreach ($publicaciones as $pub)
                    <option value="{{ $pub->id }}" {{ request('publicacion_id') == $pub->id ? 'selected' : '' }}>
                        #{{ $pub->id }} — {{ $pub->titulo }}
                    </option>
                @endforeach
            </select>

            <select name="usuario_id" class="form-control" style="max-width: 240px;">
                <option value="">Todos los usuarios</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ request('usuario_id') == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Filtrar</button>

            @if (request()->hasAny(['publicacion_id', 'usuario_id']))
                <a href="{{ route('admin.evidencias.index') }}" class="btn btn-outline">Limpiar</a>
            @endif
        </form>

        @if ($evidencias->isEmpty())
            <p style="color: var(--texto-suave);">No hay evidencias registradas con los criterios seleccionados.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Archivo</th>
                            <th>Publicación</th>
                            <th>Editar datos y reemplazo</th>
                            <th style="width: 110px;">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evidencias as $ev)
                            @php
                                $archivoExiste = \Illuminate\Support\Facades\Storage::disk('public')->exists($ev->ruta);
                                $esPdf = str_ends_with(strtolower($ev->ruta), '.pdf');
                            @endphp
                            <tr>
                                <td>
                                    @if (!$archivoExiste)
                                        <span class="badge badge-rojo">Archivo no encontrado</span>
                                    @elseif ($esPdf)
                                        <a href="{{ asset('storage/' . $ev->ruta) }}" target="_blank" class="btn btn-outline btn-sm">
                                            Ver PDF
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $ev->ruta) }}" target="_blank" title="Ver evidencia completa">
                                            <img src="{{ asset('storage/' . $ev->ruta) }}" alt="Evidencia #{{ $ev->id }}" class="img-thumb" style="width: 84px; height: 60px;">
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if ($ev->publicacion)
                                        <a href="{{ route('admin.publicaciones.edit', $ev->publicacion) }}" style="color: var(--azul); font-weight: 700;">
                                            {{ $ev->publicacion->titulo }}
                                        </a>
                                        <div style="font-size: 12px; color: var(--texto-suave);">
                                            {{ $ev->publicacion->categoria->nombre ?? 'Sin categoría' }}
                                        </div>
                                    @else
                                        <span class="badge badge-rojo">Sin publicación</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.evidencias.update', $ev) }}" method="POST" enctype="multipart/form-data"
                                          style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                        @csrf
                                        @method('PUT')

                                        <select name="usuario_id" class="form-control" style="max-width: 175px; padding: 7px 10px; font-size: 13px;" required>
                                            @foreach ($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}" {{ $ev->usuario_id == $usuario->id ? 'selected' : '' }}>
                                                    {{ $usuario->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input
                                            type="date"
                                            name="fecha_evidencia"
                                            class="form-control"
                                            style="max-width: 145px; padding: 7px 10px; font-size: 13px;"
                                            value="{{ $ev->fecha_evidencia->format('Y-m-d') }}"
                                            required
                                        >

                                        <input
                                            type="text"
                                            name="descripcion"
                                            class="form-control"
                                            style="max-width: 190px; padding: 7px 10px; font-size: 13px;"
                                            value="{{ $ev->descripcion }}"
                                            placeholder="Descripción..."
                                            maxlength="255"
                                        >

                                        <input
                                            type="file"
                                            name="archivo"
                                            class="form-control"
                                            style="max-width: 195px; padding: 5px 8px; font-size: 12px;"
                                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                                            title="Reemplazar archivo de evidencia"
                                        >

                                        <button type="submit" class="btn btn-celeste btn-sm">Guardar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.evidencias.destroy', $ev) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta evidencia de forma permanente?');">
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