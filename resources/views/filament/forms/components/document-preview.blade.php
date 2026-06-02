@php
    use Illuminate\Support\Facades\Storage;
    use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

    $sourceUrl = null;

    // Handle langsung dari viewData (sudah difilter di resource)
    if (!empty($url)) {
        if ($url instanceof TemporaryUploadedFile) {
            try {
                $sourceUrl = $url->temporaryUrl();
            } catch (\Throwable $e) {
                $sourceUrl = null;
            }
        } elseif (filter_var($url, FILTER_VALIDATE_URL)) {
            $sourceUrl = $url;
        } elseif (is_string($url) && Storage::exists($url)) {
            $sourceUrl = Storage::url($url);
        }
    }
@endphp

<div class="mt-2">
    @if ($sourceUrl)
        @php
            $extension = strtolower(pathinfo(parse_url($sourceUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
        @endphp

        {{-- 🖼️ Preview Gambar --}}
        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
            <img src="{{ $sourceUrl }}" alt="Preview" class="w-full max-h-96 object-contain border rounded-lg" loading="lazy">

        {{-- 📄 Preview PDF --}}
        @elseif ($extension === 'pdf')
            <iframe src="{{ $sourceUrl }}" class="w-full h-96 border rounded-lg"></iframe>

        {{-- 📊 Office --}}
        @elseif (in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']))
            <iframe
                src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($sourceUrl) }}"
                class="w-full h-96 border rounded-lg">
            </iframe>

        {{-- 📃 Text --}}
        @elseif (in_array($extension, ['txt', 'csv']))
            <iframe src="{{ $sourceUrl }}" class="w-full h-96 border rounded-lg"></iframe>

        {{-- 🌐 Tanpa ekstensi --}}
        @elseif (empty($extension))
            <iframe src="{{ $sourceUrl }}" class="w-full h-96 border rounded-lg"></iframe>

        {{-- ❌ Tidak didukung --}}
        @else
            <p class="text-gray-500 text-sm">
                Format dokumen <strong>.{{ $extension }}</strong> tidak didukung untuk pratinjau.
            </p>
            <a href="{{ $sourceUrl }}" target="_blank" class="text-blue-600 underline text-sm">Buka file</a>
        @endif
    @else
        <p class="text-gray-400 italic text-sm">
            Belum ada dokumen yang diunggah atau ditautkan.
        </p>
    @endif
</div>
