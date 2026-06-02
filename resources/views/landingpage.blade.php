<x-layouts.appuser>
    @push('styles')
    <style>
        /* ========================================
           HERO ANIMATIONS
           ======================================== */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-scale-in {
            animation: scaleIn 0.6s ease-out forwards;
        }

        .shimmer-effect {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }

        /* ========================================
           GRADIENT BACKGROUNDS
           ======================================== */
        .gradient-hero {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 25%, #fcd34d 50%, #fbbf24 75%, #f59e0b 100%);
            position: relative;
            overflow: hidden;
        }

        .gradient-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(251, 191, 36, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.3) 0%, transparent 50%);
            pointer-events: none;
        }

        .gradient-card {
            background: linear-gradient(135deg, #ffffff 0%, #fefce8 100%);
        }

        /* ========================================
           FEATURE CARDS
           ======================================== */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(251, 191, 36, 0.1), transparent);
            transition: left 0.5s;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(251, 191, 36, 0.3);
        }

        .feature-icon {
            transition: all 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.15) rotate(5deg);
        }

        /* ========================================
           BLOB DECORATIONS
           ======================================== */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
            pointer-events: none;
        }

        .blob-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            top: -100px;
            right: -100px;
            animation: float 8s ease-in-out infinite;
        }

        .blob-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #fcd34d 0%, #fbbf24 100%);
            bottom: -50px;
            left: -50px;
            animation: float 10s ease-in-out infinite;
            animation-delay: 2s;
        }

        /* ========================================
           STATS SECTION
           ======================================== */
        .stat-item {
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: scale(1.05);
        }

        .stat-number {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ========================================
           CTA BUTTON
           ======================================== */
        .cta-button {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .cta-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
        }

        .cta-button:active {
            transform: translateY(0);
        }

        /* ========================================
           SCROLL INDICATOR
           ======================================== */
        .scroll-indicator {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
    </style>
    @endpush

    {{-- Hero Section --}}
    <section class="text-center py-20 bg-gradient-to-b from-amber-50 to-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-amber-700">Selamat Datang di {{ filament()->getCurrentPanel()->getBrandName() ?? config('app.name') }}</h1>
        <a href="#features" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold transition">Pelajari Lebih Lanjut</a>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 bg-gradient-to-b from-white to-amber-50">
        <div class="container mx-auto px-6">
            {{-- Section Header --}}
            <div class="text-center mb-16">
                <span class="text-2xl inline-block bg-amber-100 text-amber-700 px-4 py-2 rounded-full text-sm font-bold mb-4">
                    FITUR UNGGULAN
                </span>
            </div>

            {{-- Feature Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                {{-- Feature 1 --}}
                <div class="feature-card gradient-card p-8 rounded-3xl shadow-xl">
                    <div class="feature-icon bg-gradient-to-br from-amber-400 to-orange-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900">Manajemen Konten</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Atur berita, artikel, dan seluruh konten Anda dengan sistem yang mudah, intuitif, dan efisien untuk meningkatkan produktivitas tim
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="feature-card gradient-card p-8 rounded-3xl shadow-xl">
                    <div class="feature-icon bg-gradient-to-br from-amber-400 to-orange-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900">Sistem Notifikasi</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Dapatkan pemberitahuan real-time untuk setiap update penting langsung ke dashboard Anda, tidak ada informasi yang terlewat
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="feature-card gradient-card p-8 rounded-3xl shadow-xl">
                    <div class="feature-icon bg-gradient-to-br from-amber-400 to-orange-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900">Akses Cepat</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Navigasi super cepat ke semua fitur utama platform untuk efisiensi kerja maksimal dan pengalaman pengguna yang optimal
                    </p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer untuk animasi on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-scale-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            observer.observe(card);
        });
    </script>
    @endpush
</x-layouts.appuser>