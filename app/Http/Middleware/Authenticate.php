<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Silakan login terlebih dahulu.',
            ]);
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek jika mencoba mengakses halaman admin
        if ($request->is('admin/*') || $request->is('admin')) {
            // Hanya role tertentu yang boleh
            if (!in_array($user->role, ['admin', 'superadmin', 'author'])) {
                return redirect()->route('home')->withErrors([
                    'access' => 'Anda tidak memiliki izin untuk mengakses modul admin.',
                ]);
            }
        }

        return $next($request);
    }
}
