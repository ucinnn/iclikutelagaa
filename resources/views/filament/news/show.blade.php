<x-layouts.app>
    @section('pageTitle', $news->title)

    <div class="container mx-auto px-4 py-6">
        <article class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('news.index') }}" class="hover:text-red-600 transition">Berita</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                @if ($news->category->isNotEmpty())
                    @foreach ($news->category as $cat)
                        <a href="{{ route('news.category.show', $cat->slug) }}" class="hover:text-red-600 transition">
                            {{ $cat->name }}
                        </a>
                        @if (!$loop->last)
                            <span class="mx-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    @endforeach
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                @endif
                <span class="text-gray-700 font-medium truncate">{{ Str::limit($news->title, 50) }}</span>
            </nav>

            {{-- Category Badge --}}
            @if ($news->category->isNotEmpty())
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    @foreach ($news->category as $cat)
                        <a href="{{ route('news.category.show', $cat->slug) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-semibold rounded-full hover:shadow-lg transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            @endif
            {{-- Judul --}}
            <h1 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900 leading-tight">{{ $news->title }}</h1>

            {{-- Info --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span
                        class="font-medium">{{ optional($news->published_at)->translatedFormat('d M Y') ?? $news->created_at->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">{{ $news->author ?? 'Admin' }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-5 h-5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>{{ $news->views }} kali dilihat</span>
                </div>
            </div>

        {{-- Thumbnail --}}
        @php
            $showThumbnail = false;

            if ($news->thumbnail) {
                $thumbnailUrl = $news->image_url;
                $showThumbnail = true;

                // Cek apakah thumbnail adalah logo
                if (Str::contains(strtolower($thumbnailUrl), 'logo')) {
                    $showThumbnail = false;
                }

                // Cek apakah thumbnail ada di dalam konten
                if ($showThumbnail) {
                    $contents = $news->content;

                    // Decode JSON jika berbentuk string
                    if (is_string($contents)) {
                        $decoded = json_decode($contents, true);
                        $contents = json_last_error() === JSON_ERROR_NONE ? $decoded : $contents;
                    }

                    if (is_array($contents)) {
                        // Normalize thumbnail URL untuk perbandingan
                        $thumbnailPath = parse_url($thumbnailUrl, PHP_URL_PATH);
                        $thumbnailFilename = basename($thumbnailPath);

                        foreach ($contents as $block) {
                            if (isset($block['type']) && $block['type'] === 'image') {
                                $contentImageUrl = $block['data']['url_link'] ?? ($block['data']['url'] ?? null);

                                if ($contentImageUrl) {
                                    // Normalize content image URL
                                    $contentImagePath = parse_url($contentImageUrl, PHP_URL_PATH);
                                    $contentImageFilename = basename($contentImagePath);

                                    // Bandingkan nama file (lebih reliable)
                                    if ($thumbnailFilename === $contentImageFilename) {
                                        $showThumbnail = false;
                                        break;
                                    }

                                    // Alternatif: bandingkan full path jika sama
                                    if (
                                        Str::contains($thumbnailUrl, $contentImageFilename) &&
                                        Str::contains($contentImageUrl, $contentImageFilename)
                                    ) {
                                        $showThumbnail = false;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        @endphp

        {{-- @if ($showThumbnail)
            <div class="mb-8 rounded-2xl overflow-hidden shadow-xl">
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-auto object-cover">
            </div>
        @endif --}}

            {{-- Konten --}}
            <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
                @php
                    $contents = $news->content;
                    if (is_string($contents)) {
                        $decoded = json_decode($contents, true);
                        $contents = json_last_error() === JSON_ERROR_NONE ? $decoded : $contents;
                    }
                @endphp

                @if (is_array($contents))
                    @foreach ($contents as $block)
                        @switch($block['type'])
                            @case('heading')
                                @php
                                    $level = $block['data']['level'] ?? 'h2';
                                    $text = $block['data']['text'] ?? '';
                                @endphp
                                <{{ $level }}
                                    class="font-bold mt-8 mb-4 text-gray-900
                                    {{ $level === 'h1' ? 'text-3xl' : '' }}
                                    {{ $level === 'h2' ? 'text-2xl' : '' }}
                                    {{ $level === 'h3' ? 'text-xl' : '' }}
                                    {{ $level === 'h4' ? 'text-lg' : '' }}">
                                    {{ $text }}
                                    </{{ $level }}>
                                @break

                                @case('paragraph')
                                    <div class="mb-4 text-gray-700 leading-relaxed">{!! $block['data']['text'] ?? '' !!}</div>
                                @break

                                @case('image')
                                    @php
                                        $imageFile = $block['data']['url'] ?? null;
                                        $imageLink = $block['data']['url_link'] ?? null;
                                        $alt = $block['data']['alt'] ?? 'Image';
                                        $alignment = $block['data']['alignment'] ?? 'center';
                                        $imageUrl = $imageLink ?: ($imageFile ? asset('storage/' . $imageFile) : null);

                                        // Untuk Google Drive, buat download URL
                                        $downloadUrl = $imageUrl;
                                        if (Str::contains($imageUrl, 'drive.google.com')) {
                                            $fileId = null;
                                            if (preg_match('/\/file\/d\/([^\/\?]+)/', $imageUrl, $matches)) {
                                                $fileId = $matches[1];
                                            } elseif (preg_match('/[?&]id=([^&]+)/', $imageUrl, $matches)) {
                                                $fileId = $matches[1];
                                            }
                                            if ($fileId) {
                                                $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
                                            }
                                        }
                                    @endphp

                                    @if ($imageUrl)
                                        <div
                                            class="my-8 flex justify-{{ $alignment === 'left' ? 'start' : ($alignment === 'right' ? 'end' : 'center') }}">
                                            <div class="w-full max-w-4xl">
                                                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                                                    {{-- Header dengan kontrol --}}
                                                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            <div>
                                                                <h4 class="font-semibold text-gray-900 text-xs">{{ $alt }}</h4>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            {{-- Tombol Buka di Tab Baru --}}
                                                            <a href="{{ $imageUrl }}" target="_blank"
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-300 text-xs font-medium">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                                    </path>
                                                                </svg>
                                                                Buka
                                                            </a>

                                                            {{-- Tombol Download --}}
                                                            <a href="{{ $downloadUrl }}"
                                                                onclick="event.preventDefault(); downloadFile('{{ $downloadUrl }}', '{{ $alt }}');"
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 hover:shadow-md transition-all duration-300 text-xs font-medium">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                                    </path>
                                                                </svg>
                                                                Download
                                                            </a>
                                                        </div>
                                                    </div>

                                                    {{-- Image Preview dengan tinggi tetap --}}
                                                    <div class="relative w-full h-[420px] overflow-hidden bg-gray-100">
                                                        <img src="{{ $imageUrl }}" alt="{{ $alt }}"
                                                            class="w-full h-full object-contain" loading="lazy">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @break

                                @case('video')
                                    @php
                                        $videoFile = $block['data']['url'] ?? null;
                                        $videoLink = $block['data']['url_link'] ?? null;
                                        $caption = $block['data']['caption'] ?? 'Video';
                                        $alignment = $block['data']['alignment'] ?? 'center';
                                        $videoSrc = $videoLink ?: ($videoFile ? asset('storage/' . $videoFile) : null);

                                        $embedType = null;
                                        $embedUrl = null;
                                        $originalUrl = $videoSrc;
                                        $downloadUrl = $videoSrc;

                                        if ($videoSrc) {
                                            // YouTube
                                            if (Str::contains($videoSrc, ['youtube.com', 'youtu.be'])) {
                                                $embedType = 'youtube';
                                                $embedUrl = preg_replace(
                                                    ['/.*youtube\.com\/watch\?v=/', '/.*youtu\.be\//'],
                                                    'https://www.youtube.com/embed/',
                                                    $videoSrc,
                                                );
                                            }
                                            // Google Drive
                                            elseif (Str::contains($videoSrc, 'drive.google.com')) {
                                                $embedType = 'drive';
                                                $fileId = null;

                                                if (preg_match('/\/file\/d\/([^\/\?]+)/', $videoSrc, $matches)) {
                                                    $fileId = $matches[1];
                                                } elseif (preg_match('/[?&]id=([^&]+)/', $videoSrc, $matches)) {
                                                    $fileId = $matches[1];
                                                } elseif (preg_match('/\/d\/([^\/\?]+)/', $videoSrc, $matches)) {
                                                    $fileId = $matches[1];
                                                }

                                                if ($fileId) {
                                                    $embedUrl = "https://drive.google.com/file/d/{$fileId}/preview";
                                                    $originalUrl = "https://drive.google.com/file/d/{$fileId}/view";
                                                    $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
                                                }
                                            }
                                            // OneDrive
                                            elseif (
                                                Str::contains($videoSrc, [
                                                    '1drv.ms',
                                                    'onedrive.live.com',
                                                    'sharepoint.com',
                                                ])
                                            ) {
                                                $embedType = 'onedrive';

                                                if (Str::contains($videoSrc, '1drv.ms')) {
                                                    $embedUrl = str_replace(
                                                        '1drv.ms',
                                                        'onedrive.live.com/embed',
                                                        $videoSrc,
                                                    );
                                                } elseif (Str::contains($videoSrc, 'onedrive.live.com')) {
                                                    $embedUrl = str_replace(['redir', 'download'], 'embed', $videoSrc);
                                                    if (!Str::contains($embedUrl, '/embed')) {
                                                        $embedUrl = preg_replace('/\/[^\/]+\?/', '/embed?', $embedUrl);
                                                    }
                                                } else {
                                                    $embedUrl = $videoSrc;
                                                }

                                                if (!Str::contains($embedUrl, 'embed=')) {
                                                    $separator = Str::contains($embedUrl, '?') ? '&' : '?';
                                                    $embedUrl .= $separator . 'embed=1&autoplay=0';
                                                }

                                                // OneDrive download URL
                                                $downloadUrl = str_replace('embed', 'download', $embedUrl);
                                            }
                                            // File lokal
                                            else {
                                                $embedType = 'file';
                                                $embedUrl = $videoSrc;
                                            }
                                        }
                                    @endphp

                                    @if ($embedUrl)
                                        <div class="my-8">
                                            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                                                {{-- Header dengan kontrol --}}
                                                <div
                                                    class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <svg class="w-5 h-5 text-red-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900 text-sm">
                                                                {{ $caption }}</h4>
                                                            <p class="text-xs text-gray-500 mt-0.5">
                                                                @if ($embedType === 'youtube')
                                                                    YouTube
                                                                @elseif($embedType === 'drive')
                                                                    Google Drive
                                                                @elseif($embedType === 'onedrive')
                                                                    OneDrive
                                                                @else
                                                                    Video
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center gap-2">
                                                        {{-- Tombol Buka --}}
                                                        <a href="{{ $originalUrl }}" target="_blank"
                                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-300 text-xs font-medium">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                                </path>
                                                            </svg>
                                                            Buka
                                                        </a>

                                                        {{-- Tombol Share (untuk semua platform) --}}
                                                        <button
                                                            onclick="navigator.clipboard.writeText('{{ $originalUrl }}'); alert('Link berhasil disalin!');"
                                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-300 text-xs font-medium">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                                                                </path>
                                                            </svg>
                                                            Share
                                                        </button>

                                                        {{-- Tombol Download --}}
                                                        @if (in_array($embedType, ['drive', 'onedrive', 'file']))
                                                            <a href="{{ $downloadUrl }}"
                                                                onclick="event.preventDefault(); downloadFile('{{ $downloadUrl }}', '{{ $caption }}');"
                                                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 hover:shadow-md transition-all duration-300 text-xs font-medium">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                                    </path>
                                                                </svg>
                                                                Download
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Video Preview --}}
                                                <div class="relative bg-gray-900">
                                                    @switch($embedType)
                                                        @case('youtube')
                                                            <iframe class="w-full aspect-video" src="{{ $embedUrl }}"
                                                                frameborder="0"
                                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                allowfullscreen>
                                                            </iframe>
                                                        @break

                                                        @case('drive')
                                                            <iframe class="w-full aspect-video" src="{{ $embedUrl }}"
                                                                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                                                            </iframe>
                                                            <p class="text-xs text-gray-400 text-center py-2 bg-gray-800">
                                                                Pastikan video Google Drive diset ke <strong>"Anyone with the link can
                                                                    view"</strong>
                                                            </p>
                                                        @break

                                                        @case('onedrive')
                                                            <iframe class="w-full aspect-video" src="{{ $embedUrl }}"
                                                                frameborder="0" allowfullscreen>
                                                            </iframe>
                                                            <p class="text-xs text-gray-400 text-center py-2 bg-gray-800">
                                                                Pastikan video OneDrive diset ke <strong>"Anyone with the link can
                                                                    view"</strong>
                                                            </p>
                                                        @break

                                                        @case('file')
                                                            <video controls class="w-full aspect-video">
                                                                <source src="{{ $embedUrl }}" type="video/mp4">
                                                                Browser Anda tidak mendukung pemutar video.
                                                            </video>
                                                        @break
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @break

                                @case('document')
                                    @php
                                        $docFile = $block['data']['url'] ?? null;
                                        $docLink = $block['data']['url_link'] ?? null;
                                        $name = $block['data']['name'] ?? 'Dokumen';
                                        $docUrl = $docLink ?: ($docFile ? asset('storage/' . $docFile) : null);

                                        $embedType = null;
                                        $embedUrl = null;
                                        $downloadUrl = $docUrl;

                                        if ($docUrl) {
                                            // Google Drive
                                            if (Str::contains($docUrl, 'drive.google.com')) {
                                                $embedType = 'drive';
                                                $fileId = null;

                                                if (preg_match('/\/file\/d\/([^\/\?]+)/', $docUrl, $matches)) {
                                                    $fileId = $matches[1];
                                                } elseif (preg_match('/[?&]id=([^&]+)/', $docUrl, $matches)) {
                                                    $fileId = $matches[1];
                                                } elseif (preg_match('/\/d\/([^\/\?]+)/', $docUrl, $matches)) {
                                                    $fileId = $matches[1];
                                                }

                                                if ($fileId) {
                                                    $embedUrl = "https://drive.google.com/file/d/{$fileId}/preview";
                                                    $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
                                                }
                                            }
                                            // OneDrive
                                            elseif (
                                                Str::contains($docUrl, [
                                                    '1drv.ms',
                                                    'onedrive.live.com',
                                                    'sharepoint.com',
                                                ])
                                            ) {
                                                $embedType = 'onedrive';

                                                if (Str::contains($docUrl, '1drv.ms')) {
                                                    $embedUrl = str_replace(
                                                        '1drv.ms',
                                                        'onedrive.live.com/embed',
                                                        $docUrl,
                                                    );
                                                } elseif (Str::contains($docUrl, 'onedrive.live.com')) {
                                                    $embedUrl = str_replace(['redir', 'download'], 'embed', $docUrl);
                                                    if (!Str::contains($embedUrl, '/embed')) {
                                                        $embedUrl = preg_replace('/\/[^\/]+\?/', '/embed?', $embedUrl);
                                                    }
                                                } else {
                                                    $embedUrl = $docUrl;
                                                }

                                                if (!Str::contains($embedUrl, 'embed=')) {
                                                    $separator = Str::contains($embedUrl, '?') ? '&' : '?';
                                                    $embedUrl .= $separator . 'action=embedview';
                                                }

                                                // OneDrive download URL
                                                $downloadUrl = str_replace(
                                                    ['embed', 'embedview'],
                                                    'download',
                                                    $embedUrl,
                                                );
                                            }
                                            // Dropbox
                                            elseif (Str::contains($docUrl, 'dropbox.com')) {
                                                $embedType = 'dropbox';
                                                $embedUrl = str_replace('dl=0', 'raw=1', $docUrl);
                                                $downloadUrl = str_replace('dl=0', 'dl=1', $docUrl);
                                            }
                                            // File lokal atau link langsung
                                            else {
                                                $embedType = 'file';
                                                $embedUrl = $docUrl;
                                            }
                                        }
                                    @endphp

                                    @if ($embedUrl)
                                        <div class="my-8">
                                            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                                                {{-- Header dengan nama file dan tombol download --}}
                                                <div
                                                    class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <svg class="w-6 h-6 text-red-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900">{{ $name }}</h4>
                                                            <p class="text-xs text-gray-500 mt-0.5">
                                                                @if ($embedType === 'drive')
                                                                    Google Drive
                                                                @elseif($embedType === 'onedrive')
                                                                    OneDrive
                                                                @elseif($embedType === 'dropbox')
                                                                    Dropbox
                                                                @else
                                                                    Dokumen
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center gap-2">
                                                        {{-- Tombol Lihat Fullscreen --}}
                                                        <a href="{{ $embedUrl }}" target="_blank"
                                                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-300 text-sm font-medium">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                                </path>
                                                            </svg>
                                                            Buka
                                                        </a>

                                                        {{-- Tombol Share --}}
                                                        <button
                                                            onclick="navigator.clipboard.writeText('{{ $docUrl }}'); alert('Link berhasil disalin!');"
                                                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-300 text-sm font-medium">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                                                                </path>
                                                            </svg>
                                                            Share
                                                        </button>

                                                        {{-- Tombol Download --}}
                                                        <a href="{{ $downloadUrl }}"
                                                            onclick="event.preventDefault(); downloadFile('{{ $downloadUrl }}', '{{ $name }}');"
                                                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 hover:shadow-md transition-all duration-300 text-sm font-medium">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                                </path>
                                                            </svg>
                                                            Download
                                                        </a>
                                                    </div>
                                                </div>

                                                {{-- Preview Area --}}
                                                <div class="relative bg-gray-50">
                                                    @if ($embedType === 'drive')
                                                        <iframe src="{{ $embedUrl }}" class="w-full h-[600px]"
                                                            frameborder="0" allow="autoplay">
                                                        </iframe>
                                                        <p class="text-xs text-gray-500 text-center py-3 bg-gray-100">
                                                            Pastikan dokumen Google Drive diset ke <strong>"Anyone with the link
                                                                can view"</strong>
                                                        </p>
                                                    @elseif($embedType === 'onedrive')
                                                        <iframe src="{{ $embedUrl }}" class="w-full h-[600px]"
                                                            frameborder="0">
                                                        </iframe>
                                                        <p class="text-xs text-gray-500 text-center py-3 bg-gray-100">
                                                            Pastikan dokumen OneDrive diset ke <strong>"Anyone with the link can
                                                                view"</strong>
                                                        </p>
                                                    @elseif($embedType === 'dropbox')
                                                        <iframe src="{{ $embedUrl }}" class="w-full h-[600px]"
                                                            frameborder="0">
                                                        </iframe>
                                                    @else
                                                        {{-- Untuk file lokal atau PDF --}}
                                                        @if (Str::endsWith(strtolower($embedUrl), '.pdf'))
                                                            <iframe src="{{ $embedUrl }}" class="w-full h-[600px]"
                                                                frameborder="0">
                                                            </iframe>
                                                        @else
                                                            {{-- Untuk file Office (doc, docx, xls, xlsx, ppt, pptx) gunakan Office Online Viewer --}}
                                                            <iframe
                                                                src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($embedUrl) }}"
                                                                class="w-full h-[600px]" frameborder="0">
                                                            </iframe>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @break

                                @case('table')
                                    @php
                                        $heading = $block['data']['heading'] ?? '';
                                        $content = $block['data']['rows'] ?? [];
                                    @endphp

                                    <div class="my-8 overflow-x-auto">
                                        @if (!empty($heading))
                                            <h3 class="text-xl font-bold mb-4 text-gray-900">{{ $heading }}</h3>
                                        @endif

                                        @if (!empty($content) && is_array($content))
                                            <div class="rounded-xl border border-gray-300 overflow-hidden shadow-md">
                                                <table class="min-w-full divide-y divide-gray-300">
                                                    <tbody class="divide-y divide-gray-200 bg-white">
                                                        @foreach ($content as $rowIndex => $row)
                                                            <tr
                                                                class="{{ $rowIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-red-50 transition-colors">
                                                                @if (is_array($row))
                                                                    @foreach ($row as $cellIndex => $cell)
                                                                        <td
                                                                            class="px-6 py-4 text-sm text-gray-700 border-r border-gray-200 last:border-r-0">
                                                                            {!! is_array($cell) ? $cell['content'] ?? ($cell['value'] ?? '') : $cell !!}
                                                                        </td>
                                                                    @endforeach
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                @break

                                @default
                                    {{-- Handle unknown block types --}}
                                    <div class="my-4 p-4 bg-gray-100 rounded-lg text-sm text-gray-600">
                                        <p class="font-semibold">Block type: {{ $block['type'] ?? 'unknown' }}</p>
                                        <pre class="mt-2 text-xs overflow-auto">{{ json_encode($block, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                            @endswitch
                    @endforeach
                @elseif(is_string($contents))
                    {!! $contents !!}
                @else
                    <p class="text-gray-500 italic">Tidak ada konten yang tersedia.</p>
                @endif
            </div>

            {{-- Tag --}}
            @if ($news->tags && $news->tags->count())
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" vicomewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Tags</h3>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($news->tags as $tag)
                            <a href="{{ route('tags.index', $tag->slug) }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-full hover:bg-red-600 hover:text-white transition-all duration-300 border border-gray-300 hover:border-red-600">
                                <span>#</span>{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- ===================== --}}
            {{-- 🏷️ Tags --}}
            {{-- ===================== --}}
            <div class="pb-10 mb-6 mt-4">
                @php
                    $tags = \App\Models\Tags::all();
                @endphp

                <div class="flex flex-wrap -m-1.5">
                    @forelse($tags as $tag)
                        <a href="{{ route('news.tags.index', $tag->slug) }}"
                            class="flex items-center justify-center h-8 border border-gray-300 rounded-full text-xs text-gray-600 hover:bg-gray-700 hover:text-white hover:border-gray-700 transition-all px-4 py-1 m-1.5">
                            {{ $tag->name }}
                        </a>
                    @empty
                        <p class="text-sm text-gray-400"></p>
                    @endforelse
                </div>
            </div>
            {{-- Share Buttons --}}
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <span class="text-gray-700 font-semibold">Bagikan:</span>
                    <div class="flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank"
                            class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}"
                            target="_blank" class="p-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}"
                            target="_blank"
                            class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    </div>
    <script>
        function downloadFile(url, filename) {
            try {
                // Buat elemen <a> untuk trigger download
                const link = document.createElement('a');
                link.href = url;
                link.download = filename || '';

                // Untuk jaga-jaga kalau file dari domain lain (Drive, OneDrive, dsb)
                link.target = '';
                link.rel = 'noopener noreferrer';

                // Simulasikan klik
                document.body.appendChild(link);
                link.click();

                // Hapus elemen setelah selesai
                document.body.removeChild(link);

                console.log('Download dimulai:', url);
            } catch (error) {
                console.error('Gagal mengunduh file:', error);
                alert('Terjadi kesalahan saat mencoba mengunduh file.');
            }
        }
    </script>

</x-layouts.app>
