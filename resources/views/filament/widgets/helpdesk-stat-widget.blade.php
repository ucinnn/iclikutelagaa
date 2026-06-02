@php
    $data = $this->getViewData();
    $data = $this->getData();
    $maxValue = max($messagesPerDay);
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                📊 {{ __('dashboard.helpdesk.title') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ __('dashboard.helpdesk.subtitle') }}
            </p>
        </div>

        {{-- Stats Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total Messages --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">{{ __('dashboard.helpdesk.total_tickets') }}</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($data['totalMessages']) }}</p>
                        <p class="text-blue-100 text-xs mt-2">{{ __('dashboard.helpdesk.all_time') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Open Messages --}}
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">{{ __('dashboard.helpdesk.open_tickets') }}</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($data['openMessages']) }}</p>
                        <p class="text-orange-100 text-xs mt-2">
                            <span class="font-semibold">{{ $data['todayOpen'] }}</span> {{ __('dashboard.helpdesk.today') }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Closed Messages --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">{{ __('dashboard.helpdesk.closed_tickets') }}</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($data['closedMessages']) }}</p>
                        <p class="text-green-100 text-xs mt-2">
                            <span class="font-semibold">{{ $data['todayClosed'] }}</span> {{ __('dashboard.helpdesk.today') }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Response Rate --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">{{ __('dashboard.helpdesk.response_rate') }}</p>
                        <p class="text-3xl font-bold mt-2">{{ $data['responseRate'] }}%</p>
                        <p class="text-purple-100 text-xs mt-2">
                            {{ __('dashboard.helpdesk.avg') }}: <span class="font-semibold">{{ $data['avgResponseTime'] }}{{ __('dashboard.helpdesk.hours') }}</span>
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart & Recent Tickets --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Messages Per Day Chart --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        📈 {{ __('dashboard.helpdesk.last_7_days') }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($data['messagesPerDay'] as $index => $count)
                            @php
                                $date = now()->subDays(6 - $index);
                                $maxCount = max($data['messagesPerDay']);
                                $percentage = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">
                                        {{ $date->locale(app()->getLocale())->format('D, d M y') }}
                                    </span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $count }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Recent Open Tickets --}}
            {{-- <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        🎫 {{ __('dashboard.helpdesk.recent_open_tickets') }}
                    </h3>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($data['recentTickets'] as $ticket)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                #{{ $ticket['id'] }}
                                            </span>
                                            @if($ticket['has_admin_reply'])
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    ✓ {{ __('dashboard.helpdesk.replied') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    ! {{ __('dashboard.helpdesk.needs_response') }}
                                                </span>
                                            @endif
                                        </div>
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 truncate">
                                            {{ $ticket['subject'] }}
                                        </h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">
                                            {{ $ticket['message'] }}
                                        </p>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $ticket['user_name'] }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                </svg>
                                                {{ $ticket['user_email'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $ticket['created_at_human'] }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $ticket['replies_count'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('dashboard.helpdesk.no_open_tickets') }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div> --}}
        </div>

        {{-- Performance Indicators --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500 rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-900 dark:text-blue-200">{{ __('dashboard.helpdesk.avg_response_time') }}</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $data['avgResponseTime'] }} {{ __('dashboard.helpdesk.hours') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center gap-3">
                    <div class="bg-green-500 rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-900 dark:text-green-200">{{ __('dashboard.helpdesk.completion_rate') }}</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $data['totalMessages'] > 0 ? round(($data['closedMessages'] / $data['totalMessages']) * 100, 1) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-500 rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-900 dark:text-purple-200">{{ __('dashboard.helpdesk.needs_attention') }}</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $data['openMessages'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>