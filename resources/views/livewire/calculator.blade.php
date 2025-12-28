<div>
    <!-- Print View - Hidden by default, visible only when printing -->
    <div class="hidden print-view" style="display: none;">
        <!-- Logo Header -->
        <div class="mb-6 pb-4 border-b-2 border-gray-300">
            <div class="flex items-start">
                <!-- Logo SVG -->
                <svg width="120" height="50" viewBox="0 0 200 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Icon container with gradient background -->
                    <defs>
                        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#4F46E5;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#6366F1;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    
                    <!-- Building icon with shadow -->
                    <rect x="5" y="15" width="50" height="50" rx="6" fill="url(#logoGradient)"/>
                    <rect x="15" y="25" width="10" height="10" rx="1" fill="white" opacity="0.9"/>
                    <rect x="30" y="25" width="10" height="10" rx="1" fill="white" opacity="0.9"/>
                    <rect x="15" y="40" width="10" height="10" rx="1" fill="white" opacity="0.9"/>
                    <rect x="30" y="40" width="10" height="10" rx="1" fill="white" opacity="0.9"/>
                    <rect x="22.5" y="52" width="10" height="8" rx="1" fill="white" opacity="0.9"/>
                    
                    <!-- Text Estimo -->
                    <text x="70" y="50" font-family="Arial, sans-serif" font-size="36" font-weight="bold" fill="#1F2937" letter-spacing="2">ESTIMO</text>
                    
                    <!-- Decorative line -->
                    <line x1="70" y1="58" x2="195" y2="58" stroke="#4F46E5" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        @if(count($this->selectedServicesForPrint) > 0)
            <!-- Total Summary in Print View -->
            <div class="mb-6 pb-4 border-b-2 border-gray-300">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Suma całkowita wyceny</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($this->total, 2, ',', ' ') }} zł</p>
                </div>
            </div>
            
            @foreach($this->selectedServicesForPrint as $categoryGroup)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-gray-300">
                        {{ $categoryGroup['category'] }}
                    </h2>
                    <table class="w-full border-collapse mb-6">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-900">Lp.</th>
                                <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-900">Nazwa usługi</th>
                                <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-900">Ilość</th>
                                <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-900">Jedn.</th>
                                <th class="border border-gray-300 px-4 py-2 text-right text-sm font-semibold text-gray-900">Cena</th>
                                <th class="border border-gray-300 px-4 py-2 text-right text-sm font-semibold text-gray-900">Wartość</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryGroup['services'] as $index => $service)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $service['name'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-center text-gray-900">{{ number_format($service['quantity'], 2, ',', ' ') }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-center text-gray-900">{{ $service['unit'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-right text-gray-900">{{ number_format($service['price'], 2, ',', ' ') }} zł</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-right font-semibold text-gray-900">{{ number_format($service['total'], 2, ',', ' ') }} zł</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
            
            <div class="mt-6 pt-4 border-t-2 border-gray-300">
                <table class="w-full">
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="5" class="px-4 py-3 text-right text-lg text-gray-900">SUMA CAŁKOWITA:</td>
                        <td class="px-4 py-3 text-right text-xl text-gray-900">{{ number_format($this->total, 2, ',', ' ') }} zł</td>
                    </tr>
                </table>
            </div>
        @else
            <p class="text-gray-600">Brak zaznaczonych usług do wyceny.</p>
        @endif
    </div>

    <!-- Normal View - Hidden when printing -->
    <div class="print-hidden">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-6 sm:py-8 lg:py-12">
            <!-- Success/Error Messages -->
            <div x-data="{ show: false, message: '', type: '' }" 
                 x-show="show"
                 x-cloak
                 @show-success.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 5000)"
                 @show-error.window="show = true; message = $event.detail.message; type = 'error'; setTimeout(() => show = false, 5000)"
                 class="mb-4">
                <div x-show="type === 'success'" 
                     x-transition
                     class="p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span x-text="message" class="text-sm font-medium"></span>
                    </div>
                </div>
                <div x-show="type === 'error'" 
                     x-transition
                     class="p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span x-text="message" class="text-sm font-medium"></span>
                    </div>
                </div>
            </div>
            
            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 mb-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Powrót do kategorii
                        </a>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                            Kalkulator Wycen Budowlanych
                        </h1>
                    </div>
                </div>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                    Wybierz kategorie i zaznacz usługi. Możesz wybrać usługi z wielu kategorii w jednej wycenie.
                </p>
            </div>

            <!-- Calculator Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Summary Header -->
                <div class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                                Podsumowanie wyceny
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Wybierz kategorie i usługi, podaj parametry
                            </p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-1">Suma całkowita</p>
                            <p class="text-2xl sm:text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ number_format($this->total, 2, ',', ' ') }} zł
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Categories and Services List -->
                <div class="p-4 sm:p-6">
                    <div class="space-y-6">
                        @foreach($categories as $category)
                            @php
                                $services = $this->getServicesForCategory($category['slug']);
                                $isExpanded = $expandedCategories[$category['slug']] ?? false;
                                $hasSelectedServices = false;
                                
                                // Check if category has any selected services
                                foreach ($services as $service) {
                                    if (isset($selectedServices[$service['id']]) && $selectedServices[$service['id']]) {
                                        $hasSelectedServices = true;
                                        break;
                                    }
                                }
                            @endphp
                            
                            <div 
                                id="category-{{ $category['slug'] }}"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden scroll-mt-24"
                            >
                                <!-- Category Header -->
                                <button
                                    type="button"
                                    wire:click="toggleCategory('{{ $category['slug'] }}')"
                                    class="w-full flex items-center justify-between p-4 sm:p-5 bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">{{ $category['icon'] }}</span>
                                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                                            {{ $category['name'] }}
                                        </h3>
                                    </div>
                                    <svg 
                                        class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform {{ $isExpanded ? 'rotate-180' : '' }}"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Services List -->
                                @if($isExpanded)
                                    <div class="p-4 sm:p-5 space-y-4 bg-white dark:bg-gray-800">
                                        @if(count($services) > 0)
                                            @foreach($services as $service)
                                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors {{ isset($selectedServices[$service['id']]) && $selectedServices[$service['id']] ? 'bg-indigo-50/50 dark:bg-indigo-900/10 border-indigo-300 dark:border-indigo-600' : '' }}">
                                                    <div class="flex items-start space-x-4">
                                                        <!-- Checkbox -->
                                                        <div class="flex items-center h-5 pt-1">
                                                            <input 
                                                                type="checkbox" 
                                                                wire:model.live="selectedServices.{{ $service['id'] }}"
                                                                class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 cursor-pointer"
                                                                id="service-{{ $service['id'] }}"
                                                            >
                                                        </div>
                                                        
                                                        <!-- Service Details -->
                                                        <div class="flex-1 min-w-0">
                                                            <label for="service-{{ $service['id'] }}" class="cursor-pointer">
                                                                <h4 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-2">
                                                                    {{ $service['name'] }}
                                                                </h4>
                                                            </label>
                                                            
                                                            @if(isset($selectedServices[$service['id']]) && $selectedServices[$service['id']])
                                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                                    <!-- Quantity Input -->
                                                                    <div>
                                                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                            Ilość ({{ $service['unit'] }})
                                                                        </label>
                                                                        <input 
                                                                            type="number" 
                                                                            step="0.01"
                                                                            min="0"
                                                                            wire:model.live="quantities.{{ $service['id'] }}"
                                                                            placeholder="0" 
                                                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base"
                                                                        >
                                                                    </div>
                                                                    
                                                                    <!-- Price Input -->
                                                                    <div>
                                                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                            Cena (zł/{{ $service['unit'] }})
                                                                        </label>
                                                                        <input 
                                                                            type="number" 
                                                                            step="0.01"
                                                                            min="0"
                                                                            wire:model.live="prices.{{ $service['id'] }}"
                                                                            placeholder="{{ number_format($service['suggested_price'], 2, ',', ' ') }}" 
                                                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base"
                                                                        >
                                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                                            Sugerowana: {{ number_format($service['suggested_price'], 2, ',', ' ') }} zł
                                                                        </p>
                                                                    </div>
                                                                    
                                                                    <!-- Total Value -->
                                                                    <div>
                                                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                            Wartość
                                                                        </label>
                                                                        <div class="w-full rounded-md bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm sm:text-base font-semibold text-gray-900 dark:text-white">
                                                                            {{ number_format($this->getServiceTotal($service['id']), 2, ',', ' ') }} zł
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                                                    Zaznacz, aby dodać do wyceny
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                                Brak dostępnych usług w tej kategorii.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions Footer -->
                <div class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <!-- Total Summary -->
                            <div class="flex items-center space-x-3">
                                <div class="text-left">
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Suma całkowita</p>
                                    <p class="text-xl sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($this->total, 2, ',', ' ') }} zł
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Print/PDF Button -->
                            <button 
                                type="button"
                                wire:click="printEstimate"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors active:bg-gray-100 dark:active:bg-gray-600"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Drukuj lub zapisz jako PDF
                            </button>
                            
                            <!-- Save to Disk Button -->
                            <button 
                                type="button"
                                wire:click="exportEstimate"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors active:bg-gray-100 dark:active:bg-gray-600"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Zapisz na dysku
                            </button>
                        </div>
                        
                        <!-- Save Button -->
                        <button 
                            type="button"
                            class="inline-flex items-center justify-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed active:bg-indigo-800"
                            disabled
                            title="Funkcjonalność dostępna po zalogowaniu"
                        >
                            Zapisz wycenę
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('print-estimate', () => {
        window.print();
    });

    // Function to scroll to category
    function scrollToCategory(categorySlug) {
        const categoryElement = document.getElementById('category-' + categorySlug);
        
        if (categoryElement) {
            // Wait for Livewire to finish rendering
            setTimeout(() => {
                const yOffset = -80; // Offset for fixed header
                const y = categoryElement.getBoundingClientRect().top + window.pageYOffset + yOffset;
                
                window.scrollTo({
                    top: y,
                    behavior: 'smooth'
                });
                
                // Add highlight effect
                categoryElement.classList.add('ring-2', 'ring-indigo-500', 'ring-offset-2', 'transition-all');
                setTimeout(() => {
                    categoryElement.classList.remove('ring-2', 'ring-indigo-500', 'ring-offset-2');
                }, 2000);
            }, 200);
        }
    }

    // Track if we've already scrolled to avoid repeated scrolling
    let hasScrolledToCategory = false;

    // Handle Livewire navigation (wire:navigate) - only scroll once on initial navigation
    document.addEventListener('livewire:navigated', () => {
        @if($categorySlug)
            if (!hasScrolledToCategory) {
                setTimeout(() => {
                    scrollToCategory('{{ $categorySlug }}');
                    hasScrolledToCategory = true;
                }, 100);
            }
        @endif
    });

    // Handle initial page load (for direct URL access) - only scroll once
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            @if($categorySlug)
                if (!hasScrolledToCategory) {
                    setTimeout(() => {
                        scrollToCategory('{{ $categorySlug }}');
                        hasScrolledToCategory = true;
                    }, 300);
                }
            @endif
        });
    } else {
        // DOM already loaded - only scroll once
        @if($categorySlug)
            if (!hasScrolledToCategory) {
                setTimeout(() => {
                    scrollToCategory('{{ $categorySlug }}');
                    hasScrolledToCategory = true;
                }, 300);
            }
        @endif
    }
</script>
@endscript
