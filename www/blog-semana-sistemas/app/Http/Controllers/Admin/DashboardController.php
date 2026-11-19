<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPublicaciones = DB::table('publicaciones')->count();
        $totalCategorias = DB::table('categorias')->count();
        $totalUsuarios = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalEvidencias = DB::table('evidencias')->count();

        $publicacionesRecientes = DB::table('publicaciones')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPublicaciones',
            'totalCategorias',
            'totalUsuarios',
            'totalAdmins',
            'totalEvidencias',
            'publicacionesRecientes'
        ));
    }
}