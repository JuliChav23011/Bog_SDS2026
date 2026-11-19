@extends('layouts.admin')

@section('title', 'Editar Categoría')
@section('header-title', 'Editar Categoría')

@section('content')
    <div class="card" style="max-width: 680px;">
        <div class="card-header">
            <h2 class="card-title">Editando: {{ $categoria->nombre }}</h2>
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline btn-sm">Volver al listado</a>
        </div>

        <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre de la categoría *</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="form-control"
                    value="{{ old('nombre', $categoria->nombre) }}"
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
                >{{ old('descripcion', $categoria->descripcion) }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Actualizar categoría</button>
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection