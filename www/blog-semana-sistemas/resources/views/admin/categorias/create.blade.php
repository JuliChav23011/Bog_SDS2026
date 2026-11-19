@extends('layouts.admin')

@section('title', 'Nueva Categoría')
@section('header-title', 'Crear Categoría')

@section('content')
    <div class="card" style="max-width: 680px;">
        <div class="card-header">
            <h2 class="card-title">Datos de la nueva categoría</h2>
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline btn-sm">Volver al listado</a>
        </div>

        <form action="{{ route('admin.categorias.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre de la categoría *</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="form-control"
                    value="{{ old('nombre') }}"
                    placeholder="Ej. Ponencias, Talleres, Feria de proyectos..."
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción (opcional)</label>
                <textarea
                    id="descripcion"
                    name="descripcion"
                    class="form-control"
                    rows="4"
                    maxlength="1000"
                    placeholder="Describe brevemente el tipo de actividades que agrupa esta categoría..."
                >{{ old('descripcion') }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Guardar categoría</button>
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection