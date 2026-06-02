<x-layouts.app>
    @section('pageTitle', 'Whistle Blowing')

    {{-- Header Sticky --}}
    <header class="sticky top-0 z-50 bg-white shadow"></header>

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-red-600 via-red-700 to-rose-800 text-white py-16 sm:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6 backdrop-blur-sm">
                <i class="fas fa-bullhorn text-4xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">Whistle Blowing</h1>
            <p class="text-base sm:text-lg text-red-100 max-w-2xl mx-auto">
                Laporkan dugaan pelanggaran, kecurangan, atau tindak pidana. Identitas Anda dijaga kerahasiaannya.
            </p>
        </div>
    </section>

    {{-- Main Content --}}
    <x-layouts.main>
        <div class="container mx-auto py-8 sm:py-12 px-0 sm:px-6 lg:px-12">

            {{-- Alert Success/Error --}}
            @if (session('success'))
                <div class="max-w-4xl mx-auto mb-6 px-4 sm:px-0">
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm animate-fade-in">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-4xl mx-auto mb-6 px-4 sm:px-0">
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm animate-fade-in">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Grid --}}
            <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 px-4 sm:px-0">

                {{-- Form Laporan --}}
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <div class="bg-white rounded-none sm:rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-flag mr-3"></i>
                                Buat Laporan Baru
                            </h2>
                        </div>

                        <form action="{{ route('whistle-blowing.store') }}" method="POST"
                            enctype="multipart/form-data" class="p-6 space-y-5" id="whistleForm">
                            @csrf

                            {{-- Subject --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-red-600"></i>Nama Pelaku <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="subject"
                                    value="{{ old('subject') }}"
                                    placeholder="Contoh: Fulan (Bisa lebih dari satu)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition @error('subject') border-red-500 @enderror"
                                    required>
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-folder mr-2 text-red-600"></i>Kategori Pelanggaran <span class="text-red-500">*</span>
                                </label>
                                <select name="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition @error('category') border-red-500 @enderror"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Korupsi"         {{ old('category') == 'Korupsi' ? 'selected' : '' }}>Korupsi</option>
                                    <option value="Gratifikasi"     {{ old('category') == 'Gratifikasi' ? 'selected' : '' }}>Gratifikasi</option>
                                    <option value="Kecurangan"      {{ old('category') == 'Kecurangan' ? 'selected' : '' }}>Kecurangan</option>
                                    <option value="Pelecehan"       {{ old('category') == 'Pelecehan' ? 'selected' : '' }}>Pelecehan</option>
                                    <option value="Pelanggaran SOP" {{ old('category') == 'Pelanggaran SOP' ? 'selected' : '' }}>Pelanggaran SOP</option>
                                    <option value="Lainnya"         {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Division --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-building mr-2 text-red-600"></i>Divisi Terkait <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="division"
                                    value="{{ old('division') }}"
                                    placeholder="Contoh: HRD, Keuangan, Operasional..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition @error('division') border-red-500 @enderror"
                                    required>
                                @error('division')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-comment-dots mr-2 text-red-600"></i>Kronologi Kejadian <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" rows="6"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition resize-none @error('description') border-red-500 @enderror"
                                    placeholder="Ceritakan kronologi kejadian secara detail: kapan, di mana, siapa yang terlibat, dan apa yang terjadi..."
                                    required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>Semakin detail kronologi, semakin cepat kami dapat menindaklanjuti.
                                </p>
                            </div>

                            {{-- Bukti Pendukung --}}
                            <div class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-paperclip mr-2 text-red-600"></i>Bukti Pendukung
                                    <span class="text-gray-400 font-normal text-xs">(opsional • bisa file atau link maupun keduanya • FILE BESAR DISARANKAN VIA LINK)</span>
                                </label>

                                {{-- Input Links --}}
                                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-link mr-2 text-blue-600"></i>Link Bukti
                                        <span class="text-gray-400 font-normal text-xs">(Google Drive, OneDrive, Dropbox, dll)</span>
                                    </label>

                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-white rounded-full text-xs text-gray-600 border">
                                            <img src="https://www.google.com/favicon.ico" class="w-3 h-3 mr-1"> Google Drive
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-white rounded-full text-xs text-gray-600 border">
                                            <img src="https://onedrive.live.com/favicon.ico" class="w-3 h-3 mr-1"> OneDrive
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-white rounded-full text-xs text-gray-600 border">
                                            <img src="https://www.dropbox.com/favicon.ico" class="w-3 h-3 mr-1"> Dropbox
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-white rounded-full text-xs text-gray-600 border">
                                            <img src="https://www.icloud.com/favicon.ico" class="w-3 h-3 mr-1"> iCloud
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-white rounded-full text-xs text-gray-600 border">
                                            <i class="fas fa-link mr-1"></i> Link lainnya
                                        </span>
                                    </div>

                                    <div id="linkContainer" class="space-y-2">
                                        <div class="flex gap-2 link-item items-center">
                                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center" id="linkIcon_0">
                                                <i class="fas fa-link text-gray-400 text-sm"></i>
                                            </div>
                                            <input type="text" name="links[]"
                                                placeholder="Tempel link dari Google Drive, OneDrive, Dropbox, dll..."
                                                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm bg-white"
                                                oninput="detectPlatform(this, 0)">
                                            <button type="button" onclick="removeLinkRow(this)"
                                                class="px-3 py-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="button" onclick="addLinkRow()"
                                        class="mt-2 inline-flex items-center text-xs text-blue-600 hover:text-blue-700 font-medium">
                                        <i class="fas fa-plus mr-1"></i> Tambah Link
                                    </button>

                                    @error('links.*')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Upload File --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-cloud-upload-alt mr-2 text-red-600"></i>Upload File
                                        <span class="text-gray-400 font-normal text-xs">(semua jenis file • total maks. 500MB)</span>
                                    </label>
                                    <div id="dropZone"
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all duration-200"
                                        onclick="document.getElementById('proofInput').click()">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                        <p class="text-sm font-medium text-gray-600">Klik atau seret file ke sini</p>
                                        <p class="text-xs text-gray-400 mt-1">Semua jenis file didukung • Lebih dari 1 file diperbolehkan • Total maks. 500MB</p>
                                    </div>

                                    <input type="file" id="proofInput" name="proof[]" multiple class="hidden"
                                        onchange="handleFiles(this.files)">

                                    @error('proof')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('proof.*')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <div id="fileList" class="mt-3 hidden">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs font-semibold text-gray-600">File terpilih:</p>
                                            <p class="text-xs text-gray-500">Total: <span id="totalSize" class="font-medium">0 MB</span> / 500 MB</p>
                                        </div>
                                        <div id="fileItems" class="space-y-2 max-h-48 overflow-y-auto"></div>
                                        <div id="sizeWarning" class="hidden mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-600">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Total ukuran file melebihi 500MB!
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Privacy Notice --}}
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-shield-alt text-yellow-600 mt-0.5 mr-3 flex-shrink-0"></i>
                                    <div class="text-xs text-yellow-800">
                                        <p class="font-semibold mb-1">Jaminan Kerahasiaan</p>
                                        <p>Identitas pelapor dijaga kerahasiaannya sesuai dengan kebijakan perusahaan. Laporan akan ditangani oleh tim yang berwenang.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>Respons dalam 1-3 hari kerja
                                </div>
                                <button type="submit" id="submitBtn"
                                    class="bg-gradient-to-r from-red-600 to-rose-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-red-700 hover:to-rose-700 transition duration-200 shadow-md hover:shadow-lg flex items-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1 order-1 lg:order-2 space-y-6">
                    <div class="bg-white rounded-none sm:rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-list-ol text-red-600 mr-2"></i>
                            Prosedur Pelaporan
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                                <span class="text-gray-600">Isi formulir dengan detail kejadian</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                <span class="text-gray-600">Lampirkan bukti pendukung jika ada</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                <span class="text-gray-600">Laporan diterima & diverifikasi tim</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-bold">4</span>
                                <span class="text-gray-600">Tindak lanjut sesuai prosedur</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-none sm:rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-phone-alt text-red-600 mr-2"></i>
                            Kontak Darurat
                        </h3>
                        <div class="space-y-3 text-sm">
                            <a href="mailto:{{ config('app.site_mail', env('APP_SITEMAIL')) }}" class="flex items-center text-gray-700 hover:text-red-600 transition">
                                <i class="fas fa-envelope text-red-600 w-5 mr-2"></i>
                                {{ config('app.site_mail', env('APP_SITEMAIL')) }}
                            </a>
                        </div>
                    </div>

                    <div class="bg-red-50 rounded-none sm:rounded-xl shadow-lg p-6 border border-red-100">
                        <h3 class="font-bold text-red-800 mb-3 flex items-center">
                            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                            Penting
                        </h3>
                        <p class="text-xs text-red-700 leading-relaxed">
                            Laporan palsu atau fitnah dapat dikenakan sanksi sesuai peraturan yang berlaku. Pastikan informasi yang Anda sampaikan adalah benar dan dapat dipertanggungjawabkan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ✅ $detectPlatform didefinisikan SEKALI di luar @forelse --}}
            @php
                $detectPlatform = function(string $url): array {
                    $u = strtolower($url);
                    if (str_contains($u, 'drive.google.com'))
                        return ['icon' => 'https://www.google.com/favicon.ico', 'label' => 'Google Drive', 'type' => 'img'];
                    if (str_contains($u, 'onedrive.live.com') || str_contains($u, '1drv.ms') || str_contains($u, 'sharepoint.com'))
                        return ['icon' => 'https://onedrive.live.com/favicon.ico', 'label' => 'OneDrive', 'type' => 'img'];
                    if (str_contains($u, 'dropbox.com'))
                        return ['icon' => 'https://www.dropbox.com/favicon.ico', 'label' => 'Dropbox', 'type' => 'img'];
                    if (str_contains($u, 'icloud.com'))
                        return ['icon' => 'https://www.icloud.com/favicon.ico', 'label' => 'iCloud', 'type' => 'img'];
                    if (str_contains($u, 'mega.nz') || str_contains($u, 'mega.io'))
                        return ['icon' => 'https://mega.nz/favicon.ico', 'label' => 'MEGA', 'type' => 'img'];
                    if (str_contains($u, 'wetransfer.com'))
                        return ['icon' => 'https://wetransfer.com/favicon.ico', 'label' => 'WeTransfer', 'type' => 'img'];
                    if (str_contains($u, 'box.com'))
                        return ['icon' => 'https://www.box.com/favicon.ico', 'label' => 'Box', 'type' => 'img'];
                    if (str_contains($u, 'youtube.com') || str_contains($u, 'youtu.be'))
                        return ['icon' => 'fab fa-youtube text-red-600', 'label' => 'YouTube', 'type' => 'fa'];
                    return ['icon' => 'fas fa-external-link-alt text-blue-500', 'label' => 'Link', 'type' => 'fa'];
                };
            @endphp

            {{-- Riwayat Laporan --}}
            <div class="max-w-4xl mx-auto mt-8 px-4 sm:px-0">
                <div class="bg-white rounded-none sm:rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-history mr-3"></i>Riwayat Laporan Saya
                        </h2>
                    </div>
                    <div class="p-6">
                        @forelse ($reports as $report)
                            <div class="border rounded-lg shadow-sm mb-4 overflow-hidden">
                                <div class="px-5 py-4 bg-gradient-to-r from-gray-50 to-gray-100">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-3 mb-1 flex-wrap">
                                                <h3 class="font-bold text-gray-800">{{ $report->category }}</h3>
                                                @if($report->subject)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $report->subject }}
                                                    </span>
                                                @endif
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $report->status === 'resolved' ? 'bg-green-100 text-green-800' :
                                                       ($report->status === 'process'  ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($report->status ?? 'pending') }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-building mr-1"></i>{{ $report->division }}
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-clock mr-1"></i>{{ $report->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 py-4">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $report->description }}</p>

                                    {{-- Bukti File --}}
                                    @if(!empty($report->proof))
                                        @php
                                            $files = is_array($report->proof)
                                                ? $report->proof
                                                : json_decode($report->proof, true);
                                            $files = $files ?? [];
                                        @endphp
                                        @if(!empty($files))
                                            <div class="mt-3 pt-3 border-t">
                                                <p class="text-xs font-semibold text-gray-600 mb-2">
                                                    <i class="fas fa-paperclip mr-1"></i>Bukti File ({{ count($files) }}):
                                                </p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($files as $file)
                                                        @php
                                                            $filePath  = is_array($file) ? ($file['path'] ?? '') : $file;
                                                            $fileName  = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($file);
                                                            $cleanFile = preg_replace('#^storage/#', '', $filePath);
                                                            $url       = asset('storage/' . $cleanFile);
                                                            $ext       = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                                            $iconClass = match($ext) {
                                                                'pdf'                       => 'fa-file-pdf text-red-500',
                                                                'doc', 'docx'               => 'fa-file-word text-blue-500',
                                                                'xls', 'xlsx'               => 'fa-file-excel text-green-500',
                                                                'ppt', 'pptx'               => 'fa-file-powerpoint text-orange-500',
                                                                'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image text-purple-500',
                                                                'mp4', 'avi', 'mov'         => 'fa-file-video text-pink-500',
                                                                'mp3'                       => 'fa-file-audio text-yellow-500',
                                                                'zip', 'rar'                => 'fa-file-archive text-gray-500',
                                                                default                     => 'fa-file text-gray-400',
                                                            };
                                                        @endphp
                                                        <a href="{{ $url }}" download="{{ $fileName }}" target="_blank"
                                                            class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-700 rounded-lg text-xs transition border border-gray-200 hover:border-red-200">
                                                            <i class="fas {{ $iconClass }} text-base flex-shrink-0"></i>
                                                            <div class="min-w-0">
                                                                <p class="font-medium truncate max-w-[160px]">{{ $fileName }}</p>
                                                                <p class="text-gray-400 uppercase">{{ $ext }}</p>
                                                            </div>
                                                            <i class="fas fa-download ml-auto flex-shrink-0 text-gray-400"></i>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    {{-- Bukti Link --}}
                                    @if(!empty($report->links))
                                        @php
                                            $linkList = is_array($report->links)
                                                ? $report->links
                                                : json_decode($report->links, true);
                                            $linkList = $linkList ?? [];
                                        @endphp
                                        @if(!empty($linkList))
                                            <div class="mt-3 pt-3 border-t">
                                                <p class="text-xs font-semibold text-gray-600 mb-2">
                                                    <i class="fas fa-link mr-1"></i>Bukti Link ({{ count($linkList) }}):
                                                </p>
                                                <div class="flex flex-col gap-2">
                                                    @foreach($linkList as $link)
                                                        @php $platform = $detectPlatform($link); @endphp
                                                        <a href="{{ $link }}" target="_blank"
                                                            class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs transition border border-blue-100 hover:border-blue-300 group">
                                                            @if($platform['type'] === 'img')
                                                                <img src="{{ $platform['icon'] }}" class="w-4 h-4 flex-shrink-0" alt="{{ $platform['label'] }}">
                                                            @else
                                                                <i class="{{ $platform['icon'] }} flex-shrink-0"></i>
                                                            @endif
                                                            <span class="font-medium flex-shrink-0 text-blue-800">{{ $platform['label'] }}</span>
                                                            <span class="text-blue-400 truncate group-hover:text-blue-600 max-w-[200px]">{{ $link }}</span>
                                                            <i class="fas fa-external-link-alt flex-shrink-0 ml-auto text-blue-400"></i>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Laporan</h3>
                                <p class="text-gray-500">Anda belum pernah membuat laporan whistle blowing.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </x-layouts.main>

    @push('styles')
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }
        #dropZone.drag-over { border-color: #dc2626; background-color: #fef2f2; }
    </style>
    @endpush

    @push('scripts')
    <script>
        // ========== FILE UPLOAD ==========
        const MAX_TOTAL_SIZE = 500 * 1024 * 1024;
        let selectedFiles    = [];

        const dropZone = document.getElementById('dropZone');
        dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            handleFiles(e.dataTransfer.files);
        });

        function handleFiles(newFiles) {
            for (let file of newFiles) {
                if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            }
            updateFileList();
            syncInputFiles();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
            syncInputFiles();
        }

        function formatSize(bytes) {
            if (bytes < 1024)        return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function getFileIcon(name) {
            const ext = name.split('.').pop().toLowerCase();
            const icons = {
                pdf: 'fa-file-pdf text-red-500',
                doc: 'fa-file-word text-blue-500', docx: 'fa-file-word text-blue-500',
                xls: 'fa-file-excel text-green-500', xlsx: 'fa-file-excel text-green-500',
                ppt: 'fa-file-powerpoint text-orange-500', pptx: 'fa-file-powerpoint text-orange-500',
                jpg: 'fa-file-image text-purple-500', jpeg: 'fa-file-image text-purple-500',
                png: 'fa-file-image text-purple-500', gif: 'fa-file-image text-purple-500',
                mp4: 'fa-file-video text-pink-500', avi: 'fa-file-video text-pink-500',
                mp3: 'fa-file-audio text-yellow-500',
                zip: 'fa-file-archive text-gray-500', rar: 'fa-file-archive text-gray-500',
            };
            return icons[ext] || 'fa-file text-gray-400';
        }

        function updateFileList() {
            const fileList    = document.getElementById('fileList');
            const fileItems   = document.getElementById('fileItems');
            const totalSizeEl = document.getElementById('totalSize');
            const sizeWarning = document.getElementById('sizeWarning');
            const submitBtn   = document.getElementById('submitBtn');

            if (selectedFiles.length === 0) { fileList.classList.add('hidden'); return; }

            fileList.classList.remove('hidden');
            const totalBytes  = selectedFiles.reduce((acc, f) => acc + f.size, 0);
            const isOverLimit = totalBytes > MAX_TOTAL_SIZE;

            totalSizeEl.textContent = formatSize(totalBytes);
            totalSizeEl.className   = isOverLimit ? 'font-medium text-red-600' : 'font-medium text-green-600';
            sizeWarning.classList.toggle('hidden', !isOverLimit);
            submitBtn.disabled = isOverLimit;
            submitBtn.classList.toggle('opacity-50', isOverLimit);
            submitBtn.classList.toggle('cursor-not-allowed', isOverLimit);

            fileItems.innerHTML = selectedFiles.map((file, i) => `
                <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <i class="fas ${getFileIcon(file.name)} text-lg flex-shrink-0"></i>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-700 truncate max-w-[200px]">${file.name}</p>
                            <p class="text-xs text-gray-400">${formatSize(file.size)}</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(${i})" class="ml-2 text-red-400 hover:text-red-600 transition flex-shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }

        function syncInputFiles() {
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            document.getElementById('proofInput').files = dt.files;
        }

        // ========== LINK INPUT ==========
        let linkCount = 1;

        function detectPlatform(input, index) {
            const val    = input.value.toLowerCase();
            const iconEl = document.getElementById('linkIcon_' + index);
            if (!iconEl) return;

            if (val.includes('drive.google.com')) {
                iconEl.innerHTML = '<img src="https://www.google.com/favicon.ico" class="w-5 h-5">';
            } else if (val.includes('onedrive.live.com') || val.includes('1drv.ms') || val.includes('sharepoint.com')) {
                iconEl.innerHTML = '<img src="https://onedrive.live.com/favicon.ico" class="w-5 h-5">';
            } else if (val.includes('dropbox.com')) {
                iconEl.innerHTML = '<img src="https://www.dropbox.com/favicon.ico" class="w-5 h-5">';
            } else if (val.includes('icloud.com')) {
                iconEl.innerHTML = '<img src="https://www.icloud.com/favicon.ico" class="w-5 h-5">';
            } else if (val.includes('mega.nz') || val.includes('mega.io')) {
                iconEl.innerHTML = '<img src="https://mega.nz/favicon.ico" class="w-5 h-5">';
            } else if (val.includes('wetransfer.com')) {
                iconEl.innerHTML = '<img src="https://wetransfer.com/favicon.ico" class="w-5 h-5">';
            } else if (val.includes('box.com')) {
                iconEl.innerHTML = '<img src="https://www.box.com/favicon.ico" class="w-5 h-5">';
            } else if (val.includes('youtube.com') || val.includes('youtu.be')) {
                iconEl.innerHTML = '<i class="fab fa-youtube text-red-600 text-lg"></i>';
            } else if (val.length > 5) {
                iconEl.innerHTML = '<i class="fas fa-link text-blue-500 text-sm"></i>';
            } else {
                iconEl.innerHTML = '<i class="fas fa-link text-gray-400 text-sm"></i>';
            }
        }

        function addLinkRow() {
            const container = document.getElementById('linkContainer');
            const id        = linkCount++;
            const div       = document.createElement('div');
            div.className   = 'flex gap-2 link-item items-center';
            div.innerHTML   = `
                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center" id="linkIcon_${id}">
                    <i class="fas fa-link text-gray-400 text-sm"></i>
                </div>
                <input type="text" name="links[]"
                    placeholder="Tempel link dari Google Drive, OneDrive, Dropbox, dll..."
                    class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm bg-white"
                    oninput="detectPlatform(this, ${id})">
                <button type="button" onclick="removeLinkRow(this)"
                    class="px-3 py-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        }

        function removeLinkRow(btn) {
            const items = document.querySelectorAll('.link-item');
            if (items.length > 1) {
                btn.closest('.link-item').remove();
            } else {
                btn.closest('.link-item').querySelector('input').value = '';
                const iconEl = btn.closest('.link-item').querySelector('[id^="linkIcon_"]');
                if (iconEl) iconEl.innerHTML = '<i class="fas fa-link text-gray-400 text-sm"></i>';
            }
        }

        // ========== MISC ==========
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.animate-fade-in').forEach(el => {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.3s';
                    el.style.opacity    = '0';
                    setTimeout(() => el.remove(), 300);
                }, 5000);
            });

            document.getElementById('whistleForm').addEventListener('submit', function (e) {
                const btn        = document.getElementById('submitBtn');
                const totalBytes = selectedFiles.reduce((acc, f) => acc + f.size, 0);
                if (totalBytes > MAX_TOTAL_SIZE) { e.preventDefault(); alert('Total ukuran file melebihi 500MB!'); return; }
                if (btn.disabled) { e.preventDefault(); return; }
                btn.disabled  = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            });
        });
    </script>
    @endpush
</x-layouts.app>