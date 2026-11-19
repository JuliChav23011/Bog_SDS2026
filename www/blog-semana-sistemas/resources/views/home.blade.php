
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semana de Sistemas 2026</title>
    @vite('resources/css/style.css')
    
</head>
<body>
    <header class="navbar">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-icon">S</span>
            Semana de <strong>Sistemas 2026</strong>
        </a>

        <nav>
            <a href="{{ route('home') }}">Inicio</a>
            <a href="#actividades">Actividades</a>
            <a href="#nosotros">Nosotros</a>
        </nav>
    @auth
        <div class="nav-usuario">

            <span class="nombre-usuario">
                Hola, {{ auth()->user()->name }}
            </span>

            <a href="{{ route('public.index') }}" class="btn-login">
                Ver publicaciones
            </a>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-login">
                    Panel administrador
                </a>
            @endif

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-cerrar">
                    Cerrar sesión
                </button>
            </form>

        </div>
    @else
        <a href="{{ route('login') }}" class="btn-login">
            Iniciar sesión
        </a>
    @endauth

    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <span class="eyebrow">UNIVERSIDAD DE EL SALVADOR</span>
                <h1>
                    Una semana de
                    <span>innovación y tecnología.</span>
                </h1>
                <p>
                    Revive las experiencias, proyectos y aprendizajes
                    que formaron parte de la Semana de Sistemas 2026.
                </p>
                <a href="#actividades" class="btn-primary">
                    Explorar actividades
                </a>
            </div>
            <div class="hero-art" aria-hidden="true">
                <div class="art-circle">
                    <span>&lt;/&gt;</span>
                </div>
                <div class="art-tag tag-one">INNOVACIÓN</div>
                <div class="art-tag tag-two">TECNOLOGÍA</div>
            </div>
        </section>

        <section class="section" id="actividades">
            <div class="section-heading">
                <span class="section-label">NUESTRO BLOG</span>
                <h2>Descubre las actividades</h2>
                <p>Conoce lo que vivimos durante esta semana.</p>
            </div>

            <div class="activities-grid">
                <article class="activity-card">
                    <span class="activity-icon">🎤</span>
                    <h3>Ponencias</h3>
                    <p>Ideas, experiencias y conocimientos compartidos.</p>
                </article>

                <article class="activity-card">
                    <span class="activity-icon">💻</span>
                    <h3>Talleres</h3>
                    <p>Aprendizaje práctico y nuevas herramientas.</p>
                </article>

                <article class="activity-card">
                    <span class="activity-icon">🧩</span>
                    <h3>Feria de proyectos</h3>
                    <p>Creatividad e innovación de los estudiantes.</p>
                </article>

                <article class="activity-card">
                    <span class="activity-icon">⚽</span>
                    <h3>Fútbol sala</h3>
                    <p>Deporte, compañerismo y participación.</p>
                </article>

                <article class="activity-card">
                    <span class="activity-icon">🎉</span>
                    <h3>Bingo</h3>
                    <p>Momentos de diversión y convivencia.</p>
                </article>

                <article class="activity-card">
                    <span class="activity-icon">🍕</span>
                    <h3>Pizza y parrillada</h3>
                    <p>Espacios para compartir y crear recuerdos.</p>
                </article>
            </div>
        </section>

        <section class="about-section" id="nosotros">
            <div class="about-inner">
                <span class="section-label">SOBRE EL PROYECTO</span>
                <h2>Mucho más que una semana académica</h2>
                <p>
                    Este blog documenta las actividades y reflexiones
                    de la Semana de Sistemas 2026, realizada en la
                    Universidad de El Salvador.
                </p>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2026 Semana de Sistemas | Universidad de El Salvador</p>
    </footer>
</body>
</html>
