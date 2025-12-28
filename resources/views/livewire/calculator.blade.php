<div>
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
                            0,00 zł
                        </p>
                    </div>
                </div>
            </div>

            <!-- Services List -->
            <div class="p-4 sm:p-6">
                <div class="space-y-4">
                    <!-- Placeholder for services - will be replaced with dynamic data in ETAP 2 -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5">
                        <div class="flex items-start space-x-4">
                            <div class="flex items-center h-5">
                                <input 
                                    type="checkbox" 
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
                                    disabled
                                >
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-2">
                                    Przykładowa usługa
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Ilość (m²)
                                        </label>
                                        <input 
                                            type="number" 
                                            placeholder="0" 
                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base"
                                            disabled
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Cena (zł/m²)
                                        </label>
                                        <input 
                                            type="number" 
                                            placeholder="0.00" 
                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base"
                                            disabled
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Wartość
                                        </label>
                                        <div class="w-full rounded-md bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm sm:text-base font-semibold text-gray-900 dark:text-white">
                                            0,00 zł
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info message -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-blue-800 dark:text-blue-300">
                                    <strong>Uwaga:</strong> To jest wersja demonstracyjna. W ETAPIE 2 zostanie dodana pełna funkcjonalność kalkulatora z dynamicznymi usługami z bazy danych.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <button 
                        type="button"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                        disabled
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Drukuj
                    </button>
                    <button 
                        type="button"
                        class="inline-flex items-center justify-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        Zapisz wycenę
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
