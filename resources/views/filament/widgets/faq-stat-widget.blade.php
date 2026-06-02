<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ __('faq-widget.heading') }}</span>
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <div class="flex items-center gap-2">
                <x-filament::badge color="primary">
                    {{ __('faq-widget.questions_badge', ['count' => $totalFaqs]) }}
                </x-filament::badge>
                <x-filament::badge color="success">
                    {{ __('faq-widget.categories_badge', ['count' => $totalCategories]) }}
                </x-filament::badge>
            </div>
        </x-slot>

        {{-- Stats Overview --}}
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            {{-- Total FAQs --}}
            <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 p-6 transition-all hover:shadow-lg dark:from-primary-900/20 dark:to-primary-800/20">
                <div class="relative z-10">
                    <p class="text-xs font-medium uppercase tracking-wide text-primary-700 dark:text-primary-300 mb-3">{{ __('faq-widget.stats.total_faqs') }}</p>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <p class="text-4xl font-bold text-primary-900 dark:text-primary-100">{{ number_format($totalFaqs) }}</p>
                            <p class="mt-2 text-xs text-primary-600 dark:text-primary-400">{{ __('faq-widget.stats.all_questions') }}</p>
                        </div>

                        <div class="flex-shrink-0 flex h-16 w-16 items-center justify-center rounded-xl bg-primary-600 shadow-lg dark:bg-primary-500">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="absolute bottom-0 right-0 opacity-10 pointer-events-none">
                    <svg class="h-32 w-32 text-primary-900 dark:text-primary-100" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            {{-- Total Categories --}}
            <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-success-50 to-success-100 p-6 transition-all hover:shadow-lg dark:from-success-900/20 dark:to-success-800/20">
                <div class="relative z-10">
                    <p class="text-xs font-medium uppercase tracking-wide text-success-700 dark:text-success-300 mb-3">{{ __('faq-widget.stats.categories_title') }}</p>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <p class="text-4xl font-bold text-success-900 dark:text-success-100">{{ number_format($totalCategories) }}</p>
                            <p class="mt-2 text-xs text-success-600 dark:text-success-400">{{ __('faq-widget.stats.topics_covered') }}</p>
                        </div>

                        <div class="flex-shrink-0 flex h-16 w-16 items-center justify-center rounded-xl bg-success-600 shadow-lg dark:bg-success-500">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="absolute bottom-0 right-0 opacity-10 pointer-events-none">
                    <svg class="h-32 w-32 text-success-900 dark:text-success-100" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            {{-- Top Category --}}
            @if($topCategory)
                <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-warning-50 to-warning-100 p-6 transition-all hover:shadow-lg dark:from-warning-900/20 dark:to-warning-800/20">
                    <div class="relative z-10">
                        <p class="text-xs font-medium uppercase tracking-wide text-warning-700 dark:text-warning-300 mb-3">{{ __('faq-widget.stats.most_popular') }}</p>

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-2xl font-bold text-warning-900 dark:text-warning-100 truncate">{{ $topCategory->category }}</p>
                                <p class="mt-2 text-xs text-warning-600 dark:text-warning-400">{{ __('faq-widget.stats.questions_unit', ['count' => $topCategory->count]) }}</p>
                            </div>

                            <div class="flex-shrink-0 flex h-16 w-16 items-center justify-center rounded-xl bg-warning-600 shadow-lg dark:bg-warning-500">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="absolute bottom-0 right-0 opacity-10 pointer-events-none">
                        <svg class="h-32 w-32 text-warning-900 dark:text-warning-100" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
            @else
                <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 p-6 transition-all hover:shadow-lg dark:from-gray-900/20 dark:to-gray-800/20">
                    <div class="relative z-10">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-3">{{ __('faq-widget.stats.most_popular') }}</p>

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1">
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('faq-widget.stats.no_data') }}</p>
                                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">{{ __('faq-widget.stats.no_categories') }}</p>
                            </div>

                            <div class="flex-shrink-0 flex h-16 w-16 items-center justify-center rounded-xl bg-gray-400 shadow-lg dark:bg-gray-600">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Recent FAQs (2/3 width) --}}
            <div class="lg:col-span-2">
                <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                    <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ __('faq-widget.recent_faqs.heading') }}</span>
                </h3>

                <div class="space-y-3 max-h-[600px] overflow-y-auto pr-2">
                    @forelse($recentFaqs as $faq)
                        <div class="group rounded-xl border border-gray-200 bg-white p-5 transition-all hover:border-primary-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-primary-600">
                            {{-- Header with Category --}}
                            <div class="mb-3 flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-900/30">
                                        <svg class="h-4 w-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <x-filament::badge color="primary" size="sm">
                                        {{ $faq->category }}
                                    </x-filament::badge>
                                </div>
                                <span class="flex-shrink-0 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $faq->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Question --}}
                            <h4 class="mb-3 text-sm font-bold leading-snug text-gray-900 dark:text-white">
                                {{ $faq->question }}
                            </h4>

                            {{-- Answer Preview --}}
                            <div class="mb-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-900/50">
                                <p class="line-clamp-3 text-xs leading-relaxed text-gray-700 dark:text-gray-300">
                                    {{ $faq->answer }}
                                </p>
                            </div>

                            {{-- Footer Metadata --}}
                            <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                                <div class="flex items-center gap-3 text-xs text-gray-600 dark:text-gray-400">
                                    {{-- Author --}}
                                    <div class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="font-medium">{{ $faq->created_by }}</span>
                                    </div>

                                    {{-- Updated Info --}}
                                    @if($faq->updated_by && $faq->updated_at != $faq->created_at)
                                        <div class="flex items-center gap-1.5 text-success-600 dark:text-success-400">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>{{ __('faq-widget.recent_faqs.updated') }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Expand Button --}}
                                <button class="flex items-center gap-1 text-xs font-medium text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                    <span>{{ __('faq-widget.recent_faqs.read_more') }}</span>
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 py-16 text-center dark:border-gray-700">
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/30">
                                <svg class="h-8 w-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mb-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ __('faq-widget.recent_faqs.empty_heading') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('faq-widget.recent_faqs.empty_description') }}
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Category Distribution Chart (1/3 width) --}}
            <div class="lg:col-span-1">
                @if($categoryCounts->isNotEmpty())
                    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                        <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                            <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>{{ __('faq-widget.chart.heading') }}</span>
                        </h3>

                        <div class="mb-4 h-64">
                            <canvas id="faqCategoryChart"></canvas>
                        </div>

                        {{-- Category List --}}
                        <div class="space-y-2 border-t border-gray-200 pt-4 dark:border-gray-700">
                            <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                {{ __('faq-widget.chart.all_categories') }}
                            </h4>
                            @foreach($categoryCounts as $cat)
                                <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 transition-colors hover:bg-gray-100 dark:bg-gray-800/50 dark:hover:bg-gray-800">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        <div class="h-2 w-2 flex-shrink-0 rounded-full bg-primary-600"></div>
                                        <span class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $cat->category }}
                                        </span>
                                    </div>
                                    <x-filament::badge color="primary" size="sm">
                                        {{ $cat->count }}
                                    </x-filament::badge>
                                </div>
                            @endforeach
                        </div>

                        {{-- Summary Stats --}}
                        <div class="mt-4 space-y-2 border-t border-gray-200 pt-4 dark:border-gray-700">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('faq-widget.chart.avg_per_category') }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ round($totalFaqs / max($totalCategories, 1), 1) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('faq-widget.chart.largest_category') }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ __('faq-widget.chart.faqs_unit', ['count' => $topCategory ? $topCategory->count : 0]) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('faqCategoryChart');
                if (ctx) {
                    const categories = @json($categoryCounts->pluck('category'));
                    const counts = @json($categoryCounts->pluck('count'));
                    const isDark = document.documentElement.classList.contains('dark');

                    // Generate colors for each category
                    const colors = [
                        'rgba(99, 102, 241, 0.8)', // primary
                        'rgba(34, 197, 94, 0.8)', // success
                        'rgba(234, 179, 8, 0.8)', // warning
                        'rgba(239, 68, 68, 0.8)', // danger
                        'rgba(59, 130, 246, 0.8)', // info
                        'rgba(168, 85, 247, 0.8)', // purple
                        'rgba(236, 72, 153, 0.8)', // pink
                        'rgba(249, 115, 22, 0.8)', // orange
                    ];

                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: categories,
                            datasets: [{
                                data: counts,
                                backgroundColor: colors.slice(0, categories.length),
                                borderWidth: 2,
                                borderColor: isDark ? 'rgb(17, 24, 39)' : 'rgb(255, 255, 255)',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: {
                                        size: 13,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            cutout: '65%',
                            animation: {
                                animateRotate: true,
                                animateScale: true
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-filament-widgets::widget>