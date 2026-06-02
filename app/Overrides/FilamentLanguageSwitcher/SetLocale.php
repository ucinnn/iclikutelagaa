<?php

namespace App\Overrides\FilamentLanguageSwitcher;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!method_exists($request, 'hasSession') || !$request->hasSession()) {
                return $next($request);
            }

            // Ambil locale dari URL ?lang=id jika ada
            $locale = $request->get('lang');

            // Kalau tidak ada dari URL, ambil dari session
            if (!$locale) {
                $locale = Session::get('locale', config('app.locale'));
            }

            // Ambil daftar locale valid
            $availableLocales = collect(config('filament-language-switcher.filament.locales', []))
                ->pluck('code')
                ->toArray();

            if (empty($availableLocales)) {
                $availableLocales = config('filament-language-switcher.supported_locales', []);
            }

            // Validasi locale
            if (!in_array($locale, $availableLocales)) {
                $locale = config('app.locale');
            }

            // Terapkan locale
            App::setLocale($locale);
            Session::put('locale', $locale);
        } catch (\Throwable $e) {
            // Tidak ganggu request
        }

        return $next($request);
    }
}
