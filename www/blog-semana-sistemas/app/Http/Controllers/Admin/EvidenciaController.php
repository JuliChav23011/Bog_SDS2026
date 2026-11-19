<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evidencia;
use App\Models\Publicacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EvidenciaController extends Controller
{
    public function index(Request $request)
    {
        $query = Evidencia::with(['publicacion.categoria', 'usuario']);

        if ($request->filled('publicacion_id')) {
            $query->where('publicacion_id', $request->publicacion_id);
        }

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        $evidencias = $query
            ->orderByDesc('fecha_evidencia')
            ->orderByDesc('id')
            ->get();

        $publicaciones = Publicacion::orderBy('titulo')->get();
        $usuarios = User::orderBy('name')->get();

        return view('admin.evidencias.index', compact('evidencias', 'publicaciones', 'usuarios'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'publicacion_id' => ['required', 'exists:publicaciones,id'],
            'usuario_id' => ['required', 'exists:usuarios,id'],
            'fecha_evidencia' => ['required', 'date'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'archivos' => ['required', 'array', 'min:1'],
            'archivos.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], [
            'publicacion_id.required' => 'Debes seleccionar una publicación asociada.',
            'publicacion_id.exists' => 'La publicación seleccionada no es válida.',
            'usuario_id.required' => 'Debes indicar el usuario responsable de la evidencia.',
            'usuario_id.exists' => 'El usuario seleccionado no es válido.',
            'fecha_evidencia.required' => 'La fecha de la evidencia es obligatoria.',
            'archivos.required' => 'Debes adjuntar al menos un archivo de evidencia.',
            'archivos.*.mimes' => 'Formatos permitidos: JPG, JPEG, PNG, WEBP o PDF.',
            'archivos.*.max' => 'Cada archivo no debe superar los 5 MB.',
        ]);

        $rutasGuardadas = [];

        try {
            DB::transaction(function () use ($request, $datos, &$rutasGuardadas) {
                foreach ($request->file('archivos') as $archivo) {
                    $ruta = $archivo->store('evidencias', 'public');
                    $rutasGuardadas[] = $ruta;

                    Evidencia::create([
                        'publicacion_id' => $datos['publicacion_id'],
                        'usuario_id' => $datos['usuario_id'],
                        'fecha_evidencia' => $datos['fecha_evidencia'],
                        'descripcion' => $datos['descripcion'] ?? null,
                        'ruta' => $ruta,
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
            ->route('admin.evidencias.index', ['publicacion_id' => $datos['publicacion_id']])
            ->with('success', 'Evidencia(s) registrada(s) correctamente.');
    }

    public function update(Request $request, Evidencia $evidencia)
    {
        $datos = $request->validate([
            'usuario_id' => ['required', 'exists:usuarios,id'],
            'fecha_evidencia' => ['required', 'date'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'archivo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], [
            'usuario_id.required' => 'El usuario responsable es obligatorio.',
            'fecha_evidencia.required' => 'La fecha de la evidencia es obligatoria.',
            'archivo.mimes' => 'El archivo de reemplazo debe ser JPG, JPEG, PNG, WEBP o PDF.',
            'archivo.max' => 'El archivo de reemplazo no debe superar los 5 MB.',
        ]);

        $nuevaRuta = null;

        try {
            DB::transaction(function () use ($request, $datos, $evidencia, &$nuevaRuta) {
                if ($request->hasFile('archivo')) {
                    $nuevaRuta = $request->file('archivo')->store('evidencias', 'public');
                    $rutaAnterior = $evidencia->ruta;

                    $evidencia->ruta = $nuevaRuta;

                    if ($rutaAnterior) {
                        Storage::disk('public')->delete($rutaAnterior);
                    }
                }

                $evidencia->usuario_id = $datos['usuario_id'];
                $evidencia->fecha_evidencia = $datos['fecha_evidencia'];
                $evidencia->descripcion = $datos['descripcion'] ?? null;
                $evidencia->save();
            });
        } catch (\Throwable $e) {
            if ($nuevaRuta) {
                Storage::disk('public')->delete($nuevaRuta);
            }
            throw $e;
        }

        return back()->with('success', 'Evidencia actualizada correctamente.');
    }

    public function destroy(Evidencia $evidencia)
    {
        DB::transaction(function () use ($evidencia) {
            $ruta = $evidencia->ruta;
            $evidencia->delete();

            if ($ruta) {
                Storage::disk('public')->delete($ruta);
            }
        });

        return back()->with('success', 'Evidencia eliminada del sistema y del almacenamiento.');
    }
}