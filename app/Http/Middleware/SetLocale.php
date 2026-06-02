<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Pastikan session aktif
            if (!method_exists($request, 'hasSession') || !$request->hasSession()) {
                return $next($request);
            }

            // Ambil locale dari parameter URL ?lang=id
            $locale = $request->get('lang');

            // Jika tidak ada di URL, ambil dari session
            if (!$locale) {
                $locale = Session::get('locale', config('app.locale'));
            }

            // Ambil daftar bahasa valid dari config plugin
            $availableLocales = collect(config('filament-language-switcher.filament.locales', []))
                ->pluck('code')
                ->toArray();

            if (empty($availableLocales)) {
                $availableLocales = config('filament-language-switcher.supported_locales', ['en', 'id', 'zh']);
            }

            // Jika locale tidak valid, fallback ke default
            if (!in_array($locale, $availableLocales)) {
                $locale = config('app.locale');
            }

            // Set dan simpan locale
            App::setLocale($locale);
            Session::put('locale', $locale);
        } catch (\Throwable $e) {
            // Abaikan error agar tidak ganggu request
        }

        return $next($request);
    }
}
