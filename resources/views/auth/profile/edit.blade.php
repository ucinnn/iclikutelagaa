<x-layouts.app>
    @section('pageTitle', 'Edit Profile')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                <a href="javascript:history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>

                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                        <p class="text-gray-600 mt-1">Perbarui informasi akun Anda</p>
                    </div>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-md p-6 sticky top-24">
                        {{-- Avatar Preview --}}
                        <div class="text-center mb-6">
                            <div class="relative inline-block">
                                <img id="avatarPreview"
                                    src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=200&background=dc2626&color=fff' }}"
                                    alt="{{ Auth::user()->name }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg mx-auto">
                                <div class="absolute bottom-0 right-0 bg-red-600 text-white p-2 rounded-full shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                        </div>

                        {{-- Quick Info --}}
                        <div class="space-y-3 pt-6 border-t border-gray-200">
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-gray-600">Bergabung
                                    {{ Auth::user()->created_at->translatedFormat('M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                <span class="text-gray-600">Akun Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Main Form --}}
                <main class="lg:col-span-2">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Personal Information --}}
                        <div class="bg-white rounded-2xl shadow-md p-6">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Informasi Pribadi</h2>
                            </div>

                            <div class="space-y-5">
                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', Auth::user()->name) }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all @error('name') border-red-500 @enderror"
                                        required>
                                    @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                {{-- <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email <span class="text-red-600">*</span>
                                    </label>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', Auth::user()->email) }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all @error('email') border-red-500 @enderror"
                                        required>
                                    @error('email')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div> --}}

                                {{-- Phone --}}
                                {{-- <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Telepon
                                    </label>
                                    <input type="tel" id="phone" name="phone"
                                        value="{{ old('phone', Auth::user()->phone) }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all"
                                        placeholder="08xxxxxxxxxx">
                                </div> --}}

                                {{-- Bio --}}
                                {{-- <div>
                                    <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Bio
                                    </label>
                                    <textarea id="bio" name="bio" rows="4"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all resize-none"
                                        placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', Auth::user()->bio) }}</textarea>
                                    <p class="mt-2 text-sm text-gray-500">Maksimal 200 karakter</p>
                                </div> --}}

                                {{-- Avatar Upload --}}
                                {{-- <div>
                                    <label for="avatar" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Foto Profile
                                    </label>
                                    <div class="flex items-center gap-4">
                                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden"
                                            onchange="previewAvatar(event)">
                                        <label for="avatar"
                                            class="flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 cursor-pointer transition-all font-medium border-2 border-gray-200 hover:border-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Pilih Foto
                                        </label>
                                        <span id="fileName" class="text-sm text-gray-600">Tidak ada file dipilih</span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">JPG, PNG atau GIF. Maksimal 2MB.</p>
                                    @error('avatar')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div> --}}
                            </div>
                        </div>

                        {{-- Change Password --}}
                        <div class="bg-white rounded-2xl shadow-md p-6">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Ubah Password</h2>
                                    <p class="text-sm text-gray-600 mt-1">Kosongkan jika tidak ingin mengubah password
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                {{-- Current Password --}}
                                <div>
                                    <label for="current_password"
                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                        Password Saat Ini
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="current_password" name="current_password"
                                            class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all @error('current_password') border-red-500 @enderror">
                                        <button type="button" onclick="togglePassword('current_password')"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('current_password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                {{-- New Password --}}
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="password" name="password"
                                            class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all @error('password') border-red-500 @enderror">
                                        <button type="button" onclick="togglePassword('password')"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Minimal 8 karakter</p>
                                    @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                {{-- Confirm Password --}}
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                        Konfirmasi Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all">
                                        <button type="button" onclick="togglePassword('password_confirmation')"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-4">
                            <button type="submit"
                                class="flex-1 flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-red-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center justify-center gap-2 px-8 py-4 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
    // Preview Avatar
    function previewAvatar(event) {
        const file = event.target.files[0];
        const fileName = document.getElementById('fileName');
        const preview = document.getElementById('avatarPreview');

        if (file) {
            fileName.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            fileName.textContent = 'Tidak ada file dipilih';
        }
    }

    // Toggle Password Visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
    }
    </script>
</x-layouts.app>
