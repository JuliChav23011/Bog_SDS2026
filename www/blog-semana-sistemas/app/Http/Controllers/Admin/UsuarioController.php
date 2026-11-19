<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['publicaciones', 'evidencias']);

        if ($request->filled('buscar')) {
            $termino = '%' . mb_strtolower(trim($request->buscar)) . '%';
            $query->where(function ($q) use ($termino) {
                $q->whereRaw('LOWER(name) LIKE ?', [$termino])
                  ->orWhereRaw('LOWER(email) LIKE ?', [$termino]);
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $usuarios = $query
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email'],
            'role' => ['required', 'in:user,admin'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre del usuario es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Ya existe una cuenta registrada con ese correo.',
            'role.in' => 'El rol seleccionado no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        User::create([
            'name' => trim($datos['name']),
            'email' => mb_strtolower(trim($datos['email'])),
            'role' => $datos['role'],
            'password' => Hash::make($datos['password']),
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email,' . $usuario->id],
            'role' => ['required', 'in:user,admin'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre del usuario es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Ya existe otro usuario con ese correo electrónico.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        if ($usuario->id === auth()->id() && $datos['role'] !== 'admin') {
            return back()
                ->withInput()
                ->with('error', 'No puedes quitarte a ti misma el rol de administradora.');
        }

        if ($usuario->role === 'admin' && $datos['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()
                ->withInput()
                ->with('error', 'Debe existir al menos un usuario con rol de administrador en el sistema.');
        }

        $camposActualizar = [
            'name' => trim($datos['name']),
            'email' => mb_strtolower(trim($datos['email'])),
            'role' => $datos['role'],
        ];

        if (!empty($datos['password'])) {
            $camposActualizar['password'] = Hash::make($datos['password']);
        }

        $usuario->update($camposActualizar);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function cambiarRol(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes cambiar tu propio rol de administradora.');
        }

        $nuevoRol = $usuario->role === 'admin' ? 'user' : 'admin';

        if ($usuario->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'No se puede degradar al único administrador activo.');
        }

        $usuario->update(['role' => $nuevoRol]);

        return back()->with('success', "Rol de {$usuario->name} actualizado a «{$nuevoRol}».");
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta mientras tienes sesión iniciada.');
        }

        if ($usuario->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'No se puede eliminar al único administrador del sistema.');
        }

        if ($usuario->publicaciones()->exists() || $usuario->evidencias()->exists()) {
            return back()->with('error', "No se puede eliminar a {$usuario->name} porque tiene publicaciones o evidencias asociadas.");
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}