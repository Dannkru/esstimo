<div>
    <!-- Print View - Hidden by default, visible only when printing -->
    <div class="hidden print-view" style="display: none;">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Wycena: {{ $categoryName }}
            </h1>
            <p class="text-sm text-gray-600">
                Data wyceny: {{ now()->format('d.m.Y') }}
            </p>
        </div>

        @if(count($this->selectedServicesForPrint) > 0)
            <div class="mb-6">
                <table class="w-full border-collapse">
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
                        @foreach($this->selectedServicesForPrint as $index => $service)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $service['name'] }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-center text-gray-900">{{ number_format($service['quantity'], 2, ',', ' ') }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-center text-gray-900">{{ $service['unit'] }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-right text-gray-900">{{ number_format($service['price'], 2, ',', ' ') }} zł</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-right font-semibold text-gray-900">{{ number_format($service['total'], 2, ',', ' ') }} zł</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50 font-bold">
                            <td colspan="5" class="border border-gray-300 px-4 py-3 text-right text-sm text-gray-900">SUMA CAŁKOWITA:</td>
                            <td class="border border-gray-300 px-4 py-3 text-right text-lg text-gray-900">{{ number_format($this->total, 2, ',', ' ') }} zł</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">Brak zaznaczonych usług do wyceny.</p>
        @endif
    </div>

    <!-- Normal View - Hidden when printing -->
    <div class="print-hidden">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-6 sm:py-8 lg:py-12">
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
                            Kalkulator: {{ $categoryName }}
                        </h1>
                    </div>
                </div>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                    Zaznacz usługi, podaj ilość i cenę. Kalkulator automatycznie obliczy całkowitą wycenę.
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
                            Wybierz usługi i podaj parametry
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

            <!-- Services List -->
            <div class="p-4 sm:p-6">
                @if(count($services) > 0)
                    <div class="space-y-4">
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
                                            <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-2">
                                                {{ $service['name'] }}
                                            </h3>
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
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">
                            Brak dostępnych usług dla tej kategorii.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Actions Footer -->
            <div class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <button 
                        type="button"
                        wire:click="printEstimate"
                        onclick="window.print()"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors active:bg-gray-100 dark:active:bg-gray-600"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Drukuj
                    </button>
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
</script>
@endscript
