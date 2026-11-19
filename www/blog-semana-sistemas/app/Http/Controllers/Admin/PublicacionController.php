<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\ImagenPublicacion;
use App\Models\Publicacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Publicacion::with([
            'categoria',
            'autores',
            'imagenes' => fn ($q) => $q->orderByDesc('es_principal')->orderBy('orden'),
        ]);

        if ($request->filled('buscar')) {
            $termino = '%' . mb_strtolower(trim($request->buscar)) . '%';
            $query->whereRaw('LOWER(titulo) LIKE ?', [$termino]);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $publicaciones = $query
            ->orderByDesc('fecha_actividad')
            ->orderByDesc('id')
            ->get();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.publicaciones.index', compact('publicaciones', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('admin.publicaciones.create', compact('categorias', 'usuarios'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'fecha_actividad' => ['required', 'date'],
            'resumen' => ['nullable', 'string', 'max:300'],
            'contenido' => ['required', 'string'],
            'estado' => ['required', 'in:borrador,pendiente,publicado'],
            'autores' => ['required', 'array', 'min:1'],
            'autores.*' => ['exists:usuarios,id'],
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'titulo.required' => 'El título de la publicación es obligatorio.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'fecha_actividad.required' => 'La fecha de la actividad es obligatoria.',
            'contenido.required' => 'El contenido de la publicación es obligatorio.',
            'autores.required' => 'Debes seleccionar al menos un autor.',
            'imagenes.*.image' => 'Todos los archivos adjuntos deben ser imágenes válidas.',
            'imagenes.*.mimes' => 'Las imágenes deben ser formato JPG, PNG o WEBP.',
            'imagenes.*.max' => 'Cada imagen no debe superar los 4 MB.',
        ]);

        DB::transaction(function () use ($request, $datos) {
            $publicacion = Publicacion::create([
                'categoria_id' => $datos['categoria_id'],
                'titulo' => trim($datos['titulo']),
                'slug' => $this->generarSlugUnico($datos['titulo']),
                'fecha_actividad' => $datos['fecha_actividad'],
                'resumen' => $datos['resumen'] ?? null,
                'contenido' => $datos['contenido'],
                'estado' => $datos['estado'],
                'fecha_publicacion' => $datos['estado'] === 'publicado' ? now() : null,
            ]);

            $publicacion->autores()->sync($datos['autores']);

            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $indice => $archivo) {
                    $ruta = $archivo->store('publicaciones', 'public');

                    ImagenPublicacion::create([
                        'publicacion_id' => $publicacion->id,
                        'ruta' => $ruta,
                        'es_principal' => $indice === 0,
                        'orden' => $indice + 1,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.publicaciones.index')
            ->with('success', 'Publicación creada correctamente.');
    }

    public function edit(Publicacion $publicacion)
    {
        $publicacion->load([
            'autores',
            'imagenes' => fn ($q) => $q->orderByDesc('es_principal')->orderBy('orden')->orderBy('id'),
        ]);

        $categorias = Categoria::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('admin.publicaciones.edit', compact('publicacion', 'categorias', 'usuarios'));
    }

    public function update(Request $request, Publicacion $publicacion)
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'fecha_actividad' => ['required', 'date'],
            'resumen' => ['nullable', 'string', 'max:300'],
            'contenido' => ['required', 'string'],
            'estado' => ['required', 'in:borrador,pendiente,publicado'],
            'autores' => ['required', 'array', 'min:1'],
            'autores.*' => ['exists:usuarios,id'],
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'reemplazos' => ['nullable', 'array'],
            'reemplazos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'eliminar_imagenes' => ['nullable', 'array'],
            'eliminar_imagenes.*' => ['integer'],
        ], [
            'titulo.required' => 'El título de la publicación es obligatorio.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'fecha_actividad.required' => 'La fecha de la actividad es obligatoria.',
            'contenido.required' => 'El contenido de la publicación es obligatorio.',
            'autores.required' => 'Debes seleccionar al menos un autor.',
            'imagenes.*.mimes' => 'Las imágenes nuevas deben ser JPG, PNG o WEBP.',
            'reemplazos.*.mimes' => 'Las imágenes de reemplazo deben ser JPG, PNG o WEBP.',
        ]);

        DB::transaction(function () use ($request, $datos, $publicacion) {
            $fechaPublicacion = $datos['estado'] === 'publicado'
                ? ($publicacion->fecha_publicacion ?? now())
                : null;

            $publicacion->update([
                'categoria_id' => $datos['categoria_id'],
                'titulo' => trim($datos['titulo']),
                'slug' => $this->generarSlugUnico($datos['titulo'], $publicacion->id),
                'fecha_actividad' => $datos['fecha_actividad'],
                'resumen' => $datos['resumen'] ?? null,
                'contenido' => $datos['contenido'],
                'estado' => $datos['estado'],
                'fecha_publicacion' => $fechaPublicacion,
            ]);

            $publicacion->autores()->sync($datos['autores']);

            // 1. Eliminar imágenes marcadas
            $idsEliminar = $request->input('eliminar_imagenes', []);
            if (!empty($idsEliminar)) {
                $imagenesBorrar = $publicacion->imagenes()->whereIn('id', $idsEliminar)->get();
                foreach ($imagenesBorrar as $img) {
                    Storage::disk('public')->delete($img->ruta);
                    $img->delete();
                }
            }

            // 2. Reemplazar imágenes existentes
            $reemplazos = $request->file('reemplazos') ?? [];
            foreach ($reemplazos as $imagenId => $archivo) {
                if (in_array((int) $imagenId, array_map('intval', $idsEliminar), true)) {
                    continue;
                }

                $imagenExistente = $publicacion->imagenes()->where('id', $imagenId)->first();
                if ($imagenExistente && $archivo->isValid()) {
                    Storage::disk('public')->delete($imagenExistente->ruta);
                    $nuevaRuta = $archivo->store('publicaciones', 'public');
                    $imagenExistente->update(['ruta' => $nuevaRuta]);
                }
            }

            // 3. Agregar nuevas imágenes
            if ($request->hasFile('imagenes')) {
                $siguienteOrden = ((int) $publicacion->imagenes()->max('orden')) + 1;
                foreach ($request->file('imagenes') as $archivo) {
                    $ruta = $archivo->store('publicaciones', 'public');
                    ImagenPublicacion::create([
                        'publicacion_id' => $publicacion->id,
                        'ruta' => $ruta,
                        'es_principal' => false,
                        'orden' => $siguienteOrden++,
                    ]);
                }
            }

            // 4. Garantizar que exista una imagen principal si quedan imágenes (respeta índice único de PostgreSQL)
            $tienePrincipal = $publicacion->imagenes()->where('es_principal', true)->exists();
            if (!$tienePrincipal) {
                $primera = $publicacion->imagenes()->orderBy('orden')->orderBy('id')->first();
                if ($primera) {
                    $primera->update(['es_principal' => true]);
                }
            }
        });

        return redirect()
            ->route('admin.publicaciones.index')
            ->with('success', 'Publicación actualizada correctamente.');
    }

    public function destroy(Publicacion $publicacion)
    {
        DB::transaction(function () use ($publicacion) {
            foreach ($publicacion->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen->ruta);
            }

            foreach ($publicacion->evidencias as $evidencia) {
                Storage::disk('public')->delete($evidencia->ruta);
            }

            $publicacion->delete();
        });

        return redirect()
            ->route('admin.publicaciones.index')
            ->with('success', 'Publicación y archivos asociados eliminados correctamente.');
    }

    private function generarSlugUnico(string $titulo, ?int $ignorarId = null): string
    {
        $slugBase = Str::slug($titulo) ?: 'publicacion';
        $slug = $slugBase;
        $contador = 1;

        while (
            Publicacion::where('slug', $slug)
                ->when($ignorarId, fn ($q) => $q->where('id', '!=', $ignorarId))
                ->exists()
        ) {
            $slug = $slugBase . '-' . $contador++;
        }

        return $slug;
    }
}