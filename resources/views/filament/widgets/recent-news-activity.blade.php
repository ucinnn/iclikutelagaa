<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ __('dashboard.recent-news-widget.heading') }}</span>
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::badge color="info">
                {{ __('dashboard.recent-news-widget.badge', ['count' => $this->getRecentNews()->count()]) }}
            </x-filament::badge>
        </x-slot>

        <div class="space-y-3">
            @forelse($this->getRecentNews() as $news)
                <div class="group relative flex items-center gap-4 rounded-xl border border-gray-200 p-4 transition-all hover:border-primary-300 hover:bg-gray-50 hover:shadow-md dark:border-gray-700 dark:hover:border-primary-600 dark:hover:bg-gray-800/50">

                    {{-- Thumbnail --}}
                    <div class="relative flex-shrink-0">
                        @if($news['thumbnail'])
                            <img src="{{ Storage::url($news['thumbnail']) }}"
                                 alt="{{ $news['title'] }}"
                                 class="h-20 w-20 rounded-lg object-cover ring-2 ring-gray-100 transition-transform group-hover:scale-105 dark:ring-gray-800">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 ring-2 ring-gray-100 dark:from-gray-800 dark:to-gray-700 dark:ring-gray-800">
                                <svg class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                        @endif

                        {{-- Featured Badge on Image --}}
                        @if($news['featured'])
                            <div class="absolute -right-2 -top-2">
                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-yellow-500 shadow-lg ring-2 ring-white dark:ring-gray-900">
                                    <svg class="h-3.5 w-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="min-w-0 flex-1">
                        {{-- Title --}}
                        <h4 class="mb-2 line-clamp-2 text-base font-semibold text-gray-900 transition-colors group-hover:text-primary-600 dark:text-gray-100 dark:group-hover:text-primary-400">
                            {{ $news['title'] }}
                        </h4>

                        {{-- Meta Info --}}
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-600 dark:text-gray-400">
                            {{-- Author --}}
                            <div class="flex items-center gap-1.5">
                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/30">
                                    <svg class="h-3 w-3 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="font-medium">{{ $news['author'] }}</span>
                            </div>

                            {{-- Views --}}
                            <div class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="font-medium text-success-600 dark:text-success-400">
                                    {{ number_format($news['views']) }}
                                </span>
                            </div>

                            {{-- Time --}}
                            <div class="flex items-center gap-1.5">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $news['created_at'] }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="flex-shrink-0">
                        <x-filament::badge
                            size="lg"
                            :color="match($news['status']) {
                                'published' => 'success',
                                'draft' => 'warning',
                                'schedule' => 'primary',
                                default => 'gray'
                            }">
                            <div class="flex items-center gap-1.5">
                                @if($news['status'] === 'published')
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @elseif($news['status'] === 'draft')
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                @else
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                                <span class="font-medium">{{ __('dashboard.recent-news-widget.status.' . $news['status'], [], $news['status']) }}</span>
                            </div>
                        </x-filament::badge>
                    </div>

                    {{-- Hover Indicator --}}
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 transition-opacity group-hover:opacity-100">
                        <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __('dashboard.recent-news-widget.empty.heading') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('dashboard.recent-news-widget.empty.description') }}
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Footer Summary --}}
      @if($this->getRecentNews()->isNotEmpty())
    <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ __('dashboard.recent-news-widget.footer.info', ['count' => $this->getRecentNews()->count()]) }}</span>
            </div>

            <a href="{{ route('filament.admin.resources.news.index') }}"
               class="flex items-center gap-1 font-medium text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                <span>{{ __('dashboard.recent-news-widget.footer.link') }}</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
@endif

    </x-filament::section>
</x-filament-widgets::widget>