<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PopUpNews;
use App\Models\News;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $popups = PopUpNews::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_at')->orWhere('end_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->orderBy('start_at', 'asc')
            ->get();

        $news = News::latest()->take(5)->get();

        // Ambil artikel yang punya blok video
        $latestVideos = News::with('category')
            ->where('status', 'published')
            ->latest('published_at')
            ->get()
            ->filter(function ($news) {
                // Cek apakah content sudah array atau masih string
                $content = is_string($news->content)
                    ? json_decode($news->content, true)
                    : $news->content;

                // Pastikan content adalah array yang valid
                if (is_array($content)) {
                    return collect($content)->contains(function ($block) {
                        return isset($block['type']) && $block['type'] === 'video';
                    });
                }

                return false;
            })
            ->take(8);

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

        $latestArticles = News::where('status', 'published')
            ->latest('published_at')
            ->take(6)
            ->get();

        // Ambil artikel terbaru sebagai artikel utama
        $mainArticle = News::with('category')
            ->orderByDesc('published_at')
            ->first();

        // Ambil 3 artikel berikutnya sebagai artikel kecil
        $smallArticles = News::with('category')
            ->orderByDesc('published_at')
            ->skip(1)
            ->take(3)
            ->get();

        // Filter berdasarkan search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        return view('livewire.show-home', compact('popups', 'news',  'mainArticle', 'smallArticles', 'latestVideos', 'categories', 'latestArticles'));
    }
}
