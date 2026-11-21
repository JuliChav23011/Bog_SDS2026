<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Talleres | Semana de Sistemas 2026</title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- ESTILOS -->
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
                Talleres
            </h1>

            <p>
                Descubre los talleres desarrollados durante la
                Semana de Sistemas 2026, sus participantes,
                conocimientos compartidos, experiencias prácticas
                y momentos destacados.
            </p>

        </section>

        <!-- PUBLICACIONES -->
        <section class="seccion-actividades">

            <h2 class="fw-bold mb-4">
                Publicaciones de Talleres
            </h2>

            @if ($publicaciones->isNotEmpty())

                <!-- CARRUSEL PRINCIPAL DE TALLERES -->
                <div
                    id="carruselTalleres"
                    class="carrusel-publicaciones"
                >

                    <div class="contenedor-ponencias">

                        @foreach ($publicaciones as $publicacion)

                            @php
                                $imagenes = $publicacion->imagenes->values();
                            @endphp

                            <!-- CADA TALLER ES UNA DIAPOSITIVA -->
                            <div
                                class="diapositiva-ponencia {{ $loop->first ? 'ponencia-activa' : '' }}"
                            >

                                <article class="articulo-editorial">

                                    <!-- ENCABEZADO EDITORIAL -->
                                    <div class="editorial-encabezado">

                                        <span class="editorial-etiqueta">
                                            SEMANA DE SISTEMAS 2026
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

                                    <!-- CONTENIDO EN DOS COLUMNAS -->
                                    <div class="editorial-cuerpo">

                                        <!-- COLUMNA IZQUIERDA -->
                                        <div class="editorial-columna-izquierda">

                                            <!-- CARRUSEL DE FOTOGRAFÍAS -->
                                            @if ($imagenes->isNotEmpty())

                                                <div
                                                    id="fotos-taller-{{ $publicacion->id }}"
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
                                                                        alt="Fotografía del taller {{ $publicacion->titulo }}"
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
                                                            data-bs-target="#fotos-taller-{{ $publicacion->id }}"
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
                                                            data-bs-target="#fotos-taller-{{ $publicacion->id }}"
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
                                                                    data-bs-target="#fotos-taller-{{ $publicacion->id }}"
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

                                                <div class="editorial-sin-imagen">
                                                    🛠️
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

                                            <!-- DESCRIPCIÓN COMPLETA -->
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
                                                            alt="Momento destacado del taller {{ $publicacion->titulo }}"
                                                            loading="lazy"
                                                        >

                                                    </a>

                                                    <figcaption>
                                                        Momentos destacados del taller
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

                            </div>

                        @endforeach

                    </div>

                </div>

                <!-- NAVEGACIÓN ENTRE TALLERES -->
                @if ($publicaciones->count() > 1)

                    <div class="navegacion-ponencias">

                        <button
                            id="btnTallerAnterior"
                            class="btn-ponencia-anterior"
                            type="button"
                        >
                            ← Taller anterior
                        </button>

                        <div class="contador-ponencias">

                            <span id="tallerActual">1</span>
                            /
                            <span>{{ $publicaciones->count() }}</span>

                        </div>

                        <button
                            id="btnTallerSiguiente"
                            class="btn-ponencia-siguiente"
                            type="button"
                        >
                            Siguiente taller →
                        </button>

                    </div>

                @endif

            @else

                <div class="alert alert-info">
                    Todavía no hay talleres publicados.
                </div>

            @endif

        </section>

    </main>

    <!-- PIE DE PÁGINA -->
    <footer>
        Universidad de El Salvador | Semana de Sistemas 2026
    </footer>

    <!-- BOOTSTRAP PARA EL CARRUSEL DE FOTOGRAFÍAS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JAVASCRIPT PARA CAMBIAR ENTRE TALLERES -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const talleres = document.querySelectorAll(
                '#carruselTalleres .diapositiva-ponencia'
            );

            const btnAnterior = document.getElementById(
                'btnTallerAnterior'
            );

            const btnSiguiente = document.getElementById(
                'btnTallerSiguiente'
            );

            const contador = document.getElementById(
                'tallerActual'
            );

            let indiceActual = 0;

            function mostrarTaller(indice) {

                if (talleres.length === 0) {
                    return;
                }

                talleres.forEach(function (taller) {
                    taller.classList.remove('ponencia-activa');
                });

                indiceActual = (
                    indice + talleres.length
                ) % talleres.length;

                talleres[indiceActual].classList.add(
                    'ponencia-activa'
                );

                if (contador) {
                    contador.textContent = indiceActual + 1;
                }
            }

            if (btnAnterior) {
                btnAnterior.addEventListener('click', function () {
                    mostrarTaller(indiceActual - 1);
                });
            }

            if (btnSiguiente) {
                btnSiguiente.addEventListener('click', function () {
                    mostrarTaller(indiceActual + 1);
                });
            }

            mostrarTaller(0);

        });
    </script>

</body>
</html>
