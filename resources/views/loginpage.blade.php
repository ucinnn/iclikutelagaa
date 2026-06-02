<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Dynamic Title --}}
    @php
        $pageTitle = 'Pilih Jenis Login';
        $brandName = trim(strip_tags(filament()->getBrandName() ?? config('app.name')));
    @endphp
    <title>{{ $pageTitle ? "{$pageTitle} - {$brandName}" : $brandName }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #1f2937;
            overflow-x: hidden; /* hanya sembunyikan scroll horizontal */
            overflow-y: auto;   /* biarkan scroll vertical berfungsi */
        }

        /* Animations */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }

        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .animate-slide-up { animation: slideUp 0.8s ease-out forwards; }
        .animate-float { animation: float 6s ease-in-out infinite; }

        /* Card Hover Effect */
        .login-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #ffffff;
            border: 3px solid #e5e7eb;
            border-radius: 1.5rem;
        }

        .login-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 25px rgba(59, 130, 246, 0.1);
        }

        .icon-container {
            transition: transform 0.3s ease;
        }
        .login-card:hover .icon-container {
            transform: scale(1.1) rotate(5deg);
        }
    </style>
</head>
<body class="min-h-screen relative">

    <!-- Back Button -->
    <div class="fixed top-6 left-6 z-50 animate-fade-in">
        <a href="{{ route('landingpage') }}"
            class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-indigo-200 rounded-full text-sm p-3 shadow-lg inline-flex items-center gap-2 transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    <!-- Main Content -->
    <div class="relative flex flex-col items-center justify-center min-h-screen px-4 py-12">
        <div class="text-center max-w-6xl w-full space-y-12 animate-slide-up">

            <!-- Logo & Title -->
            <div class="space-y-6">
                <a href="{{ route('landingpage') }}" class="inline-flex items-center justify-center mb-8 transition-all duration-300 hover:scale-105">
                    @if(config('app.logo'))
                        <img src="{{ config('app.logo') }}" alt="{{ config('app.name') }} Logo" class="h-16 w-auto mr-3 drop-shadow-lg">
                        <span class="text-4xl font-black text-gray-900">{{ filament()->getCurrentPanel()->getBrandName() ?? config('app.name') }}</span>
                    @else
                        <span class="text-4xl font-black text-gray-900">{{ filament()->getCurrentPanel()->getBrandName() ?? config('app.name') }}</span>
                    @endif
                </a>

                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 tracking-tight">
                    Selamat Datang! 👋
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                    Pilih jenis login yang sesuai dengan kebutuhan Anda untuk melanjutkan
                </p>
            </div>

            <!-- Login Cards -->
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">

                <!-- User Login -->
                <a href="{{ route('login') }}" class="block group">
                    <div class="login-card p-8 shadow-lg h-full">
                        <div class="space-y-6">
                            <div class="icon-container w-24 h-24 mx-auto bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user text-white text-4xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">User Login</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Akses untuk pengguna reguler dengan fitur-fitur utama aplikasi
                            </p>
                            <ul class="space-y-3 text-left text-gray-700">
                                <li class="flex items-center"><svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293z" clip-rule="evenodd"></path></svg>Akses fitur utama</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293z" clip-rule="evenodd"></path></svg>Profil & pengaturan</li>
                            </ul>
                            <button class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform group-hover:scale-105 shadow-lg">
                                Masuk sebagai User <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </a>

                           <!-- Admin Login -->
                <a href="{{ route('filament.admin.auth.login') }}" class="block group">
                    <div class="login-card p-8 shadow-lg h-full">
                        <div class="space-y-6">
                            <div class="icon-container w-24 h-24 mx-auto bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user-shield text-white text-4xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">Admin Login</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Akses penuh untuk mengelola sistem, pengaturan, dan data aplikasi
                            </p>
                            <ul class="space-y-3 text-left text-gray-700">
                                <li class="flex items-center"><svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293z" clip-rule="evenodd"></path></svg>Dashboard lengkap</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293z" clip-rule="evenodd"></path></svg>Manajemen pengguna</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293z" clip-rule="evenodd"></path></svg>Pengaturan sistem</li>
                            </ul>
                            <button class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform group-hover:scale-105 shadow-lg">
                                Masuk sebagai Admin <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </a>

            </div>

            <!-- Footer -->
            <div class="text-center pt-8">
                <p class="text-gray-500 text-sm">
                    Belum punya akun? Hubungi admin di
                    <a href="mailto:{{ env('APP_SITEMAIL') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                        {{ env('APP_SITEMAIL') }}
                    </a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
