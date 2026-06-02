<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use App\Models\Tags;

class SideSection extends Component
{
    public $tags;

    public function __construct()
    {
        // Bisa langsung di constructor, tapi pastikan tidak berat
        $this->tags = Tags::orderBy('name')->get();
    }

    public function render()
    {
        return view('components.layouts.rightsidesection', [
            'tags' => $this->tags
        ]);
    }
}
