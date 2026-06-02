<main class="w-full max-w-[1700px] mx-auto px-2 lg:px-4 mt-16 sm:mt-18 md:mt-8 mb-12">
    {{ $slot ?? '' }}
    @yield('content')
</main>
