<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Darmowa Wycena Budowlana
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-2">
                Wybierz kategorię prac i oblicz kosztorys w kilka minut
            </p>
            <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">
                Bez logowania • Bezpłatnie • Natychmiast
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                @php
                    $colors = $colorClasses[$category['color']] ?? $colorClasses['indigo'];
                @endphp
                <a 
                    href="{{ route('calculator', $category['slug']) }}" 
                    wire:navigate
                    class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-200 dark:border-gray-700 {{ $colors['border'] }} overflow-hidden"
                >
                    <!-- Background Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br {{ $colors['gradient'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="relative z-10">
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-16 h-16 rounded-full {{ $colors['bg'] }} mb-4 group-hover:scale-110 transition-transform duration-300">
                            <span class="text-3xl">{{ $category['icon'] }}</span>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 {{ $colors['hoverText'] }} transition-colors">
                            {{ $category['name'] }}
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ $category['description'] }}
                        </p>
                        
                        <!-- Arrow -->
                        <div class="flex items-center {{ $colors['text'] }} font-medium text-sm">
                            <span>Rozpocznij wycenę</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Info Section -->
        <div class="mt-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-6 border border-indigo-200 dark:border-indigo-800">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        Jak to działa?
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Wybierz kategorię prac, zaznacz potrzebne usługi, podaj ilość i cenę. Nasz kalkulator automatycznie obliczy całkowitą wycenę.
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Dla zalogowanych użytkowników:</strong> Możliwość zapisywania wycen i dostęp do historii. Abonament tylko 10 zł/mies.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
