@php
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// Ambil slug dari halaman saat ini (misal: /news/oke-gas)
$currentSlug = request()->route('slug');

// Ambil 5 video featured terbaru, kecuali yang sedang dibuka
$featuredVideos = News::where('featuredvideo', true)
    ->when($currentSlug, fn($q) => $q->where('slug', '!=', $currentSlug))
    ->where('status', 'published')
    ->latest()
    ->take(5)
    ->get();

/**
 * Helper function untuk convert Google Drive URL ke embed
 */
function convertGoogleDriveUrl($url) {
    // Pola umum file ID
    if (preg_match('/(?:file\/d\/|open\?id=|uc\?id=)([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
    }

    // Jika tidak cocok tapi ada domain drive.google.com
    if (strpos($url, 'drive.google.com') !== false) {
        // Kadang ID muncul di query string lain
        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
            if (!empty($query['id'])) {
                return 'https://drive.google.com/file/d/' . $query['id'] . '/preview';
            }
        }
    }

    return null;
}

/**
 * Helper function untuk convert OneDrive URL ke embed
 */
function convertOneDriveUrl($url) {
    // OneDrive share link pattern
    if (strpos($url, '1drv.ms') !== false || strpos($url, 'onedrive.live.com') !== false) {
        // Jika sudah embed URL, return as is
        if (strpos($url, 'embed') !== false) {
            return $url;
        }
        
        // Convert share link ke embed
        // Replace 'redir' with 'embed' atau tambahkan embed parameter
        $embedUrl = str_replace('redir?', 'embed?', $url);
        
        // Jika belum ada embed, tambahkan
        if (strpos($embedUrl, 'embed') === false) {
            $embedUrl = str_replace('onedrive.live.com/', 'onedrive.live.com/embed?', $embedUrl);
        }
        
        return $embedUrl;
    }
    
    return null;
}

/**
 * Helper function untuk convert YouTube URL ke embed
 */
function convertYouTubeUrl($url) {
    // YouTube patterns
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        $videoId = null;
        
        // Pattern: https://www.youtube.com/watch?v=VIDEO_ID
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Pattern: https://youtu.be/VIDEO_ID
        elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Pattern: https://www.youtube.com/embed/VIDEO_ID
        elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $url; // Sudah format embed
        }
        
        if ($videoId) {
            return 'https://www.youtube.com/embed/' . $videoId;
        }
    }
    
    return null;
}

/**
 * Deteksi tipe video dan return embed URL
 */
function getVideoEmbedUrl($url) {
    if (!$url) return null;
    
    // Cek YouTube
    $youtubeEmbed = convertYouTubeUrl($url);
    if ($youtubeEmbed) return ['type' => 'youtube', 'url' => $youtubeEmbed];
    
    // Cek Google Drive
    $driveEmbed = convertGoogleDriveUrl($url);
    if ($driveEmbed) return ['type' => 'google_drive', 'url' => $driveEmbed];
    
    // Cek OneDrive
    $onedriveEmbed = convertOneDriveUrl($url);
    if ($onedriveEmbed) return ['type' => 'onedrive', 'url' => $onedriveEmbed];
    
    // Default: direct video URL
    return ['type' => 'direct', 'url' => $url];
}
@endphp

<div class="pb-14 w-full">
    <div class="flex items-center border-b-2 border-gray-700 pb-2 mb-6">
        <h3 class="text-xl font-bold text-gray-800 uppercase">Video Unggulan</h3>
    </div>

    @if($featuredVideos->isNotEmpty())
        @foreach ($featuredVideos as $video)
            @php
                // URL detail
                $detailUrl = url('/news/' . $video->slug);
                $publishedAt = $video->published_at ? Carbon::parse($video->published_at)->format('M d, Y') : null;

                // Parse content untuk mencari video
                $contents = $video->content;
                if (is_string($contents)) {
                    $decoded = json_decode($contents, true);
                    $contents = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                }

                // Cari block video pertama
                $videoBlock = null;
                if (is_array($contents)) {
                    foreach ($contents as $block) {
                        if (isset($block['type']) && $block['type'] === 'video') {
                            $videoBlock = $block;
                            break;
                        }
                    }
                }

                $videoFile = $videoBlock['data']['url'] ?? null;
                $videoLink = $videoBlock['data']['url_link'] ?? null;
                
                // Prioritas: gunakan url_link dulu, baru url (file)
                $videoSource = $videoLink ?: $videoFile;
                $videoEmbed = $videoSource ? getVideoEmbedUrl($videoSource) : null;
            @endphp

            <a href="{{ $detailUrl }}"
                        >
                <div class="mb-6 shadow-lg rounded-lg overflow-hidden bg-gray-900 text-white w-full">
                    <div class="relative w-full">
                        @if ($videoEmbed)
                            @if ($videoEmbed['type'] === 'youtube')
                                {{-- YouTube Embed --}}
                                <div class="relative w-full" style="padding-bottom: 56.25%;">
                                    <iframe 
                                        class="absolute top-0 left-0 w-full h-full rounded-t-lg"
                                        src="{{ $videoEmbed['url'] }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        loading="lazy">
                                    </iframe>
                                </div>
                                
                            @elseif ($videoEmbed['type'] === 'google_drive')
                                {{-- Google Drive Embed --}}
                                <div class="relative w-full" style="padding-bottom: 56.25%;">
                                    <iframe 
                                        class="absolute top-0 left-0 w-full h-full rounded-t-lg"
                                        src="{{ $videoEmbed['url'] }}"
                                        frameborder="0"
                                        allow="autoplay; encrypted-media"
                                        allowfullscreen
                                        loading="lazy">
                                    </iframe>
                                </div>
                                
                            @elseif ($videoEmbed['type'] === 'onedrive')
                                {{-- OneDrive Embed --}}
                                <div class="relative w-full" style="padding-bottom: 56.25%;">
                                    <iframe 
                                        class="absolute top-0 left-0 w-full h-full rounded-t-lg"
                                        src="{{ $videoEmbed['url'] }}"
                                        frameborder="0"
                                        scrolling="no"
                                        allow="autoplay; encrypted-media"
                                        allowfullscreen
                                        loading="lazy">
                                    </iframe>
                                </div>
                                
                            @elseif ($videoEmbed['type'] === 'direct')
                                {{-- Direct Video File --}}
                                @if ($videoFile && !$videoLink)
                                    {{-- Video dari Storage --}}
                                    <video controls class="w-full h-64 object-cover bg-black rounded-t-lg">
                                        <source src="{{ asset('storage/' . $videoFile) }}" type="video/mp4">
                                        Browser Anda tidak mendukung tag video.
                                    </video>
                                @else
                                    {{-- Video dari URL eksternal --}}
                                    <video controls class="w-full h-64 object-cover bg-black rounded-t-lg">
                                        <source src="{{ $videoEmbed['url'] }}" type="video/mp4">
                                        Browser Anda tidak mendukung tag video.
                                    </video>
                                @endif
                            @endif
                            
                        @else
                            {{-- Fallback jika tidak ada video --}}
                            <div class="w-full h-64 bg-gray-800 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-video text-6xl text-gray-600 mb-3"></i>
                                    <p class="text-gray-500 text-sm">Video tidak tersedia</p>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Video Type Badge (Optional) --}}
                        @if ($videoEmbed && in_array($videoEmbed['type'], ['youtube', 'google_drive', 'onedrive', 'local']))
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center gap-1 rounded-full bg-black/70 px-2.5 py-1 text-xs font-medium text-white backdrop-blur-sm">
                                    @if ($videoEmbed['type'] === 'youtube')
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                        YouTube
                                    @elseif ($videoEmbed['type'] === 'google_drive')
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.01 1.485L2.984 17.438h5.659L17.67 1.485h-5.66zM16.643 19.72h5.659L18.315 12H12.66l3.983 7.72zM8.982 12L5.005 19.72h9.975L18.957 12H8.982z"/>
                                        </svg>
                                        Google Drive
                                    @elseif ($videoEmbed['type'] === 'onedrive')
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M13.8 12.4c-.4-.2-.9-.2-1.4-.2-2.5 0-4.6 2.1-4.6 4.6s2.1 4.6 4.6 4.6c1.8 0 3.3-1 4.1-2.5.6.2 1.3.3 1.9.3 3.3 0 6-2.7 6-6s-2.7-6-6-6c-.8 0-1.6.2-2.3.4-1.2-2.1-3.5-3.5-6.1-3.5-3.9 0-7 3.1-7 7 0 .7.1 1.4.3 2.1-2.5 1.1-4.2 3.6-4.2 6.5 0 3.9 3.1 7 7 7h8.1c.2-.8.3-1.7.3-2.6 0-2.1-.7-4-1.9-5.5z"/>
                                        </svg>
                                        OneDrive
                                    @elseif ($videoEmbed['type'] === 'local')
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4 4h16v16H4V4zm8 3.5l5 4.5-5 4.5V7.5z"/>
                                        </svg>
                                        Lokal
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 w-full">
                        <h5 class="pb-1">
                            <a href="{{ $detailUrl }}"
                            class="text-lg font-semibold text-white hover:text-gray-300 transition-colors line-clamp-2">
                                {{ $video->title }}
                            </a>
                        </h5>
                        <span class="text-gray-400 text-xs">
                            @if($video->author)
                                <span class="transition-colors">by {{ $video->author }}</span>
                                <span class="mx-1.5">-</span>
                            @endif
                            @if ($video->published_at)
                                <span>{{ \Carbon\Carbon::parse($video->published_at)->format('d M Y, H:i') }}</span>
                            @else
                                <span>—</span>
                            @endif
                        </span>
                    </div>
                </div>
                
             </a>

        @endforeach
    @else
        <p class="text-gray-500">Belum ada video unggulan.</p>
    @endif
</div>

<style>
    /* Responsive video container */
    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
    }
    
    .video-container iframe,
    .video-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>