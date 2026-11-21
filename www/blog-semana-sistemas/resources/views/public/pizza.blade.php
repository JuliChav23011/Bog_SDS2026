<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Comelona de Pizza | Semana de Sistemas 2026</title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- ESTILOS DEL PROYECTO -->
    @vite([
        'resources/css/publicacion.css',
        'resources/css/contenido.css'
    ])
</head>

<body>

    <!-- ENCABEZADO -->
    <header class="encabezado">

        <div class="container d-flex justify-content-between
                    align-items-center flex-wrap gap-3">

            <a href="{{ route('public.index') }}" class="logo">
                Semana de <span>Sistemas 2026</span>
            </a>

            <div class="d-flex align-items-center gap-3 flex-wrap">

                @auth

                    <span>{{ auth()->user()->name }}</span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit" class="btn-salir">
                            Cerrar sesión
                        </button>

                    </form>

                @endauth

            </div>

        </div>

    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container">

        <!-- INTRODUCCIÓN -->
        <section class="seccion-actividades">

            <a
                href="{{ route('public.index') }}"
                class="btn-explorar d-inline-block mb-4"
            >
                ← Volver a las actividades
            </a>

            <h1 class="fw-bold mb-3">
                Comelona de Pizza
            </h1>

            <p>
                Revive los momentos de diversión, convivencia y
                entusiasmo de la Comelona de Pizza de la Semana
                de Sistemas 2026. Descubre las experiencias,
                participantes y momentos destacados de esta
                actividad.
            </p>

        </section>

        <!-- PUBLICACIONES DE LA COMELONA -->
        <section class="seccion-actividades">

            <h2 class="fw-bold mb-4">
                Publicaciones de la Comelona de Pizza
            </h2>

            @forelse ($publicaciones as $publicacion)

                @php
                    $imagenes = $publicacion->imagenes->values();
                @endphp

                <!-- SECCIÓN INDEPENDIENTE -->
                <article
                    class="articulo-editorial pizza-seccion"
                    id="pizza-publicacion-{{ $publicacion->id }}"
                >

                    <!-- ENCABEZADO DE LA PUBLICACIÓN -->
                    <div class="editorial-encabezado">

                        <span class="editorial-etiqueta">
                            COMELONA DE PIZZA | SEMANA DE SISTEMAS 2026
                        </span>

                        <h2 class="editorial-titulo">
                            {{ $publicacion->titulo }}
                        </h2>

                        @if ($publicacion->fecha_actividad)

                            <p class="editorial-fecha">
                                {{ $publicacion->fecha_actividad->format('d/m/Y') }}
                            </p>

                        @endif

                    </div>

                    <!-- CUERPO EDITORIAL -->
                    <div class="editorial-cuerpo">

                        <!-- COLUMNA IZQUIERDA -->
                        <div class="editorial-columna-izquierda">

                            <!-- CARRUSEL DE FOTOGRAFÍAS -->
                            @if ($imagenes->isNotEmpty())

                                <div
                                    id="fotos-pizza-{{ $publicacion->id }}"
                                    class="carousel slide editorial-carrusel"
                                    data-bs-interval="false"
                                    data-bs-touch="true"
                                >

                                    <div class="carousel-inner">

                                        @foreach ($imagenes as $imagen)

                                            <div
                                                class="carousel-item {{ $loop->first ? 'active' : '' }}"
                                            >

                                                <a
                                                    href="{{ asset('storage/' . $imagen->ruta) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >

                                                    <img
                                                        src="{{ asset('storage/' . $imagen->ruta) }}"
                                                        class="d-block w-100"
                                                        alt="Fotografía de la Comelona de Pizza: {{ $publicacion->titulo }}"
                                                        loading="lazy"
                                                    >

                                                </a>

                                            </div>

                                        @endforeach

                                    </div>

                                    @if ($imagenes->count() > 1)

                                        <!-- FOTOGRAFÍA ANTERIOR -->
                                        <button
                                            class="carousel-control-prev"
                                            type="button"
                                            data-bs-target="#fotos-pizza-{{ $publicacion->id }}"
                                            data-bs-slide="prev"
                                            aria-label="Fotografía anterior"
                                        >

                                            <span
                                                class="carousel-control-prev-icon"
                                                aria-hidden="true"
                                            ></span>

                                        </button>

                                        <!-- FOTOGRAFÍA SIGUIENTE -->
                                        <button
                                            class="carousel-control-next"
                                            type="button"
                                            data-bs-target="#fotos-pizza-{{ $publicacion->id }}"
                                            data-bs-slide="next"
                                            aria-label="Fotografía siguiente"
                                        >

                                            <span
                                                class="carousel-control-next-icon"
                                                aria-hidden="true"
                                            ></span>

                                        </button>

                                        <!-- INDICADORES -->
                                        <div class="carousel-indicators">

                                            @foreach ($imagenes as $imagen)

                                                <button
                                                    type="button"
                                                    data-bs-target="#fotos-pizza-{{ $publicacion->id }}"
                                                    data-bs-slide-to="{{ $loop->index }}"
                                                    class="{{ $loop->first ? 'active' : '' }}"
                                                    aria-label="Ver fotografía {{ $loop->iteration }}"
                                                    @if ($loop->first)
                                                        aria-current="true"
                                                    @endif
                                                ></button>

                                            @endforeach

                                        </div>

                                    @endif

                                </div>

                            @else

                                <!-- CUANDO NO HAY FOTOGRAFÍAS -->
                                <div class="editorial-sin-imagen">
                                    🍕
                                </div>

                            @endif

                            <!-- RESUMEN -->
                            @if ($publicacion->resumen)

                                <p class="editorial-resumen">
                                    {{ $publicacion->resumen }}
                                </p>

                            @endif

                        </div>

                        <!-- COLUMNA DERECHA -->
                        <div class="editorial-columna-derecha">

                            <!-- CONTENIDO DE LA PUBLICACIÓN -->
                            @if ($publicacion->contenido)

                                <div class="editorial-texto">
                                    {!! nl2br(e($publicacion->contenido)) !!}
                                </div>

                            @endif

                            <!-- FOTOGRAFÍA SECUNDARIA -->
                            @if ($imagenes->count() > 1)

                                <figure class="editorial-imagen editorial-imagen-secundaria">

                                    <a
                                        href="{{ asset('storage/' . $imagenes[1]->ruta) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >

                                        <img
                                            src="{{ asset('storage/' . $imagenes[1]->ruta) }}"
                                            alt="Momentos destacados de la Comelona de Pizza"
                                            loading="lazy"
                                        >

                                    </a>

                                    <figcaption>
                                        Momentos destacados de la Comelona de Pizza
                                    </figcaption>

                                </figure>

                            @endif

                        </div>

                    </div>

                    <!-- AUTORES -->
                    @if ($publicacion->autores->isNotEmpty())

                        <div class="editorial-autores">

                            <strong>Autores:</strong>

                            {{ $publicacion->autores->pluck('name')->join(', ') }}

                        </div>

                    @endif

                </article>

            @empty

                <div class="alert alert-info">
                    Todavía no hay publicaciones de la Comelona de Pizza.
                </div>

            @endforelse

        </section>

    </main>

    <!-- PIE DE PÁGINA -->
    <footer>
        Universidad de El Salvador | Semana de Sistemas 2026
    </footer>

    <!-- BOOTSTRAP JS: SOLO CARRUSEL DE FOTOGRAFÍAS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
