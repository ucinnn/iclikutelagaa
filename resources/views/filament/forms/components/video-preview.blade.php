@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    // Normalisasi input
    if (is_array($fileUrl)) {
        $fileUrl = $fileUrl[0] ?? null;
    }

    $fileUrl = $fileUrl ? Storage::url($fileUrl) : null;
    $linkUrl = $linkUrl ?? null;
    $videoSrc = $linkUrl ?: $fileUrl;
@endphp

@if ($videoSrc)
    @php
        $embedType = null;
        $embedUrl = null;

        // === YouTube ===
        if (Str::contains($videoSrc, ['youtube.com', 'youtu.be'])) {
            $embedType = 'youtube';
            $embedUrl = preg_replace(
                ['/.*youtube\.com\/watch\?v=/', '/.*youtu\.be\//'],
                'https://www.youtube.com/embed/',
                $videoSrc
            );
        }

        // === Google Drive ===
        elseif (Str::contains($videoSrc, 'drive.google.com')) {
            $embedType = 'drive';
            $fileId = null;

            // Format: /file/d/{id}/view
            if (preg_match('/\/file\/d\/([^\/\?]+)/', $videoSrc, $matches)) {
                $fileId = $matches[1];
            }
            // Format: open?id={id}
            elseif (preg_match('/[?&]id=([^&]+)/', $videoSrc, $matches)) {
                $fileId = $matches[1];
            }
            // Format: /d/{id}
            elseif (preg_match('/\/d\/([^\/\?]+)/', $videoSrc, $matches)) {
                $fileId = $matches[1];
            }

            if ($fileId) {
                // Gunakan link preview resmi dari Google
                $embedUrl = "https://drive.google.com/file/d/{$fileId}/preview";
            }
        }

        // === OneDrive ===
        elseif (Str::contains($videoSrc, ['1drv.ms', 'onedrive.live.com', 'sharepoint.com'])) {
            $embedType = 'onedrive';
            
            // Jika URL pendek 1drv.ms, perlu di-expand dulu atau gunakan langsung
            if (Str::contains($videoSrc, '1drv.ms')) {
                // Untuk 1drv.ms, gunakan URL langsung dengan parameter embed
                $embedUrl = str_replace('1drv.ms', 'onedrive.live.com/embed', $videoSrc);
            } 
            // Jika sudah format onedrive.live.com
            elseif (Str::contains($videoSrc, 'onedrive.live.com')) {
                // Ubah dari redir atau download ke embed
                $embedUrl = str_replace(['redir', 'download'], 'embed', $videoSrc);
                
                // Jika belum ada embed di path, tambahkan
                if (!Str::contains($embedUrl, '/embed')) {
                    $embedUrl = preg_replace('/\/[^\/]+\?/', '/embed?', $embedUrl);
                }
            }
            // Jika format sharepoint
            else {
                $embedUrl = $videoSrc;
            }
            
            // Pastikan parameter embed ada
            if (!Str::contains($embedUrl, 'embed=')) {
                $separator = Str::contains($embedUrl, '?') ? '&' : '?';
                $embedUrl .= $separator . 'embed=1&autoplay=0';
            }
        }

        // === File upload langsung ===
        else {
            $embedType = 'file';
            $embedUrl = $videoSrc;
        }
    @endphp

    <div class="mt-4 flex justify-center">
        @switch($embedType)
            @case('youtube')
                <iframe
                    width="80%"
                    height="460"
                    src="{{ $embedUrl }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    class="rounded-xl shadow-md">
                </iframe>
                @break

            @case('drive')
                <iframe
                    width="80%"
                    height="460"
                    src="{{ $embedUrl }}"
                    frameborder="0"
                    allow="autoplay; encrypted-media"
                    allowfullscreen
                    class="rounded-xl shadow-md">
                </iframe>
                <p class="text-xs text-gray-500 text-center mt-2">
                    Pastikan video Google Drive diset ke <strong>"Anyone with the link can view"</strong>.
                </p>
                @break

            @case('onedrive')
                <iframe
                    width="80%"
                    height="460"
                    src="{{ $embedUrl }}"
                    frameborder="0"
                    allowfullscreen
                    class="rounded-xl shadow-md">
                </iframe>
                <p class="text-xs text-gray-500 text-center mt-2">
                    Pastikan video OneDrive diset ke <strong>"Anyone with the link can view"</strong>.
                </p>
                @break

            @case('file')
                <video
                    width="80%"
                    height="460"
                    controls
                    class="rounded-xl shadow-md">
                    <source src="{{ $embedUrl }}" type="video/mp4">
                    Browser Anda tidak mendukung pemutar video.
                </video>
                @break

            @default
                <p class="text-gray-500 italic text-center mt-2">
                    Belum ada video yang dipilih atau diunggah.
                </p>
        @endswitch
    </div>
@else
    <p class="text-gray-500 italic text-center mt-2">
        Belum ada video yang dipilih atau diunggah.
    </p>
@endif