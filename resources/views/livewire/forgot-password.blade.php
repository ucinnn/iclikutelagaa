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

    @section('pageTitle', 'Forgot Password')

    {{-- Dynamic Title --}}
    @php
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
        <!-- Card Forgot Password -->
        <div class="bg-[#121212] rounded-2xl px-12 py-10 h-full max-h-[600px]">
            <!-- Logo dan Heading -->
            <div class="text-center mb-2">
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

                <h2 class="text-white text-xl font-medium mb-2">Lupa Password</h2>
                <p class="text-[#B0B3B8] text-sm">
                    Masukkan email anda dan link reset password akan dikirimkan melalui email anda
                </p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="mb-5 bg-green-900/20 border border-green-700 rounded-lg px-4 py-3">
                    <p class="text-green-400 text-sm">{{ session('status') }}</p>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('password.email') }}" method="POST" class="mt-6">
                @csrf
                
                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block font-bold text-[#B0B3B8] text-sm mb-2">
                        Alamat email<span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full bg-[#2A2F3A] border border-[#1E1E1E] rounded-lg px-4 py-3 text-[#D9E0E7] text-sm focus:outline-none focus:border-[#F5A300] focus:ring-1 focus:ring-[#F5A300] transition-colors"
                        placeholder="your@email.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-[#F5A300] hover:bg-[#D68B00] text-white font-medium text-sm py-3 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#F5A300] focus:ring-offset-2 focus:ring-offset-[#1A1D21] mb-4"
                >
                    Kirim link reset
                </button>

                <!-- Back to Login Link -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-[#F2B300] text-sm font-bold hover:text-[#D68B00] transition-colors">
                        ← Kembali ke masuk
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>