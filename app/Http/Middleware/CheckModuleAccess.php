<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     */

    protected array $allowedRoles = ['superadmin', 'admin', 'author'];

    public function handle(Request $request, Closure $next)
    {
        // Jika pengguna tidak login, lanjutkan ke middleware autentikasi
        if (!Auth::check()) {
            return $next($request);
        }

        // $user = Filament::auth()->user();

        // $allowedRoles = ['superadmin', 'admin', 'author'];
        // // Validasi akses ke modul Admin
        // if ($user->role !== $allowedRoles) {
        //     Auth::logout();
        //     return redirect()->route('/')
        //         ->with('error', 'Anda tidak memiliki akses ke modul Admin.');
        // }

        return $next($request);
    }
}