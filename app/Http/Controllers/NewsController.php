<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsController extends Controller
{
    /**
     * Tampilkan daftar berita terbaru.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $newsQuery = News::query()
            ->latest();

        if ($search) {
            $newsQuery->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        }
        // Ambil semua kategori dengan count berita (opsional)
        $categories = Category::withCount('news')
            ->orderBy('name')
            ->get();

        // Query berita dengan filter kategori
        $query = News::with('category')
            ->latest();

        // Filter berdasarkan kategori jika ada
        if ($request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter berdasarkan search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $news = $query->paginate(12);

        return view('filament.news.index', compact('news', 'search', 'categories'));
    }

    public function search(Request $request)
    {
        $query = trim($request->input('search'));

        // Ambil kategori untuk sidebar/filter
        $categories = Category::withCount('news')->orderBy('name')->get();

        // --- 🔍 PENCARIAN BERITA ---
        $newsQuery = News::with(['category', 'tags', 'user'])
            ->latest();

        // Jika ada kolom 'status' dan hanya tampilkan yang published
        if (Schema::hasColumn('news', 'status')) {
            $newsQuery->where('status', 'published');
        }

        // Jika ada kata kunci
        if (!empty($query)) {
            $newsQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");

                if (Schema::hasColumn('news', 'author')) {
                    $q->orWhere('author', 'like', "%{$query}%");
                }

                // 🔥 Pencarian berdasarkan tags
                $q->orWhereHas('tags', function ($tagQuery) use ($query) {
                    $tagQuery->where('name', 'like', "%{$query}%");
                });

                // 🔥 Pencarian berdasarkan kategori
                $q->orWhereHas('category', function ($catQuery) use ($query) {
                    $catQuery->where('name', 'like', "%{$query}%");
                });
            });
        }

        // Pagination bawaan (tidak perlu gabungan manual)
        $news = $newsQuery->paginate(9)->appends(['search' => $query]);

        // 🔍 Cari tag yang namanya mirip
        $tags = \App\Models\Tags::where('name', 'like', "%{$query}%")->get();

        // Kosongkan page (kalau belum ada modelnya)
        $pages = collect();

        return view('filament.news.search', [
            'query' => $query,
            'categories' => $categories,
            'news' => $news,   // ✅ penting: ganti jadi $news
            'tags' => $tags,
            'pages' => $pages,
        ]);
    }


    /**
     * Tampilkan detail berita berdasarkan slug.
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)->first();

        $content = $news->content;
        $blocks = $content['blocks'] ?? [];

        // Ambil thumbnail dari gambar pertama kalau belum ada
        if (empty($news->thumbnail)) {
            foreach ($blocks as $block) {
                if (($block['type'] ?? '') === 'image') {
                    $data = $block['data'] ?? [];
                    $url = $data['url_link'] ?? $data['url'] ?? null;
                    if ($url) {
                        $news->thumbnail = $url;
                        break;
                    }
                }
            }
        }

        if (! $news) {
            abort(404, 'Berita tidak ditemukan');
        }

        // ✅ Cegah penambahan views berulang dari user yang sama dalam waktu singkat
        $viewKey = 'news_viewed_' . $news->id . '_' . request()->ip();

        if (!Cache::has($viewKey)) {
            $news->increment('views');
            Cache::put($viewKey, true, now()->addMinutes(60)); // hanya 1x per 60 menit per IP
        }

        // Ambil beberapa berita lain sebagai "related"
        $relatedNews = News::where('id', '!=', $news->id)
            ->latest()
            ->take(4)
            ->get();

        return view('filament.news.show', compact('news', 'relatedNews', 'blocks'));
    }

    public function featuredvideo()
    {
        // Ambil 1 video yang ditandai sebagai featured
        $video = News::where('featuredvideo', true)->latest()->first();

        return view('components.layout.featuredvideo', compact('video'));
    }
}
