    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ 
        showModal: false, 
        announcements: [], 
        unreadCount: 0,
        loading: false,
        
        async loadAnnouncements() {
            this.loading = true;
            try {
                const response = await fetch('/announcements');
                const data = await response.json();
                this.announcements = data.announcements;
                this.unreadCount = data.unreadCount;
            } catch (error) {
                console.error('Error:', error);
            }
            this.loading = false;
        },
        
        async markAsRead(id) {
            try {
                const response = await fetch(`/announcements/${id}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const announcement = this.announcements.find(a => a.id === id);
                    if (announcement) {
                        announcement.is_read = true;
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }" 
    x-init="loadAnnouncements()" 
    class="relative">
    
    <!-- Bell Icon Button -->
    <button 
        @click="showModal = !showModal; console.log('Button clicked, showModal:', showModal)" 
        type="button" 
        class="relative p-2 rounded-full hover:bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        <!-- Bell SVG Icon -->
        <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Unread Badge -->
        <span 
            x-show="unreadCount > 0" 
            x-text="unreadCount"
            class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
        </span>
    </button>

    <!-- Modal Dropdown -->
<div 
    x-cloak
    x-show="showModal"
    x-bind:style="showModal ? 'display:block;' : 'display:none;'"
    @click.away="showModal = false"
    x-transition
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="absolute right-0 mt-2 w-96 bg-white shadow-xl border rounded-lg z-50 overflow-hidden">

        
        <!-- Header -->
        <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Announcements</h3>
            <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="max-h-96 overflow-y-auto">
            <!-- Loading State -->
            <div x-show="loading" class="p-8 text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                <p class="text-gray-500 text-sm mt-2">Loading...</p>
            </div>

            <!-- Empty State -->
            <div x-show="!loading && announcements.length === 0" class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 text-sm mt-2">No announcements available</p>
            </div>

            <!-- Announcements List -->
            <template x-for="announcement in announcements" :key="announcement.id">
                <div class="p-4 border-b hover:bg-gray-50 transition">
                    <div class="flex items-start gap-3">
                        <!-- Color Indicator -->
                        <div 
                            class="flex-shrink-0 w-1 h-16 rounded-full"
                            :class="{
                                'bg-blue-500': announcement.type === 'info',
                                'bg-green-500': announcement.type === 'success',
                                'bg-yellow-500': announcement.type === 'warning',
                                'bg-red-500': announcement.type === 'danger'
                            }">
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h4 class="text-sm font-semibold text-gray-900">
                                    <span x-text="announcement.title"></span>
                                    <span x-show="announcement.is_pinned" class="ml-1">📌</span>
                                </h4>
                                
                                <!-- Mark as Read Button -->
                                <button 
                                    x-show="!announcement.is_read"
                                    @click="markAsRead(announcement.id)"
                                    type="button"
                                    class="flex-shrink-0 px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    Mark Read
                                </button>
                                
                                <!-- Read Badge -->
                                <span 
                                    x-show="announcement.is_read"
                                    class="flex-shrink-0 px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">
                                    ✓ Read
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 whitespace-pre-line" x-text="announcement.body"></p>

                            <p class="text-xs text-gray-400 mt-2">
                                <span x-text="new Date(announcement.published_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>