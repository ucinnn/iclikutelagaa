<x-layouts.app>
    @section('pageTitle', 'Edit Profile')

    {{-- Header Sticky --}}
    <header class="sticky top-0 z-50 bg-white shadow"></header>

    {{-- Konten Utama --}}
    <x-layouts.main>
        <div class="w-full max-w-[1600px] mx-auto px-6 lg:px-12 py-8 grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- Konten Utama (Artikel & Entertainment) --}}
            <section class="lg:col-span-3 bg-white rounded-xl shadow-sm p-6">
         
            </section>

            {{-- Sidebar --}}
            <aside class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                <x-layouts.rightsidesection />
            </aside>

        </div>
    </x-layouts.main>

    {{-- Footer --}}
    <footer class="mt-10 py-6 text-center"></footer>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            const myBtn = document.getElementById('myBtn');
            if (myBtn) {
                window.addEventListener('scroll', () => {
                    myBtn.classList.toggle('hidden', window.scrollY < 200);
                });
                myBtn.addEventListener('click', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    </script>
</x-layouts.app>
