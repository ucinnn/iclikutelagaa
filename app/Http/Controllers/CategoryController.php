<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Ambil semua news yang memiliki kategori ini
        $news = $category->news()
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('filament.category.show', compact('category', 'news'));
    }
}
