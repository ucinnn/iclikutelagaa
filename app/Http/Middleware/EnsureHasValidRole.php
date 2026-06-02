<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasValidRole
{
    /**
     * Role yang diizinkan untuk mengakses area admin Filament.
     */
    protected array $allowedRoles = ['superadmin', 'admin', 'author'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user();

        // Jika belum login atau tidak punya role yang valid → logout & arahkan ke home
        if (! $user || ! in_array($user->role, $this->allowedRoles)) {
            Filament::auth()->logout();
            return redirect('/')
                ->with('error', 'Anda tidak memiliki izin untuk mengakses area admin.');
        }

        // Jika user mencoba mengakses /admin tapi tidak berizin
        if ($request->is('admin*') && ! in_array($user->role, $this->allowedRoles)) {
            return redirect('/home')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}
