<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Administrativo') | Semana de Sistemas 2026</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="brand-badge">S26</div>
                <div>
                    <h2>Semana Sistemas</h2>
                    <span>Panel Administrativo</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-label">Principal</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    Ver blog público
                </a>

                <div class="nav-label">Gestión del Blog</div>
                <a href="{{ url('/admin/publicaciones') }}" class="nav-link {{ request()->is('admin/publicaciones*') ? 'active' : '' }}">
                    Publicaciones
                </a>
                <a href="{{ url('/admin/categorias') }}" class="nav-link {{ request()->is('admin/categorias*') ? 'active' : '' }}">
                    Categorías
                </a>
                <a href="{{ url('/admin/imagenes') }}" class="nav-link {{ request()->is('admin/imagenes*') ? 'active' : '' }}">
                    Imágenes
                </a>
                <a href="{{ url('/admin/evidencias') }}" class="nav-link {{ request()->is('admin/evidencias*') ? 'active' : '' }}">
                    Evidencias
                </a>

                <div class="nav-label">Sistema</div>
                <a href="{{ url('/admin/usuarios') }}" class="nav-link {{ request()->is('admin/usuarios*') ? 'active' : '' }}">
                    Usuarios y Roles
                </a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
                        ☰
                    </button>
                    <h1 class="topbar-title">@yield('header-title', 'Panel de Control')</h1>
                </div>

                <div class="topbar-user">
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <span class="user-role">Administrador</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Cerrar sesión</button>
                    </form>
                </div>
            </header>

            <main class="content-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul style="margin-left: 1.2rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>