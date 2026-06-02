@php
    $brandName = filament()->getBrandName() ?? config('app.name');
    $favicon = filament()->getFavicon() ?? asset('favicon.ico');

    $brandLogo = filament()->getBrandLogo();
    $brandLogoHeight = filament()->getBrandLogoHeight() ?? '1.5rem';
    $logoStyles = "height: {$brandLogoHeight}";
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ $brandName }}</title>

    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
<div class="bg-white rounded-2xl shadow-xl p-8">

    <!-- Header -->
    <div class="text-center mb-8">

        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center justify-center gap-3 mb-4 hover:opacity-80 transition">
            @if ($brandLogo)
                <img src="{{ $brandLogo }}"
                     alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}"
                     style="{{ $logoStyles }}">
            @endif

            <span class="text-xl font-bold text-gray-900">
                {{ $brandName }}
            </span>
        </a>

        <p class="text-gray-600">
            Masuk ke akun Anda
        </p>

    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Alamat Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="nama@email.com"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg
                       focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                       transition @error('email') border-red-500 @enderror"
            >

            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Kata sandi
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
                placeholder="Masukkan kata sandi"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg
                       focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                       transition @error('password') border-red-500 @enderror"
            >

            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember + Forgot -->
        <div class="flex items-center justify-between">

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >
                Ingat saya
            </label>

            <a href="{{ route('password.request') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Lupa kata sandi?
            </a>

        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium
                   hover:bg-indigo-700 focus:outline-none focus:ring-2
                   focus:ring-indigo-500 focus:ring-offset-2 transition duration-200">
            Masuk
        </button>

    </form>

    <!-- Back -->
    <div class="mt-6 text-center">
        <a href="{{ url('/') }}"
           class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center gap-2">

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>

            Kembali ke halaman utama
        </a>
    </div>

</div>
</div>

</body>
</html>