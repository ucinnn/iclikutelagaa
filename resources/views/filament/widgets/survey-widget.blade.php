<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Quick Survey Access</span>
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <div class="flex items-center gap-2 text-xs">
                <div class="flex items-center gap-1.5">
                    <div class="h-2 w-2 rounded-full bg-success-600 dark:bg-success-400 animate-pulse"></div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $activeSurveysCount }} Active</span>
                </div>
                <span class="text-gray-400">•</span>
                <span class="text-gray-600 dark:text-gray-400">{{ $totalSurveys }} Total</span>
            </div>
        </x-slot>

        @if($activeSurveys->isNotEmpty())
            {{-- Active Surveys Grid --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($activeSurveys as $survey)
                    <a href="{{ $survey['link'] }}" 
                       target="_blank"
                       rel="noopener noreferrer"
                       class="group relative flex flex-col overflow-hidden rounded-xl border-2 border-gray-200 bg-white p-5 transition-all hover:border-primary-400 hover:shadow-xl dark:border-gray-700 dark:bg-gray-800 dark:hover:border-primary-500">
                        
                        {{-- Icon & Badge Container --}}
                        <div class="mb-4 flex items-start justify-between">
                            {{-- Icon --}}
                            <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 shadow-lg transition-transform group-hover:scale-110 group-hover:rotate-3">
                                @if($survey['icon'])
                                    <i class="{{ $survey['icon'] }} text-2xl text-white"></i>
                                @else
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @endif
                            </div>

                            {{-- Active Badge --}}
                            <div class="flex items-center gap-1 rounded-full bg-success-100 px-2.5 py-1 dark:bg-success-900/30">
                                <div class="h-1.5 w-1.5 rounded-full bg-success-600 dark:bg-success-400 animate-pulse"></div>
                                <span class="text-xs font-medium text-success-700 dark:text-success-300">Live</span>
                            </div>
                        </div>

                        {{-- Title --}}
                        <h3 class="mb-2 text-base font-bold leading-tight text-gray-900 transition-colors group-hover:text-primary-600 dark:text-white dark:group-hover:text-primary-400 line-clamp-2">
                            {{ $survey['title'] }}
                        </h3>

                        {{-- Description --}}
                        @if($survey['description'])
                        <p class="mb-4 flex-1 text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                            {{ \Illuminate\Support\Str::limit(
                                \Illuminate\Support\Str::of($survey['description'])->stripTags(),
                                120
                            ) }}
                        </p>
                        @endif

                        {{-- Footer --}}
                        <div class="mt-auto space-y-3 border-t border-gray-100 pt-3 dark:border-gray-700">
                            {{-- Domain --}}
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                                <span class="truncate">{{ $survey['domain'] }}</span>
                            </div>

                            {{-- Action Button --}}
                            <div class="flex items-center justify-between rounded-lg bg-primary-50 px-3 py-2 transition-colors group-hover:bg-primary-100 dark:bg-primary-900/20 dark:group-hover:bg-primary-900/40">
                                <span class="text-sm font-semibold text-primary-700 dark:text-primary-300">
                                    Open Survey
                                </span>
                                <svg class="h-4 w-4 text-primary-600 transition-transform group-hover:translate-x-1 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </div>

                        {{-- Hover Glow Effect --}}
                        <div class="absolute inset-0 -z-10 rounded-xl bg-gradient-to-br from-primary-500/10 to-transparent opacity-0 blur-xl transition-opacity group-hover:opacity-100"></div>
                        
                        {{-- External Link Indicator --}}
                        <div class="absolute right-3 top-3 rounded-full bg-white/90 p-1.5 opacity-0 shadow-lg transition-opacity group-hover:opacity-100 dark:bg-gray-800/90">
                            <svg class="h-3.5 w-3.5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Stats Footer --}}
            <div class="mt-6 flex items-center justify-between rounded-xl bg-gray-50 px-6 py-4 dark:bg-gray-800/50">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-900/30">
                            <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Surveys</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalSurveys }}</p>
                        </div>
                    </div>

                    <div class="h-10 w-px bg-gray-300 dark:bg-gray-600"></div>

                    <div class="flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-success-100 dark:bg-success-900/30">
                            <svg class="h-5 w-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Active</p>
                            <p class="text-lg font-bold text-success-600 dark:text-success-400">{{ $activeSurveysCount }}</p>
                        </div>
                    </div>

                    <div class="h-10 w-px bg-gray-300 dark:bg-gray-600"></div>

                    <div class="flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200 dark:bg-gray-700">
                            <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Inactive</p>
                            <p class="text-lg font-bold text-gray-600 dark:text-gray-400">{{ $inactiveSurveysCount }}</p>
                        </div>
                    </div>
                </div>

                @if($createdToday > 0)
                    <div class="flex items-center gap-2 rounded-lg bg-info-100 px-4 py-2 dark:bg-info-900/30">
                        <svg class="h-5 w-5 text-info-600 dark:text-info-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <div>
                            <p class="text-xs text-info-700 dark:text-info-300">New Today</p>
                            <p class="text-lg font-bold text-info-600 dark:text-info-400">+{{ $createdToday }}</p>
                        </div>
                    </div>
                @endif
            </div>

        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 py-20 text-center dark:border-gray-600">
                <div class="mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30">
                    <svg class="h-12 w-12 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">
                    No Active Surveys
                </h3>
                <p class="mb-6 max-w-md text-sm text-gray-600 dark:text-gray-400">
                    There are currently no active surveys. Create a new survey to start collecting responses from your users.
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('filament.admin.resources.surveys.create') }}" 
                       class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-primary-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Survey
                    </a>
                    @if($inactiveSurveysCount > 0)
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            or activate one of {{ $inactiveSurveysCount }} inactive surveys
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>