<?php
namespace App\Http\Controllers;

use App\Mail\WistleBlowingAdminMail;
use App\Mail\WistleBlowingUserMail;
use App\Models\User;
use App\Models\WistleBlowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class WistleBlowingController extends Controller
{
    public function index()
    {
        $reports = WistleBlowing::where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('filament.wistleblowing', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string',
            'category'    => 'required|string',
            'division'    => 'required|string|max:255',
            'description' => 'required|string',
            'proof.*'     => 'nullable|file|max:512000',
            'links.*'     => 'nullable|string|max:2048',
        ]);

        if ($request->hasFile('proof')) {
            $totalSize = array_sum(
                array_map(fn($file) => $file->getSize(), $request->file('proof'))
            );
            if ($totalSize > 500 * 1024 * 1024) {
                return back()
                    ->withErrors(['proof' => 'Total ukuran file tidak boleh melebihi 500MB.'])
                    ->withInput();
            }
        }

        $paths = [];
        if ($request->hasFile('proof')) {
            foreach ($request->file('proof') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs(
                    'wistle-blowing/proofs',
                    time() . '_' . $originalName,
                    'public'
                );
                $paths[] = ['path' => $path, 'name' => $originalName];
            }
        }

        $links = collect($request->input('links', []))
            ->filter(fn($l) => !empty(trim($l ?? '')))
            ->values()
            ->toArray();

        $report = WistleBlowing::create([
            'user_id'     => Auth::id(),
            'subject'     => $request->subject,
            'category'    => $request->category,
            'division'    => $request->division,
            'description' => $request->description,
            'proof'       => !empty($paths) ? $paths : null,
            'links'       => !empty($links) ? $links : null,
        ]);

        $report->load('user');

        $admins = User::where('role', 'superadmin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new WistleBlowingAdminMail($report));
        }

        if ($report->user && $report->user->email) {
            Mail::to($report->user->email)->send(new WistleBlowingUserMail($report));
        }

        return back()->with('success', 'Laporan berhasil dikirim. Konfirmasi telah dikirim ke email Anda.');
    }

    // ✅ Tambahkan method ini
    public function update(Request $request, WistleBlowing $wistleBlowing)
    {
        $request->validate([
            'status' => 'required|in:pending,process,resolved,rejected',
        ]);

        $wistleBlowing->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
    
    
public function downloadZip($id)
{
    $report = WistleBlowing::findOrFail($id);

    $files = $report->proof ?? [];

    if (empty($files)) {
        return back()->withErrors(['proof' => 'Tidak ada file yang bisa didownload.']);
    }

    $zipFileName = 'bukti-laporan-' . $report->id . '.zip';
    $zipPath     = storage_path('app/temp/' . $zipFileName);

    // Buat folder temp jika belum ada
    if (!file_exists(storage_path('app/temp'))) {
        mkdir(storage_path('app/temp'), 0755, true);
    }

    $zip = new \ZipArchive();

    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
        return back()->withErrors(['proof' => 'Gagal membuat file ZIP.']);
    }

    foreach ($files as $file) {
        $filePath = is_array($file) ? ($file['path'] ?? '') : $file;
        $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($file);

        // Hapus prefix 'storage/' jika ada karena Storage::disk('public') sudah relatif
        $cleanPath   = preg_replace('#^storage/#', '', $filePath);
        $absolutePath = storage_path('app/public/' . $cleanPath);

        if (file_exists($absolutePath)) {
            $zip->addFile($absolutePath, $fileName);
        }
    }

    $zip->close();

    return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
}
}