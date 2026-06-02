<x-layouts.app>
    @section('pageTitle', 'Helpdesk - Support Center')

    {{-- Header Sticky --}}
    <header class="sticky top-0 z-50 bg-white shadow"></header>

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white py-16 sm:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6 backdrop-blur-sm">
                <i class="fas fa-headset text-4xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">Pusat Bantuan</h1>
            <p class="text-base sm:text-lg text-blue-100 max-w-2xl mx-auto">
                Butuh bantuan? Kami siap membantu Anda 24/7. Kirimkan pesan dan tim kami akan segera merespons secepat mungkin.
            </p>
        </div>
    </section>

    {{-- Main Content --}}
    <x-layouts.main>
        <div class="container mx-auto py-8 sm:py-12 px-0 sm:px-6 lg:px-12">

            {{-- Alert Success/Error --}}
            @if (session('success'))
                <div class="max-w-4xl mx-auto mb-6 px-4 sm:px-0">
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm animate-fade-in">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-4xl mx-auto mb-6 px-4 sm:px-0">
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm animate-fade-in">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Grid --}}
            <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 px-4 sm:px-0">

                {{-- New Ticket Form --}}
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <div class="bg-white rounded-none sm:rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-paper-plane mr-3"></i>
                                Buat Topik Baru
                            </h2>
                        </div>

                        <form action="{{ route('helpdesk.store') }}" method="POST" class="p-6 space-y-5">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-blue-600"></i>Topik
                                </label>
                                <select name="subject"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('subject') border-red-500 @enderror"
                                    required>
                                
                                    <option value="">-- Pilih Topik --</option>
                                    <option value="HR" {{ old('subject') == 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="HSE" {{ old('subject') == 'HSE' ? 'selected' : '' }}>HSE</option>
                                    <option value="Umum" {{ old('subject') == 'Umum' ? 'selected' : '' }}>Umum</option>
                                
                                </select>
                                
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-comment-dots mr-2 text-blue-600"></i>Pesan
                                </label>
                                <textarea name="message"
                                          rows="5"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none @error('message') border-red-500 @enderror"
                                          placeholder="Tuliskan ringkasan singkat masalah Anda..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>Berikan detail selengkap mungkin agar kami dapat membantu dengan lebih baik.
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>Waktu respons rata-rata 2-4 jam
                                </div>
                                <button type="submit"
                                        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-200 shadow-md hover:shadow-lg flex items-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Quick Info Sidebar --}}
                <div class="lg:col-span-1 order-1 lg:order-2 space-y-6">
                    {{-- Support Hours --}}
                    <div class="bg-white rounded-none sm:rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-clock text-blue-600 mr-2"></i>
                            Jam Dukungan Balasan Cepat
                        </h3>
                        <div class="space-y-3 text-sm">
                                <div class="flex items-center text-gray-700">
                                <i class="fas fa-calendar-day text-blue-600 w-5 mr-2"></i>
                                <span class="font-medium">Senin - Jumat:</span>
                                <span class="ml-auto">08.00 - 17.00</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-calendar-week text-blue-600 w-5 mr-2"></i>
                                <span class="font-medium">Akhir Pekan:</span>
                                <span class="ml-auto">Tutup</span>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Methods --}}
                    <div class="bg-white rounded-none sm:rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-phone-alt text-blue-600 mr-2"></i>
                            Cara Lain Menghubungi Kami
                        </h3>
                        <div class="space-y-3 text-sm">
                            <a href="mailto:{{ env('APP_SITEMAIL') }}" class="flex items-center text-gray-700 hover:text-blue-600 transition">
                                <i class="fas fa-envelope text-blue-600 w-5 mr-2"></i>
                                {{ env('APP_SITEMAIL') }}
                            </a>
                            <a href="/faq" class="flex items-center text-gray-700 hover:text-blue-600 transition">
                                <i class="fas fa-question-circle text-blue-600 w-5 mr-2"></i>
                                Kunjungi FAQ
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Ticket History (Chat Style) --}}
            <div class="max-w-4xl mx-auto mt-8 px-4 sm:px-0">
                <div class="bg-white rounded-none sm:rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-history mr-3"></i>
                           Topik Kamu
                        </h2>
                    </div>

                    <div class="p-6">
                        @forelse ($messages as $ticket)
                            <div class="border rounded-lg shadow-sm mb-6 bg-white overflow-hidden">
                                {{-- Ticket Header --}}
                                <div class="px-5 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="font-bold text-lg text-gray-800">{{ $ticket->subject }}</h3>
                                                @if($ticket->status === 'closed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-lock mr-1"></i> Tutup
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-circle text-green-500 mr-1" style="font-size: 6px;"></i> Buka
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $ticket->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Chat Thread Container --}}
                                <div class="bg-gray-50 px-5 py-4 max-h-[500px] overflow-y-auto space-y-3 chat-container">
                                    {{-- Initial Message (Ticket dari user) --}}
                                    <div class="flex justify-start">
                                        <div class="max-w-[80%]">
                                            <div class="flex items-center gap-2 mb-1 px-2">
                                                <span class="text-xs font-medium text-gray-600">Kamu</span>
                                            </div>
                                            <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                                                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $ticket->message }}</p>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1 px-2">
                                                <span class="text-xs text-gray-500">{{ $ticket->created_at->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Semua balasan (admin & user) --}}
                                    @foreach($ticket->replies as $reply)
                                        @if($reply->is_admin_reply)
                                            {{-- Admin Reply --}}
                                            <div class="flex justify-end">
                                                <div class="max-w-[80%]">
                                                    <div class="flex items-center gap-2 mb-1 px-2 justify-end">
                                                        <span class="text-xs font-medium text-blue-600">Minleh</span>
                                                    </div>
                                                    <div class="bg-blue-600 text-white rounded-2xl rounded-tr-sm px-4 py-3 shadow-sm">
                                                        <p class="text-sm whitespace-pre-line">{{ $reply->message }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-2 mt-1 px-2 justify-end">
                                                        <span class="text-xs text-blue-200">{{ $reply->created_at->format('H:i') }}</span>
                                                        <i class="fas fa-check-double text-xs text-blue-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- User Reply --}}
                                            <div class="flex justify-start">
                                                <div class="max-w-[80%]">
                                                    <div class="flex items-center gap-2 mb-1 px-2">
                                                        <span class="text-xs font-medium text-gray-600">You</span>
                                                    </div>
                                                    <div class="bg-white text-gray-800 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                                                        <p class="text-sm whitespace-pre-line">{{ $reply->message }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-2 mt-1 px-2">
                                                        <span class="text-xs text-gray-500">{{ $reply->created_at->format('H:i') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>


                                {{-- Reply Form --}}
                                <div class="px-5 py-4 border-t bg-white">
                                    @if($ticket->status === 'open')
                                        <form method="POST" action="{{ route('helpdesk.store') }}" class="flex gap-3">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $ticket->id }}">
                                            <div class="flex-1">
                                                <textarea
                                                    name="message"
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                                                    rows="2"
                                                    placeholder="Ketik balasan kamu"
                                                    required></textarea>
                                            </div>
                                            <button
                                                type="submit"
                                                class="self-end bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2 font-medium">
                                                <i class="fas fa-paper-plane"></i>
                                                <span class="hidden sm:inline">Kirim</span>
                                            </button>
                                        </form>
                                    @else
                                        <div class="text-center py-3">
                                            <div class="inline-flex items-center px-4 py-2 rounded-lg bg-red-50 text-red-600 font-medium text-sm">
                                                <i class="fas fa-lock mr-2"></i>
                                                Topik ini telah ditutup oleh admin, kamu tidak dapat melakukan balasan lagi, buat topik baru atau hubungi kontak bantuan kami                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Topik</h3>
                                <p class="text-gray-500 mb-6">Anda belum pernah mengirimkan Topik bantuan. Butuh bantuan? Buat Topik pertama Anda di atas!</p>
                                <button onclick="document.querySelector('textarea[name=message]').focus()" class="text-blue-600 hover:text-blue-700 font-semibold">
                                    <i class="fas fa-arrow-up mr-2"></i>Buat Topik Baru
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </x-layouts.main>

    @push('styles')
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Custom scrollbar */
        textarea::-webkit-scrollbar,
        .chat-container::-webkit-scrollbar {
            width: 8px;
        }

        textarea::-webkit-scrollbar-track,
        .chat-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        textarea::-webkit-scrollbar-thumb,
        .chat-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        textarea::-webkit-scrollbar-thumb:hover,
        .chat-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-resize textarea
            document.querySelectorAll('textarea[name="message"]').forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });

            // Auto-hide alerts after 5 seconds
            document.querySelectorAll('.animate-fade-in').forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // Scroll to bottom of chat threads
            document.querySelectorAll('.chat-container').forEach(container => {
                container.scrollTop = container.scrollHeight;
            });

            // Prevent form double submission
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn.disabled) {
                        e.preventDefault();
                        return false;
                    }
                    submitBtn.disabled = true;
                    const originalHTML = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span class="hidden sm:inline">Sending...</span>';
                });
            });
        });
    </script>
    @endpush
</x-layouts.app>
