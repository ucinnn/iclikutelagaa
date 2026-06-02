<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher extends Component
{
    public $locale;

    public function mount()
    {
        $this->locale = session('locale', config('app.locale'));
    }

    public function updatedLocale($value)
    {
        Session::put('locale', $value);
        App::setLocale($value);
        return redirect()->to(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}