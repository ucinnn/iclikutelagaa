<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('dashboard.popular-news-widget.heading') }}
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::badge color="primary">
                {{ __('dashboard.popular-news-widget.badge') }}
            </x-filament::badge>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-900">
                            {{ __('dashboard.popular-news-widget.table.header.rank') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-900">
                            {{ __('dashboard.popular-news-widget.table.header.title') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-900">
                            {{ __('dashboard.popular-news-widget.table.header.author') }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-900">
                            {{ __('dashboard.popular-news-widget.table.header.views') }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-900">
                            {{ __('dashboard.popular-news-widget.table.header.featured') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-900">
                            {{ __('dashboard.popular-news-widget.table.header.published_at') }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-900">
                            {{ __('dashboard.popular-news-widget.table.header.status') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @forelse($popularNews as $index => $news)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    @if($index < 3)
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full
                                            {{ $index === 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                            {{ $index === 1 ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                            {{ $index === 2 ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' : '' }}
                                            font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $index + 1 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('filament.admin.resources.news.edit', $news->id) }}"
                                            class="text-sm font-medium text-gray-900 hover:text-primary-600 dark:text-white dark:hover:text-primary-400 line-clamp-2">
                                            {{ $news->title }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ Str::limit($news->slug, 40) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/30">
                                        <span class="text-xs font-medium text-primary-700 dark:text-primary-400">
                                            {{ strtoupper(substr($news->author, 0, 2)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $news->author }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <x-filament::badge color="success" size="lg">
                                    <div class="flex items-center gap-1">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ number_format($news->views) }}
                                    </div>
                                </x-filament::badge>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                @if($news->featured)
                                    <svg class="inline-block h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @else
                                    <svg class="inline-block h-5 w-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $news->published_at ? $news->published_at->format('d M Y') : '-' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $news->published_at ? $news->published_at->format('H:i') : '' }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <x-filament::badge
                                    :color="match($news->status) {
                                        'published' => 'success',
                                        'draft' => 'warning',
                                        'schedule' => 'primary',
                                        default => 'gray'
                                    }">
                                    {{-- Menggunakan helper __() dengan fallback --}}
                                    {{ __('dashboard.popular-news-widget.status.' . $news->status) ?? ucfirst($news->status) }}
                                </x-filament::badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('dashboard.popular-news-widget.table.empty') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Info --}}
        @if($popularNews->isNotEmpty())
        <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{-- Menggunakan {!! !!} untuk merender HTML (span) di dalam string terjemahan --}}
                {!! __('dashboard.popular-news-widget.pagination.info', [
                    'count' => '<span class="font-medium">' . $popularNews->count() . '</span>',
                    'total' => '<span class="font-medium">' . $totalNews . '</span>'
                ]) !!}
            </div>
        </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>