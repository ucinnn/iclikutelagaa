<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index($slug)
    {
        // Ambil satu tag berdasarkan slug
        $tag = Tags::where('slug', $slug)->firstOrFail();

        // Ambil berita yang berhubungan dengan tag tersebut
        $news = $tag->news()
            ->with('category')
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(9);

        // Ambil semua tag untuk sidebar
        $tags = Tags::orderBy('name')->get();

        // Kirim variabel ke view
        return view('filament.tags.index', compact('tag', 'tags', 'news'));
    }
}
