<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @if ($favicon = filament()->getFavicon() ?? config('app.logo'))
        <link rel="icon" type="image/png" href="{{ $favicon }}" />
    @endif

    @section('pageTitle', 'Login')

    {{-- Dynamic Title --}}
    @php
        // Ambil judul halaman dari Livewire atau section 'pageTitle' di Blade
        $pageTitle = trim(strip_tags(
            ($livewire ?? null)?->getTitle()
            ?? View::getSections()['pageTitle'] ?? ''
        ));

        $brandName = trim(strip_tags(filament()->getBrandName() ?? config('app.name')));
    @endphp

    <title>
        {{ $pageTitle ? "{$pageTitle} - {$brandName}" : $brandName }}
    </title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg-main': '#0A0A0A',
                        'card-bg': '#1A1D21',
                        'input-bg': '#0D0F12',
                        'input-border': '#1E1E1E',
                        'input-text': '#D9E0E7',
                        'label-text': '#B0B3B8',
                        'placeholder': '#8A8D91',
                        'link-accent': '#F2B300',
                        'btn-primary': '#F5A300',
                        'btn-hover': '#D68B00',
                        'logo-blue': '#007BFF',
                        'checkbox-border': '#2E2E2E',
                    }
                }
            }
        }
    </script>
    <style>
        input::placeholder {
            color: #8A8D91;
        }
    </style>
</head>
<body class="bg-[#0A0A0A] min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-[530px]">
        <!-- Card Login -->
        <div class="bg-[#121212] rounded-2xl px-12 py-10 h-full max-h-[600px]">
            <!-- Logo dan Heading -->
            <div class="text-center mb-2">
                <!-- Logo Icon dan Title dalam satu baris -->
                            @php
                $brandLogo = filament()->getBrandLogo();
                $brandLogoHeight = filament()->getBrandLogoHeight() ?? '1.5rem';
                $darkModeBrandLogo = filament()->getDarkModeBrandLogo();
                $hasDarkModeBrandLogo = filled($darkModeBrandLogo);

                $logoStyles = "height: {$brandLogoHeight}";
            @endphp
                <div class="flex items-center justify-center space-x-3 mb-4">
                    <img src="{{ $brandLogo }}" alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}" style="{{ $logoStyles }}">
                    <h1 class="text-white text-2xl font-bold leading-none">
                        {{ $brandName ?? config('app.name') }}
                    </h1>
                </div>

                <h2 class="text-white text-xl font-medium">Sign in</h2>
            </div>

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block font-bold text-[#B0B3B8] text-sm mb-2">
                        Email address<span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full bg-[#2A2F3A] border border-[#1E1E1E] rounded-lg px-4 py-3 text-[#D9E0E7] text-sm focus:outline-none focus:border-[#F5A300] focus:ring-1 focus:ring-[#F5A300] transition-colors"
                        placeholder=""
                        required
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="text-[#B0B3B8] font-bold text-sm">
                            Kata sandi<span class="text-red-500">*</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[#F2B300] text-sm font-bold hover:text-[#D68B00] transition-colors">
                            Lupa kata sand?
                        </a>
                    </div>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full bg-[#2A2F3A] border border-[#1E1E1E] rounded-lg px-4 py-3 text-[#D9E0E7] text-sm focus:outline-none focus:border-[#F5A300] focus:ring-1 focus:ring-[#F5A300] transition-colors pr-11"
                            placeholder=""
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#6B7280] hover:text-[#9CA3AF] transition-colors"
                        >
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-6">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="sr-only peer"
                            >
                            <div class="w-[18px] h-[18px] border-2 border-[#4B5563] rounded bg-transparent peer-checked:bg-[#F5A300] peer-checked:border-[#F5A300] transition-colors"></div>
                            <svg class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-white hidden peer-checked:block pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="ml-2.5 text-[#B0B3B8] text-sm">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-[#F5A300] hover:bg-[#D68B00] text-white font-medium text-sm py-3 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#F5A300] focus:ring-offset-2 focus:ring-offset-[#1A1D21]"
                >
                    Sign in
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</body>
</html