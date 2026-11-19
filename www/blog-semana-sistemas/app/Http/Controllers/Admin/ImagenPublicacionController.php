<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImagenPublicacion;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImagenPublicacionController extends Controller
{
    public function index(Request $request)
    {
        $query = ImagenPublicacion::with('publicacion.categoria');

        if ($request->filled('publicacion_id')) {
            $query->where('publicacion_id', $request->publicacion_id);
        }

        $imagenes = $query
            ->orderByDesc('publicacion_id')
            ->orderByDesc('es_principal')
            ->orderBy('orden')
            ->orderBy('id')
            ->get();

        $publicaciones = Publicacion::orderBy('titulo')->get();

        return view('admin.imagenes.index', compact('imagenes', 'publicaciones'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'publicacion_id' => ['required', 'exists:publicaciones,id'],
            'imagenes' => ['required', 'array', 'min:1'],
            'imagenes.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'marcar_principal' => ['nullable', 'boolean'],
        ], [
            'publicacion_id.required' => 'Debes seleccionar la publicación a la que pertenecen las imágenes.',
            'publicacion_id.exists' => 'La publicación seleccionada no es válida.',
            'imagenes.required' => 'Debes seleccionar al menos una imagen para subir.',
            'imagenes.*.image' => 'Todos los archivos deben ser imágenes válidas.',
            'imagenes.*.mimes' => 'Formatos permitidos: JPG, JPEG, PNG o WEBP.',
            'imagenes.*.max' => 'Cada imagen debe pesar como máximo 4 MB.',
        ]);

        $rutasGuardadas = [];

        try {
            DB::transaction(function () use ($request, $datos, &$rutasGuardadas) {
                $publicacion = Publicacion::findOrFail($datos['publicacion_id']);
                $forzarPrincipal = $request->boolean('marcar_principal');
                $tienePrincipal = $publicacion->imagenes()->where('es_principal', true)->exists();

                if ($forzarPrincipal && $tienePrincipal) {
                    $publicacion->imagenes()
                        ->where('es_principal', true)
                        ->update(['es_principal' => false]);
                    $tienePrincipal = false;
                }

                $siguienteOrden = ((int) $publicacion->imagenes()->max('orden')) + 1;

                foreach ($request->file('imagenes') as $indice => $archivo) {
                    $ruta = $archivo->store('publicaciones', 'public');
                    $rutasGuardadas[] = $ruta;

                    $esPrincipal = (!$tienePrincipal && $indice === 0);

                    ImagenPublicacion::create([
                        'publicacion_id' => $publicacion->id,
                        'ruta' => $ruta,
                        'descripcion' => $datos['descripcion'] ?? null,
                        'es_principal' => $esPrincipal,
                        'orden' => $siguienteOrden++,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            foreach ($rutasGuardadas as $ruta) {
                Storage::disk('public')->delete($ruta);
            }
            throw $e;
        }

        return redirect()
            ->route('admin.imagenes.index', ['publicacion_id' => $datos['publicacion_id']])
            ->with('success', 'Imágenes subidas y asociadas correctamente.');
    }

    public function update(Request $request, ImagenPublicacion $imagen)
    {
        $datos = $request->validate([
            'archivo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'orden' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'archivo.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'archivo.mimes' => 'Formatos permitidos: JPG, JPEG, PNG o WEBP.',
            'archivo.max' => 'La imagen de reemplazo no debe superar los 4 MB.',
            'orden.integer' => 'El orden debe ser un número entero.',
        ]);

        $nuevaRuta = null;

        try {
            DB::transaction(function () use ($request, $datos, $imagen, &$nuevaRuta) {
                if ($request->hasFile('archivo')) {
                    $nuevaRuta = $request->file('archivo')->store('publicaciones', 'public');
                    $rutaAnterior = $imagen->ruta;

                    $imagen->ruta = $nuevaRuta;

                    if ($rutaAnterior) {
                        Storage::disk('public')->delete($rutaAnterior);
                    }
                }

                $imagen->descripcion = $datos['descripcion'] ?? null;

                if (isset($datos['orden'])) {
                    $imagen->orden = $datos['orden'];
                }

                $imagen->save();
            });
        } catch (\Throwable $e) {
            if ($nuevaRuta) {
                Storage::disk('public')->delete($nuevaRuta);
            }
            throw $e;
        }

        return back()->with('success', 'Imagen actualizada correctamente.');
    }

    public function marcarPrincipal(ImagenPublicacion $imagen)
    {
        DB::transaction(function () use ($imagen) {
            ImagenPublicacion::where('publicacion_id', $imagen->publicacion_id)
                ->where('es_principal', true)
                ->update(['es_principal' => false]);

            $imagen->update(['es_principal' => true]);
        });

        return back()->with('success', 'Imagen principal actualizada.');
    }

    public function destroy(ImagenPublicacion $imagen)
    {
        DB::transaction(function () use ($imagen) {
            $publicacionId = $imagen->publicacion_id;
            $eraPrincipal = $imagen->es_principal;
            $ruta = $imagen->ruta;

            $imagen->delete();

            if ($ruta) {
                Storage::disk('public')->delete($ruta);
            }

            if ($eraPrincipal) {
                $siguiente = ImagenPublicacion::where('publicacion_id', $publicacionId)
                    ->orderBy('orden')
                    ->orderBy('id')
                    ->first();

                if ($siguiente) {
                    $siguiente->update(['es_principal' => true]);
                }
            }
        });

        return back()->with('success', 'Imagen eliminada del sistema y del almacenamiento.');
    }
}