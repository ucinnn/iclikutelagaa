{{-- resources/views/components/language-switcher.blade.php --}}
<div class="flex items-center gap-2">
    <a href="{{ route('language.switch', 'id') }}" 
       class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-200
              {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
        <svg class="w-4 h-4" viewBox="0 0 900 600" xmlns="http://www.w3.org/2000/svg">
            <rect width="900" height="600" fill="#e70011"/>
            <rect width="900" height="300" fill="#fff"/>
        </svg>
        <span class="text-sm font-medium">ID</span>
    </a>
    
    <a href="{{ route('language.switch', 'en') }}" 
       class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-200
              {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
        <svg class="w-4 h-4" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
            <clipPath id="s"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
            <clipPath id="t"><path d="M30,15 h30 v15 z v15 h-30 z h-30 v-15 z v-15 h30 z"/></clipPath>
            <g clip-path="url(#s)">
                <path d="M0,0 v30 h60 v-30 z" fill="#012169"/>
                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                <path d="M0,0 L60,30 M60,0 L0,30" clip-path="url(#t)" stroke="#C8102E" stroke-width="4"/>
                <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/>
            </g>
        </svg>
        <span class="text-sm font-medium">EN</span>
    </a>
</div>