<x-layouts.app>
    @section('pageTitle', 'Frequently Asked Questions')

    {{-- Header Sticky --}}
    <header class="sticky top-0 z-50 bg-white shadow"></header>

    {{-- Hero Section --}}
    <section class="bg-gradient-to-r from-custom-red to-red-600 text-white py-12 sm:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-12 text-center">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">Pertanyaan yang Sering Diajukan</h1>
            <p class="text-base sm:text-lg text-gray-100 max-w-2xl mx-auto">
                Temukan jawaban atas pertanyaan umum seputar platform berita kami, layanan, dan kebijakan.
            </p>
            @if($totalFaqs > 0)
                <p class="mt-4 text-sm text-gray-200">
                    <i class="fas fa-database mr-2"></i>{{ $totalFaqs }} pertanyaan tersedia
                </p>
            @endif
        </div>
    </section>

    {{-- Main Content --}}
    <x-layouts.main>
        <div class="container mx-auto py-8 sm:py-12 px-0 sm:px-6 lg:px-12">
            
            {{-- Search Box --}}
            <div class="bg-white rounded-none sm:rounded-xl shadow-sm p-4 sm:p-6 mb-6 sm:mb-8 max-w-3xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           id="faqSearch"
                           placeholder="Search for answers..." 
                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-red focus:border-custom-red outline-none">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <div id="searchResults" class="mt-2 text-sm text-gray-500"></div>
            </div>

            {{-- FAQ Content --}}
            <div class="max-w-4xl mx-auto px-4 sm:px-0">
                @forelse($faqs as $category => $categoryFaqs)
                    @php
                        $firstFaq = $categoryFaqs->first();
                        $categoryName = ucwords(str_replace('_', ' ', $category));
                        
                        // Icon mapping
                        $icons = [
                            'general' => 'fa-info-circle',
                            'account' => 'fa-user-circle',
                            'content' => 'fa-newspaper',
                            'advertising' => 'fa-briefcase',
                            'technical' => 'fa-headset',
                            'privacy' => 'fa-shield-alt',
                            'payment' => 'fa-credit-card',
                            'shipping' => 'fa-truck',
                        ];
                        $categoryIcon = $icons[$category] ?? 'fa-question-circle';
                    @endphp

                    <div class="faq-category mb-8" data-category="{{ $category }}">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas {{ $categoryIcon }} text-custom-red mr-3"></i>
                            {{ $categoryName }}
                        </h2>
                        
                        <div class="space-y-3">
                            @foreach($categoryFaqs as $faq)
                                <div class="faq-item bg-white rounded-none sm:rounded-lg shadow-sm overflow-hidden" 
                                     data-question="{{ strtolower($faq->question) }}"
                                     data-answer="{{ strtolower(strip_tags($faq->answer)) }}"
                                     data-category="{{ strtolower($category) }}">
                                    <button class="faq-question w-full text-left px-4 sm:px-6 py-4 flex justify-between items-center hover:bg-gray-50 transition">
                                        <span class="font-semibold text-gray-800 pr-4 text-sm sm:text-base">{{ $faq->question }}</span>
                                        <i class="fas fa-chevron-down text-custom-red transform transition-transform flex-shrink-0"></i>
                                    </button>
                                    <div class="faq-answer hidden px-4 sm:px-6 pb-4 text-gray-600">
                                        <div class="prose prose-sm max-w-none text-sm sm:text-base">
                                            {!! $faq->answer !!}
                                        </div>
                                        
                                        <div class="mt-4 pt-3 border-t border-gray-200 text-xs text-gray-400 flex flex-wrap items-center gap-2">
                                            <i class="fas fa-clock"></i>
                                            <span>Terakhir diperbarui {{ $faq->updated_at->diffForHumans() }}</span>
                                            @if($faq->updated_by)
                                                <span class="hidden sm:inline">•</span>
                                                <span class="hidden sm:inline">by {{ $faq->updated_by }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada FAQ</h3>
                    <p class="text-gray-500">Kami sedang menyiapkan daftar pertanyaan yang sering diajukan. Silakan periksa kembali nanti!</p>
                </div>
                @endforelse

                {{-- No Results Message --}}
                <div id="noResults" class="hidden bg-white rounded-none sm:rounded-xl shadow-sm p-8 text-center">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Hasil Ditemukan</h3>
                    <p class="text-gray-500">Coba ubah kata pencarian Anda atau jelajahi semua kategori.</p>
                    <button onclick="clearSearch()" class="px-4 py-2 bg-custom-red text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-times mr-2"></i>Bersihkan pencarian
                    </button>
                </div>
            </div>

            {{-- Still Need Help Section --}}
            <div class="max-w-3xl mx-auto mt-12 bg-gradient-to-r from-custom-red to-red-600 rounded-xl p-8 text-center text-white">
                <i class="fas fa-question-circle text-5xl mb-4 opacity-80"></i>
                <h3 class="text-2xl font-bold mb-2">Masih Membutuhkan Bantuan?</h3>
                <p class="mb-6 text-gray-100">Tidak menemukan jawaban yang Anda cari? Tim dukungan kami siap membantu Anda.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="mailto:{{ env('APP_SITEMAIL') }}" class="bg-white text-custom-red px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-flex items-center">
                        <i class="fas fa-envelope mr-2"></i>
                        Kirim Email
                    </a>
                    <a href="{{ route('helpdesk.index') }}" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-custom-red transition inline-flex items-center">
                        <i class="fas fa-comment-dots mr-2"></i>
                        Hubungi Kami
                    </a>
                </div>
            </div>


        </div>
    </x-layouts.main>

    {{-- Footer --}}
    <footer class="mt-10 py-6 text-center"></footer>

    {{-- FAQ JavaScript --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle FAQ answers
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const faqItem = this.closest('.faq-item');
                    const answer = faqItem.querySelector('.faq-answer');
                    const icon = this.querySelector('i');
                    
                    // Close all other FAQs
                    document.querySelectorAll('.faq-item').forEach(item => {
                        if (item !== faqItem) {
                            const otherAnswer = item.querySelector('.faq-answer');
                            const otherIcon = item.querySelector('.faq-question i');
                            if (otherAnswer && !otherAnswer.classList.contains('hidden')) {
                                otherAnswer.classList.add('hidden');
                                otherIcon.classList.remove('rotate-180');
                            }
                        }
                    });
                    
                    // Toggle current FAQ
                    answer.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                });
            });

            // Search functionality
            const searchInput = document.getElementById('faqSearch');
            const searchResults = document.getElementById('searchResults');
            const noResults = document.getElementById('noResults');
            const faqCategories = document.querySelectorAll('.faq-category');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;
                
                if (searchTerm === '') {
                    // Show all FAQs
                    faqCategories.forEach(cat => cat.style.display = 'block');
                    document.querySelectorAll('.faq-item').forEach(item => {
                        item.style.display = 'block';
                    });
                    searchResults.textContent = '';
                    noResults.classList.add('hidden');
                    return;
                }
                
                // Filter FAQs
                faqCategories.forEach(category => {
                    const faqItems = category.querySelectorAll('.faq-item');
                    let categoryHasResults = false;
                    
                    faqItems.forEach(item => {
                        const question = item.dataset.question || '';
                        const answer = item.dataset.answer || '';
                        const cat = item.dataset.category || '';
                        
                        if (question.includes(searchTerm) || answer.includes(searchTerm) || cat.includes(searchTerm)) {
                            item.style.display = 'block';
                            categoryHasResults = true;
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    // Show/hide category based on results
                    category.style.display = categoryHasResults ? 'block' : 'none';
                });
                
                // Update results message
                if (visibleCount > 0) {
                    searchResults.innerHTML = `<i class="fas fa-check-circle mr-1"></i>Found ${visibleCount} result${visibleCount !== 1 ? 's' : ''}`;
                    searchResults.classList.remove('text-red-500');
                    searchResults.classList.add('text-green-600');
                    noResults.classList.add('hidden');
                } else {
                    searchResults.innerHTML = '<i class="fas fa-times-circle mr-1"></i>No results found';
                    searchResults.classList.remove('text-green-600');
                    searchResults.classList.add('text-red-500');
                    noResults.classList.remove('hidden');
                }
            });

            // Clear search function
            window.clearSearch = function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            };

            // Smooth scroll to FAQ if URL has hash
            if (window.location.hash) {
                const targetId = window.location.hash.substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    setTimeout(() => {
                        targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetElement.querySelector('.faq-question')?.click();
                    }, 500);
                }
            }
        });
    </script>
    @endpush

    {{-- Custom Styles --}}
    @push('styles')
    <style>
        .faq-answer {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #1f2937;
            font-weight: 600;
            margin-top: 1em;
            margin-bottom: 0.5em;
        }

        .prose p {
            margin-bottom: 1em;
            line-height: 1.7;
        }

        .prose ul, .prose ol {
            margin: 1em 0;
            padding-left: 1.5em;
        }

        .prose li {
            margin-bottom: 0.5em;
        }

        .prose a {
            color: #dc2626;
            text-decoration: underline;
        }

        .prose a:hover {
            color: #b91c1c;
        }

        .prose code {
            background-color: #f3f4f6;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }

        .prose blockquote {
            border-left: 4px solid #dc2626;
            padding-left: 1em;
            margin: 1em 0;
            font-style: italic;
            color: #4b5563;
        }
    </style>
    @endpush
</x-layouts.app>