@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header-title', 'Resumen General')

@section('content')
    <div class="stats-grid">
        <div class="stat-card azul">
            <div class="stat-label">Total Publicaciones</div>
            <div class="stat-value">{{ $totalPublicaciones }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Categorías Registradas</div>
            <div class="stat-value">{{ $totalCategorias }}</div>
        </div>
        <div class="stat-card verde">
            <div class="stat-label">Usuarios Registrados</div>
            <div class="stat-value">{{ $totalUsuarios }}</div>
        </div>
        <div class="stat-card azul">
            <div class="stat-label">Administradores</div>
            <div class="stat-value">{{ $totalAdmins }}</div>
        </div>
        <div class="stat-card verde">
            <div class="stat-label">Evidencias Cargadas</div>
            <div class="stat-value">{{ $totalEvidencias }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Accesos directos</h2>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ url('/admin/publicaciones/create') }}" class="btn btn-success">+ Nueva publicación</a>
            <a href="{{ url('/admin/categorias') }}" class="btn btn-celeste">Gestionar categorías</a>
            <a href="{{ url('/admin/evidencias') }}" class="btn btn-primary">Subir evidencias</a>
            <a href="{{ url('/admin/usuarios') }}" class="btn btn-outline">Administrar usuarios</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Publicaciones recientes</h2>
            <a href="{{ url('/admin/publicaciones') }}" class="btn btn-outline btn-sm">Ver todas</a>
        </div>

        @if ($publicacionesRecientes->isEmpty())
            <p style="color: var(--texto-suave);">Aún no hay publicaciones registradas en el sistema.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Fecha de creación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($publicacionesRecientes as $pub)
                            <tr>
                                <td>#{{ $pub->id }}</td>
                                <td>{{ $pub->titulo ?? $pub->title ?? 'Sin título' }}</td>
                                <td>{{ \Carbon\Carbon::parse($pub->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection