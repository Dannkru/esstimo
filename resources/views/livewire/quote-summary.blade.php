<div>
    <style>
        @keyframes price-panel-fade-in {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .price-panel-expand {
            animation: price-panel-fade-in 0.25s ease-out;
        }
    </style>
    <h1 class="text-xl font-semibold mb-2 text-white">Podsumowanie sesji projektowej</h1>
    <p class="text-white text-sm mb-6">Lista dodanych etapów oraz zbiorcze zapotrzebowanie na materiały.</p>

    @if(session('message'))
        <p class="mb-4 text-amber-700 dark:text-amber-200 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 rounded-lg px-3 py-2 text-sm">{{ session('message') }}</p>
    @endif

    @if(empty($items))
        <p class="text-white py-6">Brak pozycji. <a href="{{ route('materials.app') }}" class="text-indigo-600 dark:text-indigo-400 font-medium">Dodaj pomieszczenia w kalkulatorze</a>.</p>
        <p class="mt-4">
            <a href="{{ route('materials.app') }}" class="inline-block rounded-lg bg-indigo-600 dark:bg-indigo-500 text-white font-medium py-2 px-4 hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">Przejdź do kalkulatora</a>
        </p>
    @else
        <section class="mb-8">
            <h2 class="text-lg font-semibold mb-3 text-white">Rozbicie na pomieszczenia</h2>
            <ul class="space-y-4">
                @foreach($items as $index => $item)
                    @php
                        $labels = \App\Livewire\QuoteSummary::labelsForCategory($item['category_key'] ?? 'other');
                    @endphp
                    <li class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <span class="font-medium">{{ $item['room_name'] ?? 'Bez nazwy' }}</span>
                                <span class="text-white text-sm"> – {{ $item['category'] ?? '' }}</span>
                            </div>
                            <button type="button" wire:click="removeItem('{{ $item['id'] }}')"
                                    class="text-red-600 hover:text-red-800 text-sm shrink-0"
                                    wire:confirm="Usunąć tę pozycję?">
                                Usuń
                            </button>
                        </div>
                        <ul class="mt-2 space-y-1 text-sm">
                            @foreach(($item['materials'] ?? []) as $key => $val)
                                @if($key !== 'meta' && (is_numeric($val) && ($val > 0 || ($val == 0 && in_array($key, ['fuga_kg', 'laczniki'])))))
                                    @php
                                        $rowId = 'item_' . $item['id'] . '_' . $key;
                                        $materialName = $labels[$key] ?? $key;
                                        $searchQuery = $searchTerms[$key] ?? $materialName;
                                        $query = urlencode($searchQuery);
                                    @endphp
                                    <li class="text-white">
                                        <div class="flex justify-between items-center gap-2">
                                            <span>{{ $materialName }}</span>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <span>{{ $this->formatMaterialValue($key, $val) }}</span>
                                                <button type="button"
                                                        wire:click="toggleRow('{{ $rowId }}')"
                                                        wire:loading.attr="disabled"
                                                        class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <span wire:loading.remove wire:target="toggleRow">Sprawdź ceny</span>
                                                    <span wire:loading wire:target="toggleRow">...</span>
                                                </button>
                                            </div>
                                        </div>
                                        @if(in_array($rowId, $this->expandedRows))
                                            <div class="price-panel-expand mt-2 grid grid-cols-2 md:grid-cols-4 gap-2">
                                                <a href="https://allegro.pl/listing?string={{ $query }}" target="_blank" rel="noopener"
                                                   class="inline-flex items-center justify-center rounded-lg bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-500 text-white text-xs sm:text-sm font-medium py-2 px-2 transition text-center"
                                                   title="Sprawdź ceny na Allegro">Sprawdź ceny na Allegro</a>
                                                <a href="https://www.castorama.pl/search?term={{ $query }}" target="_blank" rel="noopener"
                                                   class="inline-flex items-center justify-center rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium py-2 px-2 transition text-center"
                                                   title="Castorama">Castorama</a>
                                                <a href="https://www.google.pl/search?q={{ urlencode('site:leroymerlin.pl ' . $searchQuery) }}" target="_blank" rel="noopener"
                                                   class="inline-flex items-center justify-center rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-medium py-2 px-2 transition text-center"
                                                   title="Leroy Merlin">Leroy Merlin</a>
                                                <a href="https://www.obi.pl/search/{{ $query }}" target="_blank" rel="noopener"
                                                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white text-xs sm:text-sm font-medium py-2 px-2 transition text-center"
                                                   title="OBI">OBI</a>
                                            </div>
                                            <p class="text-xs text-white mt-3 text-center italic">Ceny i dostępność mogą się różnić lokalnie.</p>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-lg font-semibold mb-3 text-white">Całkowite zapotrzebowanie na inwestycję</h2>
            <ul class="border border-gray-200 dark:border-gray-700 rounded-lg divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
                @foreach($aggregated as $key => $val)
                    @if($val > 0 || ($val == 0 && in_array($key, ['fuga_kg', 'laczniki'])))
                        @php
                            $rowId = 'agg_' . $key;
                            $materialName = $mergedLabels[$key] ?? $key;
                            $searchQuery = $searchTerms[$key] ?? $materialName;
                            $query = urlencode($searchQuery);
                        @endphp
                        <li class="px-4 py-3">
                            <div class="flex justify-between items-center gap-2">
                                <span>{{ $materialName }}</span>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="font-medium">{{ $this->formatMaterialValue($key, $val) }}</span>
                                    <button type="button"
                                            wire:click="toggleRow('{{ $rowId }}')"
                                            wire:loading.attr="disabled"
                                            class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="toggleRow">Sprawdź ceny</span>
                                        <span wire:loading wire:target="toggleRow">...</span>
                                    </button>
                                </div>
                            </div>
                            @if(in_array($rowId, $this->expandedRows))
                                <div class="price-panel-expand mt-2 grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <a href="https://allegro.pl/listing?string={{ $query }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center justify-center rounded-lg bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-500 text-white text-xs sm:text-sm font-medium py-2 px-2 transition text-center">Allegro</a>
                                    <a href="https://www.castorama.pl/search?term={{ $query }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center justify-center rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium py-2 px-2 transition text-center">Castorama</a>
                                    <a href="https://www.google.pl/search?q={{ urlencode('site:leroymerlin.pl ' . $searchQuery) }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center justify-center rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-medium py-2 px-2 transition text-center">Leroy Merlin</a>
                                    <a href="https://www.obi.pl/search/{{ $query }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center justify-center rounded-lg bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white text-xs sm:text-sm font-medium py-2 px-2 transition text-center">OBI</a>
                                </div>
                                <p class="text-xs text-white mt-3 text-center italic">Ceny i dostępność mogą się różnić lokalnie.</p>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </section>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('materials.app') }}" class="inline-block rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 font-medium text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                Dodaj kolejne pomieszczenie
            </a>
            <a href="{{ route('quote.pdf') }}" target="_blank" rel="noopener"
               class="inline-block rounded-lg bg-indigo-600 dark:bg-indigo-500 text-white font-medium px-4 py-2 hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                Pobierz PDF
            </a>
        </div>
    @endif
</div>
