<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Popup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- TailwindCSS CDN (jika belum ada) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <h1 class="text-3xl font-bold text-gray-700">Selamat Datang di Website Kami</h1>

    @if ($popup)
        <!-- Overlay -->
        <div id="popup-news" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl shadow-lg max-w-lg w-full mx-4 relative p-6 animate-fadeIn">
                @if ($popup->image)
                    <img src="{{ asset('storage/'.$popup->image) }}" alt="Popup Image" class="rounded-lg mb-4 w-full object-cover max-h-60">
                @endif

                <h2 class="text-2xl font-semibold mb-3 text-gray-800">{{ $popup->title }}</h2>
                <div class="prose max-w-none text-gray-600 mb-5">
                    {!! $popup->content !!}
                </div>

                <button onclick="closePopup()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                    ✖
                </button>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const popup = document.getElementById('popup-news');
                const hasSeen = localStorage.getItem('seenPopup_{{ $popup->id }}');

                // hanya tampil jika belum pernah dilihat user
                if (!hasSeen) {
                    popup.classList.remove('hidden');
                }
            });

            function closePopup() {
                const popup = document.getElementById('popup-news');
                popup.classList.add('hidden');
                localStorage.setItem('seenPopup_{{ $popup->id }}', true);
            }
        </script>

        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: scale(0.9); }
                to { opacity: 1; transform: scale(1); }
            }
            .animate-fadeIn {
                animation: fadeIn 0.3s ease-out forwards;
            }
        </style>
    @endif

</body>
</html>
