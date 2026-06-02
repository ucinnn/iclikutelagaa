@php
    $record = $record ?? $this->record ?? $getRecord();
    $mainRecord = $record->parent_id ? $record->parent : $record;
    $replies = $mainRecord->replies()->with('user')->orderBy('created_at')->get();
@endphp

<div class="space-y-3 max-h-96 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">

    {{-- Pesan utama --}}
    <div class="flex justify-start">
        <div class="max-w-[75%]">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-700 dark:text-gray-200">
                    {{ substr($mainRecord->user->name ?? 'U', 0, 1) }}
                </div>
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">{{ $mainRecord->user->name ?? 'Pengguna' }}</span>
                <span class="text-xs text-gray-400 dark:text-gray-400">{{ $mainRecord->created_at->format('d M, H:i') }}</span>
            </div>
            <div class="bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg rounded-tl-sm px-4 py-3 shadow-sm border border-gray-200 dark:border-gray-600 ml-9">
                <p class="text-sm whitespace-pre-line">{{ $mainRecord->message }}</p>
            </div>
        </div>
    </div>

    {{-- Semua balasan --}}
    @forelse($replies as $reply)
        @php $isAdmin = $reply->is_admin_reply; @endphp
        <div class="flex {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[75%]">
                <div class="flex items-center gap-2 mb-2 {{ $isAdmin ? 'flex-row-reverse' : '' }}">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold
                        {{ $isAdmin ? 'bg-blue-600 text-white dark:bg-blue-700' : 'bg-gray-300 text-gray-700 dark:text-gray-200' }}">
                        {{ $isAdmin ? '🛡️' : substr($reply->user->name ?? 'U', 0, 1) }}
                    </div>
                    <span class="text-xs font-semibold {{ $isAdmin ? 'text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-200' }}">
                        {{ $isAdmin ? 'Admin' : ($reply->user->name ?? 'Pengguna') }}
                    </span>
                    <span class="text-xs text-gray-400 dark:text-gray-400">{{ $reply->created_at->format('d M, H:i') }}</span>
                </div>

                {{-- Bubble pesan --}}
                <div class="{{ $isAdmin 
                    ? 'bg-blue-100 text-blue-900 dark:bg-blue-700 dark:text-white rounded-lg rounded-tr-sm mr-9 border border-blue-300 dark:border-blue-600 shadow-sm'
                    : 'bg-white text-gray-800 dark:bg-gray-700 dark:text-gray-100 rounded-lg rounded-tl-sm border border-gray-200 dark:border-gray-600 ml-9 shadow-sm'
                }} px-4 py-3">
                    <p class="text-sm whitespace-pre-line">{{ $reply->message }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-gray-400 dark:text-gray-400">
            <p class="mt-2 text-sm">Belum ada balasan dalam percakapan ini</p>
        </div>
    @endforelse
</div>
