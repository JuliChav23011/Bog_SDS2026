<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('publicaciones')
            ->orderBy('nombre')
            ->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría registrada con ese nombre.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
        ]);

        Categoria::create([
            'nombre' => trim($datos['nombre']),
            'slug' => $this->generarSlugUnico($datos['nombre']),
            'descripcion' => $datos['descripcion'] ?? null,
        ]);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre,' . $categoria->id],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe otra categoría con ese nombre.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
        ]);

        $categoria->update([
            'nombre' => trim($datos['nombre']),
            'slug' => $this->generarSlugUnico($datos['nombre'], $categoria->id),
            'descripcion' => $datos['descripcion'] ?? null,
        ]);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->publicaciones()->exists()) {
            return redirect()
                ->route('admin.categorias.index')
                ->with('error', 'No se puede eliminar la categoría "' . $categoria->nombre . '" porque tiene publicaciones asociadas.');
        }

        $categoria->delete();

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    private function generarSlugUnico(string $nombre, ?int $ignorarId = null): string
    {
        $slugBase = Str::slug($nombre) ?: 'categoria';
        $slug = $slugBase;
        $contador = 1;

        while (
            Categoria::where('slug', $slug)
                ->when($ignorarId, fn ($q) => $q->where('id', '!=', $ignorarId))
                ->exists()
        ) {
            $slug = $slugBase . '-' . $contador++;
        }

        return $slug;
    }
}