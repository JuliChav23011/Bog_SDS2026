<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Mostrar formulario de inicio de sesión
    public function mostrarLogin()
    {
        return view('outh.login');
    }

    // Iniciar sesión
    public function login(Request $request)
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($datos)) {
            return back()
                ->withErrors([
                    'email' => 'Correo o contraseña incorrectos.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if (Auth::user()->isAdmin()) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/publicaciones');
    }

    // Mostrar formulario de registro
    public function mostrarRegistro()
    {
        return view('outh.registro');
    }

    // Registrar usuario normal
    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => $datos['password'],
        ]);

        Auth::login($usuario);

        $request->session()->regenerate();

        return redirect('/publicaciones');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
