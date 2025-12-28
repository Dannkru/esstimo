<div>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-6 sm:py-8 lg:py-12">
        <!-- Hero Section -->
        <div class="text-center mb-8 sm:mb-10 lg:mb-12">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-3 sm:mb-4 px-2">
                Darmowa Wycena Budowlana
            </h1>
            <p class="text-base sm:text-lg lg:text-xl text-gray-600 dark:text-gray-400 mb-2 px-2">
                Wybierz kategorię prac i oblicz kosztorys w kilka minut
            </p>
            <p class="text-xs sm:text-sm text-indigo-600 dark:text-indigo-400 font-medium px-2">
                Bez logowania • Bezpłatnie • Natychmiast
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
            @foreach($categories as $category)
                @php
                    $colors = $colorClasses[$category['color']] ?? $colorClasses['indigo'];
                @endphp
                    <a
                        href="{{ route('calculator.category', $category['slug']) }}"
                        wire:navigate
                    class="group relative bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-md hover:shadow-xl active:shadow-lg transition-all duration-300 p-4 sm:p-5 lg:p-6 border border-gray-200 dark:border-gray-700 {{ $colors['border'] }} overflow-hidden touch-manipulation"
                >
                    <!-- Background Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br {{ $colors['gradient'] }} opacity-0 group-hover:opacity-100 group-active:opacity-50 transition-opacity duration-300"></div>
                    
                    <div class="relative z-10">
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full {{ $colors['bg'] }} mb-3 sm:mb-4 group-hover:scale-110 group-active:scale-105 transition-transform duration-300">
                            <span class="text-2xl sm:text-3xl">{{ $category['icon'] }}</span>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2 {{ $colors['hoverText'] }} transition-colors">
                            {{ $category['name'] }}
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-3 sm:mb-4">
                            {{ $category['description'] }}
                        </p>
                        
                        <!-- Arrow -->
                        <div class="flex items-center {{ $colors['text'] }} font-medium text-xs sm:text-sm">
                            <span>Rozpocznij wycenę</span>
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Info Section -->
        <div class="mt-8 sm:mt-10 lg:mt-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg sm:rounded-xl p-4 sm:p-5 lg:p-6 border border-indigo-200 dark:border-indigo-800">
            <div class="flex items-start space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        Jak to działa?
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Wybierz kategorię prac, zaznacz potrzebne usługi, podaj ilość i cenę. Nasz kalkulator automatycznie obliczy całkowitą wycenę.
                    </p>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        <strong>Dla zalogowanych użytkowników:</strong> Możliwość zapisywania wycen i dostęp do historii. Abonament tylko 10 zł/mies.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
