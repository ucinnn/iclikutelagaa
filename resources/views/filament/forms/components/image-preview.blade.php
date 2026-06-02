@php
    // Pastikan $get tersedia
    $url = isset($get) ? $get('url_link') : null;

    function convertCloudUrl($url)
    {
        if (! $url) return null;

        // 🔹 Google Drive
        if (preg_match('/drive\.google\.com.*\/d\/([^\/]+)/', $url, $matches)) {
            return "https://drive.google.com/uc?export=view&id={$matches[1]}";
        }

        // 🔹 OneDrive (public share link)
        if (preg_match('/1drv\.ms|onedrive\.live\.com/', $url)) {
            // Replace 'redir' or 'view' with 'download'
            return str_replace(['redir?', 'view?'], 'download?', $url);
        }

        // 🔹 Dropbox
        if (preg_match('/dropbox\.com/', $url)) {
            return str_replace('?dl=0', '?raw=1', $url);
        }

        // default direct URL
        return $url;
    }

    $directUrl = convertCloudUrl($url);
@endphp

@if ($directUrl)
    <div class="mt-2">
        <div class="text-sm text-gray-600 mb-1">Image Preview:</div>
        <img 
            src="{{ $directUrl }}" 
            alt="Preview" 
            class="rounded-lg border border-gray-300 shadow-sm max-h-64 object-contain"
            onerror="this.style.display='none'"
        >
    </div>
@else
    <p class="text-gray-400 text-sm italic">Belum ada gambar yang dimasukkan.</p>
@endif
