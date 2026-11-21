
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Contribuidores | Semana de Sistemas 2026</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    @vite('resources/css/publicacion.css')

</head>

<body>

<!-- ENCABEZADO -->

<header class="encabezado">
    <div class="container d-flex justify-content-between
                align-items-center flex-wrap gap-3">

        <a href="{{ route('home') }}" class="logo">
            Semana de <span>Sistemas 2026</span>
        </a>

        <a href="{{ route('public.index') }}"
           class="btn-salir">
            Volver a publicaciones
        </a>

    </div>
</header>


<!-- PRESENTACIÓN -->

<section class="contribuidores-hero">

    <div class="container">

        <span class="hero-etiqueta">
            EQUIPO DE DESARROLLO · SDS 2026
        </span>

        <h1>
            Quienes hicieron posible este espacio
        </h1>

        <p>
            Detrás de cada publicación, fotografía y recuerdo
            existe un equipo comprometido con documentar
            las experiencias de la Semana de Sistemas 2026.

            Este espacio reúne evidencias de nuestra
            participación y del trabajo realizado durante
            las actividades universitarias.
        </p>

    </div>

</section>


<!-- GALERÍA -->

<main class="container">

    <section class="galeria-contribuidores">

        <div class="titulo-seccion">

            <h2>Momentos que nos representan</h2>

            <p>
                Una colección de fotografías que refleja
                nuestra participación, colaboración y
                experiencias durante la Semana de Sistemas.
            </p>

        </div>


        @php

            $fotografias = [
                [
                    'imagen' => 'fto1.jpeg',
                    'titulo' => 'Nuestro equipo',
                    'descripcion' => 'Equipo de desarrollo del blog SDS 2026',
                ],
                [
                    'imagen' => 'fto2.jpeg',
                    'titulo' => 'Participación en las actividades',
                    'descripcion' => 'Momentos durante la Semana de Sistemas',
                ],
                [
                    'imagen' => 'fto3.jpeg',
                    'titulo' => 'Compartiendo experiencias',
                    'descripcion' => 'Convivencia y aprendizaje',
                ],
                [
                    'imagen' => 'fto4.jpeg',
                    'titulo' => 'Trabajo en equipo',
                    'descripcion' => 'Colaboración durante el evento',
                ],
                [
                    'imagen' => 'fto5.jpeg',
                    'titulo' => 'Recuerdos de la SDS 2026',
                    'descripcion' => 'Momentos especiales',
                ],
                [
                    'imagen' => 'fto6.jpeg',
                    'titulo' => 'Nuestra participación',
                    'descripcion' => 'Evidencias fotográficas del equipo',
                ],
            ];
        @endphp


        <div class="row g-4">

            @foreach ($fotografias as $indice => $foto)

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="foto-card {{ $indice === 0 || $indice === 5
                        ? 'foto-grande'
                        : '' }}"
                         role="button"
                         tabindex="0"
                         data-bs-toggle="modal"
                         data-bs-target="#modalFotografia"
                         data-imagen="{{ asset('img/contribuidores/' . $foto['imagen']) }}"
                         data-titulo="{{ $foto['titulo'] }}">

                        <img
                            src="{{ asset('img/contribuidores/' . $foto['imagen']) }}"
                            alt="{{ $foto['titulo'] }}"
                            loading="lazy"
                        >

                        <div class="foto-overlay">

                            <h3>{{ $foto['titulo'] }}</h3>

                            <p>{{ $foto['descripcion'] }}</p>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </section>


    <!-- RECONOCIMIENTO -->

    <section class="reconocimiento">

        <h2>Un proyecto construido en equipo</h2>

        <p>
            Este blog fue desarrollado como un espacio
            para preservar las actividades, experiencias
            y momentos más importantes de la
            Semana de Sistemas 2026 de la
            Universidad de El Salvador.

            A través de estas fotografías dejamos
            constancia de nuestra participación y
            del compromiso con este proyecto.
        </p>
        <p>Fabiola Alexandra Romero Amaya - RA23033</p>
    <p>Juliana Mariselda Torres Chavarria - TC23011</p>

    </section>


    <div class="text-center mb-5">

        <a href="{{ route('public.index') }}"
           class="btn-volver-contribuidores">
            ← Regresar a las publicaciones
        </a>

    </div>

</main>


<!-- MODAL PARA AMPLIAR FOTOGRAFÍAS -->

<div class="modal fade"
     id="modalFotografia"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content bg-dark">

            <div class="modal-header border-0">

                <h5 class="modal-title text-white"
                    id="tituloModal">
                    Fotografía
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>

            </div>

            <div class="modal-body text-center">

                <img
                    id="imagenModal"
                    src=""
                    alt="Fotografía ampliada"
                    class="img-fluid modal-foto"
                >

            </div>

        </div>

    </div>

</div>


<!-- PIE DE PÁGINA -->

<footer class="text-center py-4">
    Universidad de El Salvador | Semana de Sistemas 2026
    
</footer>


<!-- BOOTSTRAP JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const modalFotografia = document.getElementById('modalFotografia');

    modalFotografia.addEventListener('show.bs.modal', function (event) {

        const tarjeta = event.relatedTarget;

        const imagen = tarjeta.getAttribute('data-imagen');
        const titulo = tarjeta.getAttribute('data-titulo');

        document.getElementById('imagenModal').src = imagen;
        document.getElementById('imagenModal').alt = titulo;
        document.getElementById('tituloModal').textContent = titulo;

    });
</script>

</body>
</html>
