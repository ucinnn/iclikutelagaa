<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use App\Helpers\HtmlHelper;

class FaqController extends Controller
{
    /**
     * Display FAQ page
     */
    public function index()
    {
        // Ambil semua FAQ, dikelompokkan berdasarkan kategori
        $faqs = FAQ::orderBy('category')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category');

        // Hitung total FAQ
        $totalFaqs = FAQ::count();

        return view('filament.faq', compact('faqs', 'totalFaqs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        // Purify sebelum save
        $validated['answer'] = HtmlHelper::purify($validated['answer']);

        Faq::create($validated);

        return redirect()->back();
    }

    /**
     * Search FAQ via AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $results = FAQ::where('question', 'like', "%{$query}%")
            ->orWhere('answer', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->get()
            ->groupBy('category');

        return response()->json([
            'success' => true,
            'results' => $results,
            'count' => $results->flatten()->count(),
        ]);
    }
}