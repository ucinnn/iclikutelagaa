<x-filament-widgets::widget>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Berita --}}
        <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="grid gap-y-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.news-stats-widget.total_news.label') }}
                    </span>
                </div>
                
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{ number_format($totalNews) }}
                </div>
                
                <div class="flex items-center gap-1 text-sm">
                    <svg class="h-4 w-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span class="font-medium text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.news-stats-widget.total_news.description') }}
                    </span>
                </div>
                
                {{-- Mini Chart --}}
                @if($chartData)
                <div class="mt-2 h-10">
                    <canvas id="totalNewsChart" class="h-full w-full"></canvas>
                </div>
                @endif
            </div>
        </div>

        {{-- Berita Published --}}
        <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="grid gap-y-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.news-stats-widget.published_news.label') }}
                    </span>
                </div>
                
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{ number_format($publishedNews) }}
                </div>
                
                <div class="flex items-center gap-1 text-sm">
                    <svg class="h-4 w-4 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.news-stats-widget.published_news.description', ['draft' => $draftNews, 'scheduled' => $scheduledNews]) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Berita Bulan Ini --}}
        <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="grid gap-y-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.news-stats-widget.monthly_news.label') }}
                    </span>
                </div>
                
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{ number_format($newsThisMonth) }}
                </div>
                
                <div class="flex items-center gap-1 text-sm">
                    @if($monthlyTrend >= 0)
                        <svg class="h-4 w-4 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span class="font-medium text-success-600 dark:text-success-400">
                            {{ __('dashboard.news-stats-widget.monthly_news.increase', ['value' => number_format(abs($monthlyTrend), 1)]) }}
                        </span>
                    @else
                        <svg class="h-4 w-4 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                        <span class="font-medium text-danger-600 dark:text-danger-400">
                            {{ __('dashboard.news-stats-widget.monthly_news.decrease', ['value' => number_format(abs($monthlyTrend), 1)]) }}
                        </span>
                    @endif
                    <span class="text-gray-500 dark:text-gray-400">{{ __('dashboard.news-stats-widget.monthly_news.period') }}</span>
                </div>
                
                {{-- Mini Chart --}}
                @if($weeklyData)
                <div class="mt-2 h-10">
                    <canvas id="monthlyNewsChart" class="h-full w-full"></canvas>
                </div>
                @endif
            </div>
        </div>

        {{-- Berita Featured --}}
        <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="grid gap-y-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.news-stats-widget.featured_news.label') }}
                    </span>
                </div>
                
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{ number_format($featuredNews) }}
                </div>
                
                <div class="flex items-center gap-1 text-sm">
                    <svg class="h-4 w-4 text-warning-600 dark:text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="font-medium text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.news-stats-widget.featured_news.description') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart configuration for mini sparkline
            const chartConfig = {
                type: 'line',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },
                    elements: {
                        line: {
                            borderWidth: 2,
                            tension: 0.4
                        },
                        point: { radius: 0 }
                    }
                }
            };

            // Total News Chart
            @if($chartData)
            const totalNewsCtx = document.getElementById('totalNewsChart');
            if (totalNewsCtx) {
                new Chart(totalNewsCtx, {
                    ...chartConfig,
                    data: {
                        labels: Array({{ count($chartData) }}).fill(''),
                        datasets: [{
                            data: @json($chartData),
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            fill: true
                        }]
                    }
                });
            }
            @endif

            // Monthly News Chart
            @if($weeklyData)
            const monthlyNewsCtx = document.getElementById('monthlyNewsChart');
            if (monthlyNewsCtx) {
                new Chart(monthlyNewsCtx, {
                    ...chartConfig,
                    data: {
                        labels: Array({{ count($weeklyData) }}).fill(''),
                        datasets: [{
                            data: @json($weeklyData),
                            borderColor: @if($monthlyTrend >= 0) 'rgb(34, 197, 94)' @else 'rgb(239, 68, 68)' @endif,
                            backgroundColor: @if($monthlyTrend >= 0) 'rgba(34, 197, 94, 0.1)' @else 'rgba(239, 68, 68, 0.1)' @endif,
                            fill: true
                        }]
                    }
                });
            }
            @endif
        });
    </script>
    @endpush
</x-filament-widgets::widget>