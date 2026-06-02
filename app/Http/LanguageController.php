<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        $availableLangs = collect(config('filament-language-switcher.filament.locales', []))
            ->pluck('code')
            ->toArray();

        if (empty($availableLangs)) {
            $availableLangs = config('filament-language-switcher.supported_locales', ['en', 'id', 'zh']);
        }

        // Validasi bahasa
        if (!in_array($lang, $availableLangs)) {
            abort(404, 'Bahasa tidak tersedia');
        }

        // Simpan ke session dan langsung terapkan
        Session::put('locale', $lang);
        App::setLocale($lang);

        return Redirect::back()->with('status', __('Bahasa berhasil diubah ke :lang', ['lang' => $lang]));
    }
}
