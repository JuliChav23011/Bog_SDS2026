<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Publicaciones | Semana de Sistemas 2026</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
     @vite('resources/css/publicacion.css')
</head>

<body>

<header class="encabezado">
    <div class="container d-flex justify-content-between
                align-items-center flex-wrap gap-3">

        <a href="{{ route('home') }}" class="logo">
            Semana de <span>Sistemas 2026</span>
        </a>

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <span>{{ auth()->user()->name }}</span>

            <a href="{{ route('contribuidores.index') }}" class="btn-salir btn-contribuidores">Contribuidores</a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-salir">
                    Cerrar sesión
                </button>
            </form>
        </div>

    </div>
</header>


@php
    // Categorías reales de PostgreSQL: cualquier categoría nueva aparece aquí.
    $categorias = \App\Models\Categoria::orderBy('id')->get();

    // Apariencia de categorías conocidas; las futuras reciben un estilo por defecto.
    $estilos = [
        'ponencia' => ['icono' => '🎤', 'color' => '#e0f2fe', 'ruta' => 'public.ponencias'],
        'talleres' => ['icono' => '💻', 'color' => '#d1fae5', 'ruta' => 'public.talleres'],
        'bingo' => ['icono' => '🎲', 'color' => '#f3e8ff', 'ruta' => 'public.bingo'],
        'expo-system' => ['icono' => '🚀', 'color' => '#e0f2fe', 'ruta' => 'public.expo-system'],
        'pizza' => ['icono' => '🍕', 'color' => '#ffedd5', 'ruta' => 'public.pizza'],
        'futsal' => ['icono' => '⚽', 'color' => '#dcfce7', 'ruta' => 'public.futsal'],
        'parrillada' => ['icono' => '🔥', 'color' => '#fee2e2', 'ruta' => 'public.parrillada'],
        'actividades-extras' => ['icono' => '✨', 'color' => '#ede9fe', 'ruta' => null],
    ];
@endphp

<main class="container">

    <!-- CARRUSEL DE CATEGORÍAS -->
    <section class="seccion-actividades">

        <h2 class="fw-bold mb-4">
            Actividades de la semana
        </h2>

        <div class="carrusel-contenedor">

            <button type="button"
                    class="flecha flecha-izquierda"
                    id="anterior"
                    aria-label="Categoría anterior">
                &#8249;
            </button>

            <div class="carrusel-ventana" id="ventanaCarrusel">

                <div class="carrusel-pista" id="pistaCarrusel">

                    @forelse ($categorias as $categoria)
                        @php
                            $estilo = $estilos[$categoria->slug] ?? ['icono' => '📌', 'color' => '#e2e8f0', 'ruta' => null];
                            $url = $estilo['ruta'] && \Illuminate\Support\Facades\Route::has($estilo['ruta'])
                                ? route($estilo['ruta'])
                                : route('public.categoria', ['slug' => $categoria->slug]);
                        @endphp

                        <article class="tarjeta">

                            <div class="tarjeta-imagen"
                                 style="background: {{ $estilo['color'] }};">
                                {{ $estilo['icono'] }}
                            </div>

                            <div class="tarjeta-contenido">

                                <h3>{{ $categoria->nombre }}</h3>

                                <p>{{ $categoria->descripcion ?: 'Descubre las publicaciones de esta actividad.' }}</p>

                                <a href="{{ $url }}"
                                    class="btn-explorar">
                                    Ver publicaciones
                                </a>

                            </div>

                        </article>

                    @empty
                        <p class="text-center w-100 py-4">Todavía no hay categorías registradas.</p>
                    @endforelse

                </div>

            </div>

            <button type="button"
                    class="flecha flecha-derecha"
                    id="siguiente"
                    aria-label="Siguiente categoría">
                &#8250;
            </button>

        </div>

        <p class="contador" id="contadorCarrusel"></p>

    </section>

  
<!-- CRÓNICA DE INAUGURACIÓN -->
<section class="cronica-inauguracion" id="introduccion">

    <!-- FOTO PRINCIPAL -->
    <div class="portada-inauguracion">

        <img
            src="{{ asset('img/inauguracion/foto5.jpeg') }}"
            alt="Inauguración de la Semana de Sistemas 2026"
            class="foto-principal" >

        <div class="portada-texto">
            <span>UNIVERSIDAD DE EL SALVADOR</span>

            <h1>
                Ingeniería de Sistemas Informáticos SDS2026
            </h1>

            <p>
                Inauguración de la Semana de Sistemas 2026
            </p>
        </div>

    </div>

    <!-- INTRODUCCIÓN -->
    <div class="texto-inauguracion">

        <h2>Inauguración de la Semana de Sistemas 2026</h2>

        <p>
            La Semana de Sistemas 2026 dio inicio con una
            jornada inaugural dedicada a la formación académica,
            la innovación tecnológica y la convivencia
            de la comunidad universitaria.
        </p>

        <p>
            Durante la ceremonia se contó con la participación
            de autoridades académicas e invitados especiales,
            quienes compartieron mensajes dirigidos
            a los estudiantes de Ingeniería de Sistemas
            Informáticos.
        </p>

    </div>

    <!-- MESA DE HONOR -->
    <div class="titulo-mesa">
        <span>ACTO INAUGURAL</span>
        <h2>Palabras de la mesa de honor</h2>
        <p>
            Intervenciones realizadas durante la ceremonia
            de inauguración.
        </p>
    </div>

    <!-- PRIMERA INTERVENCIÓN -->
    <article class="intervencion imagen-izquierda">
        <div class="intervencion-texto">
            <span class="numero-intervencion">
                01 · Primera intervención
            </span>

            <h3>Ing. José Luis Castro Cordero</h3>

            <p class="cargo">
                Jefe del depertamento de Ingeneria y Arquitectura
            </p>

            <p>
                El Ing. Castro nos hablo sobre lo especial que es para nosotros 
                la semana de sistemas y lo escencial del estudio de la carrera de ingeneria de sistemas
                informaticos
            </p>
        </div>

        <div class="intervencion-imagen">
            <img
                src="{{ asset('img/inauguracion/foto2.jpeg') }}"
                alt="Primera intervención de la mesa de honor"
                loading="lazy"
            >
        </div>

    </article>

    <!-- SEGUNDA INTERVENCIÓN -->
    <article class="intervencion imagen-derecha">
        <div class="intervencion-texto">
            <span class="numero-intervencion">
                02 · Segunda intervención
            </span>

            <h3>Ing. Ligia Astrid Hernandez</h3>

            <p class="cargo">
                Coordinadora de la carrrera Ingeneria de Sistemas
            </p>

            <p>
                La Ing. Astrid nos hablo sobre la importancia de la tematica de GREEN IOT seleccionada para 
                la semana de sistemas y como quedo maravillada sobre el tema 
            </p>
        </div>

        <div class="intervencion-imagen">
            <img
                src="{{ asset('img/inauguracion/foto3.jpeg') }}"
                alt="Segunda intervención de la mesa de honor"
                loading="lazy"
            >
        </div>

    </article>

    <!-- TERCERA INTERVENCIÓN -->
    <article class="intervencion  imagen-izquierda">

        <div class="intervencion-texto">
            <span class="numero-intervencion">
                03 · Tercera intervención
            </span>

            <h3>Dr. Norma Azucena Flores Retana</h3>

            <p class="cargo">
                Vice decana de facultad Multidisiplinaria Oriental
            </p>
            <p>
                Hablo sobre que le gusta la fmo y como se alegra por los estudiates 
            </p>
        </div>

        <div class="intervencion-imagen">
            <img
                src="{{ asset('img/inauguracion/foto6.jpeg') }}"
                alt="Tercera intervención de la mesa de honor"
                loading="lazy"
            >
        </div>
    </article>
    <article class="intervencion imagen-derecha">
        <div class="intervencion-texto">
            <span class="numero-intervencion">
                04 · Cuarta intervención
            </span>

            <h3>Bairon Levi Gomez Velasquez</h3>

            <p class="cargo">
                Presidente de la ASEIS
            </p>

            <p>
               Dio gracias a las autoridades presente y dio la bienvenida e inicio a la semana de sistemas 2026
            </p>
        </div>

        <div class="intervencion-imagen">
            <img
                src="{{ asset('img/inauguracion/foto7.jpeg') }}"
                alt="Segunda intervención de la mesa de honor"
                loading="lazy"
            >
        </div>

    </article>

</section>

</main>

<footer>
    Universidad de El Salvador | Semana de Sistemas 2026
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const pista = document.getElementById('pistaCarrusel');
    const tarjetas = Array.from(pista.querySelectorAll('.tarjeta'));
    const anterior = document.getElementById('anterior');
    const siguiente = document.getElementById('siguiente');
    const contador = document.getElementById('contadorCarrusel');

    let posicion = 0;

    function tarjetasVisibles() {
        if (window.innerWidth <= 600) return 1;
        if (window.innerWidth <= 991) return 2;
        return 3;
    }

    function actualizarCarrusel() {
        const visibles = tarjetasVisibles();
        const maximo = Math.max(0, tarjetas.length - visibles);

        posicion = Math.max(0, Math.min(posicion, maximo));

        if (tarjetas.length === 0) {
            anterior.disabled = true;
            siguiente.disabled = true;
            contador.textContent = '0 categorías';
            return;
        }

        const anchoTarjeta = tarjetas[0].getBoundingClientRect().width;
        const espacio = parseFloat(getComputedStyle(pista).gap) || 0;

        pista.style.transform =
            `translateX(-${posicion * (anchoTarjeta + espacio)}px)`;

        anterior.disabled = posicion === 0;
        siguiente.disabled = posicion === maximo;

        contador.textContent =
            `${posicion + 1} - ${Math.min(posicion + visibles, tarjetas.length)} de ${tarjetas.length} categorías`;
    }

    anterior.addEventListener('click', function () {
        posicion = Math.max(0, posicion - 1);
        actualizarCarrusel();
    });

    siguiente.addEventListener('click', function () {
        posicion++;
        actualizarCarrusel();
    });

    window.addEventListener('resize', actualizarCarrusel);

    actualizarCarrusel();
</script>

</body>
</html>
