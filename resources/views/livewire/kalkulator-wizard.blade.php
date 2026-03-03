<div class="kalkulator-wizard">
    {{-- Nawigacja wstecz --}}
    @if($step !== 'main')
        <p class="mb-4">
            <button type="button" wire:click="backToMain" class="text-sm text-stone-500 hover:text-stone-800">← Od początku</button>
        </p>
    @endif

    @if($step === 'main')
        <h1 class="text-xl font-semibold mb-2">Co chcesz zrobić?</h1>
        <p class="text-stone-600 text-sm mb-6">Wybierz kategorię remontu.</p>
        <div class="grid gap-3">
            <button type="button" wire:click="selectCategory('sucha_zabudowa')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Sucha zabudowa</span>
            </button>
            <button type="button" wire:click="selectCategory('malowanie')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-stone-400 hover:bg-stone-800 transition">
                <span class="font-medium">Malowanie – szpachlowanie</span>
            </button>
            <button type="button" wire:click="selectCategory('podlogi')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-stone-400 hover:bg-stone-800 transition">
                <span class="font-medium">Podłogi</span>
            </button>
            <button type="button" wire:click="selectCategory('lazienka')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Łazienka – hydroizolacja</span>
            </button>
            <button type="button" wire:click="selectCategory('ocieplenie')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Ocieplanie dachów (poddasze)</span>
            </button>
        </div>
    @endif

    @if($step === 'drywall')
        <p class="mb-2"><button type="button" wire:click="backToMain" class="text-sm text-stone-500">← Sucha zabudowa</button></p>
        <h2 class="text-lg font-semibold mb-4">Sucha zabudowa – co robisz?</h2>
        <div class="grid gap-3">
            <button type="button" wire:click="selectDrywall('sufit_podwieszany')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Sufit podwieszany</span>
            </button>
            <button type="button" wire:click="selectDrywall('scianka_dzialowa')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-stone-400 hover:bg-stone-800 transition">
                <span class="font-medium">Ścianka działowa</span>
            </button>
        </div>
    @endif

    @if($step === 'ceiling_type')
        <p class="mb-2"><button type="button" wire:click="backToDrywall" class="text-sm text-stone-500">← Sufit podwieszany</button></p>
        <h2 class="text-lg font-semibold mb-4">Sufit podwieszany – jaki typ?</h2>
        <div class="grid gap-3">
            <button type="button" wire:click="selectCeilingType('krzyzowy')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Krzyżowy</span>
            </button>
            <button type="button" wire:click="selectCeilingType('zwykly')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Zwykły</span>
            </button>
        </div>
    @endif

    @if($step === 'dimensions')
        <p class="mb-2"><button type="button" wire:click="backToCeilingType" class="text-sm text-stone-500">← {{ $selectedCeilingType === 'krzyzowy' ? 'Krzyżowy' : 'Zwykły' }}</button></p>
        <h2 class="text-lg font-semibold mb-4">Wymiary pomieszczenia</h2>
        <form wire:submit="calculate" class="space-y-4">
            <div>
                <label for="length" class="block text-sm font-medium text-stone-700 mb-1">Długość (m)</label>
                <input type="number" id="length" wire:model="length" step="0.1" min="0.1" max="100"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('length') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="width" class="block text-sm font-medium text-stone-700 mb-1">Szerokość (m)</label>
                <input type="number" id="width" wire:model="width" step="0.1" min="0.1" max="100"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('width') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model="profile4m" class="rounded border-stone-300">
                <span class="text-sm">Profile po 4 m (domyślnie 3 m)</span>
            </label>
            <button type="submit" class="w-full rounded-lg bg-orange-600 text-white font-medium py-2.5 hover:bg-orange-700 transition">
                Oblicz materiały
            </button>
        </form>
    @endif

    @if($step === 'result')
        <p class="mb-2"><button type="button" wire:click="backToDimensions" class="text-sm text-stone-500">← Wymiary</button></p>
        <h2 class="text-lg font-semibold mb-4">Potrzebne materiały</h2>
        <ul class="space-y-2 border-t border-stone-200 pt-4">
            @foreach ($labels as $key => $label)
                @if (isset($result[$key]) && $key !== 'meta' && ($result[$key] > 0 || $key === 'laczniki'))
                    <li class="flex justify-between py-2 border-b border-stone-100">
                        <span>{{ $label }}</span>
                        <span class="font-medium">{{ $result[$key] }} szt.</span>
                    </li>
                @endif
            @endforeach
        </ul>
        @if(!empty($result['meta']))
            <p class="mt-4 text-sm text-stone-500">Powierzchnia: {{ $result['meta']['area_m2'] }} m² · Obwód: {{ $result['meta']['perimeter_m'] }} m</p>
        @endif
        <div class="mt-6 pt-4 border-t border-stone-200 space-y-3">
            <div>
                <label for="room_name_ceiling" class="block text-sm font-medium text-stone-700 mb-1">Nazwa pomieszczenia / etapu</label>
                <input type="text" id="room_name_ceiling" wire:model="room_name" maxlength="200" placeholder="np. Kuchnia"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('room_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="button" wire:click="addToQuote" class="w-full rounded-lg bg-emerald-600 text-white font-medium py-2.5 hover:bg-emerald-700 transition">
                Dodaj do listy zakupów
            </button>
            <p class="text-center">
                <button type="button" wire:click="backToMain" class="text-orange-600 font-medium text-sm">Nowa kalkulacja</button>
            </p>
        </div>
    @endif

    @if($step === 'floor_type')
        <p class="mb-2"><button type="button" wire:click="backToMain" class="text-sm text-stone-500">← Podłogi</button></p>
        <h2 class="text-lg font-semibold mb-4">Podłogi – co robisz?</h2>
        <div class="grid gap-3">
            <button type="button" wire:click="selectFloorType('tiles')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Płytki (glazura / terakota)</span>
            </button>
            <button type="button" wire:click="selectFloorType('self_leveling')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Wylewka samopoziomująca</span>
            </button>
            <button type="button" wire:click="selectFloorType('concrete')"
                    class="block w-full text-left px-4 py-3 rounded-lg border border-stone-700 bg-stone-900 text-white hover:border-orange-400 hover:bg-stone-800 transition">
                <span class="font-medium">Wylewka betonowa (jastrych)</span>
            </button>
        </div>
    @endif

    @if($step === 'floor_form')
        <p class="mb-2"><button type="button" wire:click="backToFloorType" class="text-sm text-stone-500">← Podłogi</button></p>
        <h2 class="text-lg font-semibold mb-4">
            @if($selectedFloorType === 'tiles') Płytki – dane
            @elseif($selectedFloorType === 'self_leveling') Wylewka samopoziomująca – dane
            @else Wylewka betonowa – dane
            @endif
        </h2>
        <form wire:submit="calculateFloor" class="space-y-4">
            <div>
                <label for="floor_area" class="block text-sm font-medium text-stone-700 mb-1">Powierzchnia (m²)</label>
                <input type="number" id="floor_area" wire:model="floor_area" step="0.01" min="0.1" max="1000"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('floor_area') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            @if($selectedFloorType === 'tiles')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label for="tile_length_cm" class="block text-sm font-medium text-stone-700 mb-1">Długość płytki (cm)</label>
                        <input type="number" id="tile_length_cm" wire:model="tile_length_cm" step="0.1" min="1" max="300" class="w-full rounded-lg border border-stone-300 px-3 py-2">
                        @error('tile_length_cm') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="tile_width_cm" class="block text-sm font-medium text-stone-700 mb-1">Szerokość płytki (cm)</label>
                        <input type="number" id="tile_width_cm" wire:model="tile_width_cm" step="0.1" min="1" max="300" class="w-full rounded-lg border border-stone-300 px-3 py-2">
                        @error('tile_width_cm') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label for="joint_width_mm" class="block text-sm font-medium text-stone-700 mb-1">Szerokość fugi (mm)</label>
                    <input type="number" id="joint_width_mm" wire:model="joint_width_mm" step="0.5" min="0" max="20" class="w-full rounded-lg border border-stone-300 px-3 py-2">
                    @error('joint_width_mm') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            @else
                <div>
                    <label for="floor_thickness_mm" class="block text-sm font-medium text-stone-700 mb-1">Grubość (mm){{ $selectedFloorType === 'concrete' ? ' – typowo 40–60' : '' }}</label>
                    <input type="number" id="floor_thickness_mm" wire:model="floor_thickness_mm" step="0.5" min="1" max="150" class="w-full rounded-lg border border-stone-300 px-3 py-2">
                    @error('floor_thickness_mm') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                @if($selectedFloorType === 'concrete')
                    <p class="text-sm text-stone-500">Opcjonalnie: podaj wymiary pomieszczenia do taśmy dylatacyjnej (obwód).</p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label for="floor_length_m" class="block text-sm font-medium text-stone-700 mb-1">Długość (m)</label>
                            <input type="number" id="floor_length_m" wire:model="floor_length_m" step="0.1" min="0" class="w-full rounded-lg border border-stone-300 px-3 py-2" placeholder="opcjonalnie">
                        </div>
                        <div>
                            <label for="floor_width_m" class="block text-sm font-medium text-stone-700 mb-1">Szerokość (m)</label>
                            <input type="number" id="floor_width_m" wire:model="floor_width_m" step="0.1" min="0" class="w-full rounded-lg border border-stone-300 px-3 py-2" placeholder="opcjonalnie">
                        </div>
                    </div>
                @endif
            @endif
            <button type="submit" class="w-full rounded-lg bg-orange-600 text-white font-medium py-2.5 hover:bg-orange-700 transition">
                Oblicz materiały
            </button>
        </form>
    @endif

    @if($step === 'result_floor')
        <p class="mb-2"><button type="button" wire:click="backToFloorForm" class="text-sm text-stone-500">← Dane</button></p>
        <h2 class="text-lg font-semibold mb-4">Potrzebne materiały</h2>
        <ul class="space-y-2 border-t border-stone-200 pt-4">
            @foreach ($floorLabels as $key => $label)
                @if (isset($resultFloor[$key]) && $key !== 'meta')
                    @php $val = $resultFloor[$key]; @endphp
                    @if ($val > 0 || ($key === 'fuga_kg' && $val >= 0))
                        <li class="flex justify-between py-2 border-b border-stone-100">
                            <span>{{ $label }}</span>
                            <span class="font-medium">
                                @if (str_ends_with($key, '_l')) {{ number_format($val, 2, ',', ' ') }} L
                                @elseif (str_ends_with($key, '_kg')) {{ number_format($val, 2, ',', ' ') }} kg
                                @elseif (str_ends_with($key, '_mb')) {{ number_format($val, 2, ',', ' ') }} mb
                                @else {{ $val }} szt.
                                @endif
                            </span>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
        @if(!empty($resultFloor['meta']))
            <p class="mt-4 text-sm text-stone-500">
                Powierzchnia: {{ $resultFloor['meta']['area_m2'] }} m²
                @if(isset($resultFloor['meta']['thickness_mm'])) · Grubość: {{ $resultFloor['meta']['thickness_mm'] }} mm @endif
                @if(isset($resultFloor['meta']['grzebien_klasa'])) · {{ $resultFloor['meta']['grzebien_klasa'] }} @endif
            </p>
        @endif
        <div class="mt-6 pt-4 border-t border-stone-200 space-y-3">
            <div>
                <label for="room_name_floor" class="block text-sm font-medium text-stone-700 mb-1">Nazwa pomieszczenia / etapu</label>
                <input type="text" id="room_name_floor" wire:model="room_name" maxlength="200" placeholder="np. Łazienka na dole"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('room_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="button" wire:click="addToQuote" class="w-full rounded-lg bg-emerald-600 text-white font-medium py-2.5 hover:bg-emerald-700 transition">
                Dodaj do listy zakupów
            </button>
            <p class="text-center">
                <button type="button" wire:click="backToMain" class="text-orange-600 font-medium text-sm">Nowa kalkulacja</button>
            </p>
        </div>
    @endif

    @if($step === 'wall_finishing_form')
        <p class="mb-2"><button type="button" wire:click="backToMain" class="text-sm text-stone-500">← Malowanie – szpachlowanie</button></p>
        <h2 class="text-lg font-semibold mb-4">Wykończenie ścian – szpachlowanie i malowanie</h2>
        <form wire:submit="calculateWall" class="space-y-4">
            <div>
                <label for="wall_area" class="block text-sm font-medium text-stone-700 mb-1">Powierzchnia ścian / sufitów (m²)</label>
                <input type="number" id="wall_area" wire:model="wall_area" step="0.01" min="0.1" max="1000" class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('wall_area') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-2">Podłoże</label>
                <label class="inline-flex items-center mr-4"><input type="radio" wire:model="substrate_type" value="plaster" class="rounded border-stone-300"> <span class="ml-2">Tynk (chropowaty)</span></label>
                <label class="inline-flex items-center"><input type="radio" wire:model="substrate_type" value="drywall" class="rounded border-stone-300"> <span class="ml-2">Płyta GK</span></label>
            </div>
            @if($substrate_type === 'drywall')
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="full_surface_gk" class="rounded border-stone-300">
                    <span class="text-sm">Całopowierzchniowe szpachlowanie GK</span>
                </label>
            @endif
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-2">Jakość gładzi</label>
                <label class="inline-flex items-center mr-4"><input type="radio" wire:model="finish_quality" value="standard" class="rounded border-stone-300"> <span class="ml-2">Standard (pod malowanie)</span></label>
                <label class="inline-flex items-center"><input type="radio" wire:model="finish_quality" value="premium" class="rounded border-stone-300"> <span class="ml-2">Premium (pod światło/lustro)</span></label>
            </div>
            <div>
                <label for="paint_layers" class="block text-sm font-medium text-stone-700 mb-1">Liczba warstw farby</label>
                <input type="number" id="paint_layers" wire:model="paint_layers" min="1" max="5" class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('paint_layers') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-2">Typ farby</label>
                <label class="inline-flex items-center mr-4"><input type="radio" wire:model="paint_type" value="white" class="rounded border-stone-300"> <span class="ml-2">Biel</span></label>
                <label class="inline-flex items-center"><input type="radio" wire:model="paint_type" value="color" class="rounded border-stone-300"> <span class="ml-2">Kolor intensywny (+20%)</span></label>
            </div>
            <p class="text-sm text-stone-500">Opcjonalnie – do folii i taśmy:</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="wall_floor_area" class="block text-sm font-medium text-stone-700 mb-1">Pow. podłogi (m²)</label>
                    <input type="number" id="wall_floor_area" wire:model="wall_floor_area" step="0.01" min="0" class="w-full rounded-lg border border-stone-300 px-3 py-2" placeholder="est. ściany/3">
                </div>
                <div>
                    <label for="wall_perimeter" class="block text-sm font-medium text-stone-700 mb-1">Obwód (m)</label>
                    <input type="number" id="wall_perimeter" wire:model="wall_perimeter" step="0.1" min="0" class="w-full rounded-lg border border-stone-300 px-3 py-2" placeholder="est. z pow.">
                </div>
            </div>
            <button type="submit" class="w-full rounded-lg bg-orange-600 text-white font-medium py-2.5 hover:bg-orange-700 transition">Oblicz materiały</button>
        </form>
    @endif

    @if($step === 'result_wall')
        <p class="mb-2"><button type="button" wire:click="backToWallForm" class="text-sm text-stone-500">← Dane</button></p>
        <h2 class="text-lg font-semibold mb-4">Potrzebne materiały</h2>
        <ul class="space-y-2 border-t border-stone-200 pt-4">
            @foreach ($wallLabels as $key => $label)
                @if (isset($resultWall[$key]) && $key !== 'meta' && $resultWall[$key] > 0)
                    <li class="flex justify-between py-2 border-b border-stone-100">
                        <span>{{ $label }}</span>
                        <span class="font-medium">{{ $resultWall[$key] }} szt.</span>
                    </li>
                @endif
            @endforeach
        </ul>
        @if(!empty($resultWall['meta']['area_m2']))
            <p class="mt-4 text-sm text-stone-500">Powierzchnia: {{ $resultWall['meta']['area_m2'] }} m²</p>
        @endif
        <div class="mt-6 pt-4 border-t border-stone-200 space-y-3">
            <div>
                <label for="room_name_wall" class="block text-sm font-medium text-stone-700 mb-1">Nazwa pomieszczenia / etapu</label>
                <input type="text" id="room_name_wall" wire:model="room_name" maxlength="200" placeholder="np. Salon"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('room_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="button" wire:click="addToQuote" class="w-full rounded-lg bg-emerald-600 text-white font-medium py-2.5 hover:bg-emerald-700 transition">
                Dodaj do listy zakupów
            </button>
            <p class="text-center">
                <button type="button" wire:click="backToMain" class="text-orange-600 font-medium text-sm">Nowa kalkulacja</button>
            </p>
        </div>
    @endif

    @if($step === 'bathroom_form')
        <p class="mb-2"><button type="button" wire:click="backToMain" class="text-sm text-stone-500">← Łazienka</button></p>
        <h2 class="text-lg font-semibold mb-4">Hydroizolacja łazienki</h2>
        <form wire:submit="calculateBathroom" class="space-y-4">
            <div>
                <label for="bathroom_floor_m2" class="block text-sm font-medium text-stone-700 mb-1">Powierzchnia podłogi (m²)</label>
                <input type="number" id="bathroom_floor_m2" wire:model="bathroom_floor_m2" step="0.01" min="0.1" max="500"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('bathroom_floor_m2') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="bathroom_shower_wall_m2" class="block text-sm font-medium text-stone-700 mb-1">Strefa mokra ścian – pod prysznicem/wanną (m²)</label>
                <input type="number" id="bathroom_shower_wall_m2" wire:model="bathroom_shower_wall_m2" step="0.01" min="0" max="100"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('bathroom_shower_wall_m2') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="bathroom_corners_m" class="block text-sm font-medium text-stone-700 mb-1">Obwód narożników (m) – opcjonalnie</label>
                <input type="number" id="bathroom_corners_m" wire:model="bathroom_corners_m" step="0.1" min="0"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2" placeholder="oszacujemy, jeśli puste">
            </div>
            <div>
                <label for="bathroom_baterie" class="block text-sm font-medium text-stone-700 mb-1">Liczba punktów wodnych (baterii)</label>
                <input type="number" id="bathroom_baterie" wire:model="bathroom_baterie" min="0" max="20"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('bathroom_baterie') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="w-full rounded-lg bg-orange-600 text-white font-medium py-2.5 hover:bg-orange-700 transition">
                Oblicz materiały
            </button>
        </form>
    @endif

    @if($step === 'result_bathroom')
        <p class="mb-2"><button type="button" wire:click="backToBathroomForm" class="text-sm text-stone-500">← Dane</button></p>
        <h2 class="text-lg font-semibold mb-4">Potrzebne materiały – hydroizolacja</h2>
        @php $hydro = $resultBathroom['hydroizolacja'] ?? []; @endphp
        <ul class="space-y-2 border-t border-stone-200 pt-4">
            @foreach ($hydroizolacjaLabels as $key => $label)
                @if (isset($hydro[$key]) && ($hydro[$key] > 0 || in_array($key, ['folia_plynna_kg', 'tasma_mb'])))
                    <li class="flex justify-between py-2 border-b border-stone-100">
                        <span>{{ $label }}</span>
                        <span class="font-medium">
                            @if (str_ends_with($key, '_kg')) {{ number_format($hydro[$key], 2, ',', ' ') }} kg
                            @elseif (str_ends_with($key, '_mb')) {{ number_format($hydro[$key], 2, ',', ' ') }} mb
                            @else {{ $hydro[$key] }} szt.
                            @endif
                        </span>
                    </li>
                @endif
            @endforeach
        </ul>
        @if(!empty($resultBathroom['meta']))
            <p class="mt-4 text-sm text-stone-500">
                Podłoga: {{ $resultBathroom['meta']['floor_area_m2'] }} m² · Strefa mokra: {{ $resultBathroom['meta']['shower_wall_area_m2'] }} m²
            </p>
        @endif
        <div class="mt-6 pt-4 border-t border-stone-200 space-y-3">
            <div>
                <label for="room_name_bathroom" class="block text-sm font-medium text-stone-700 mb-1">Nazwa pomieszczenia / etapu</label>
                <input type="text" id="room_name_bathroom" wire:model="room_name" maxlength="200" placeholder="np. Łazienka na dole"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('room_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="button" wire:click="addToQuote" class="w-full rounded-lg bg-emerald-600 text-white font-medium py-2.5 hover:bg-emerald-700 transition">
                Dodaj do listy zakupów
            </button>
            <p class="text-center">
                <button type="button" wire:click="backToMain" class="text-orange-600 font-medium text-sm">Nowa kalkulacja</button>
            </p>
        </div>
    @endif

    @if($step === 'insulation_form')
        <p class="mb-2"><button type="button" wire:click="backToMain" class="text-sm text-stone-500">← Ocieplanie dachów</button></p>
        <h2 class="text-lg font-semibold mb-4">Ocieplenie poddasza – dane</h2>
        <form wire:submit="calculateInsulation" class="space-y-4">
            <div>
                <label for="insulation_roof_m2" class="block text-sm font-medium text-stone-700 mb-1">Powierzchnia skosów do ocieplenia (m²)</label>
                <input type="number" id="insulation_roof_m2" wire:model="insulation_roof_m2" step="0.01" min="0.1" max="1000"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('insulation_roof_m2') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="insulation_layer1_cm" class="block text-sm font-medium text-stone-700 mb-1">Warstwa 1 między krokwie (cm)</label>
                    <input type="number" id="insulation_layer1_cm" wire:model="insulation_layer1_cm" step="0.5" min="0" max="30"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2">
                    @error('insulation_layer1_cm') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="insulation_layer2_cm" class="block text-sm font-medium text-stone-700 mb-1">Warstwa 2 pod krokwie (cm)</label>
                    <input type="number" id="insulation_layer2_cm" wire:model="insulation_layer2_cm" step="0.5" min="0" max="30"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2">
                    @error('insulation_layer2_cm') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label for="insulation_rafter_cm" class="block text-sm font-medium text-stone-700 mb-1">Rozstaw krokwi (cm)</label>
                <input type="number" id="insulation_rafter_cm" wire:model="insulation_rafter_cm" step="1" min="40" max="120"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('insulation_rafter_cm') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="w-full rounded-lg bg-orange-600 text-white font-medium py-2.5 hover:bg-orange-700 transition">
                Oblicz materiały
            </button>
        </form>
    @endif

    @if($step === 'result_insulation')
        <p class="mb-2"><button type="button" wire:click="backToInsulationForm" class="text-sm text-stone-500">← Dane</button></p>
        <h2 class="text-lg font-semibold mb-4">Potrzebne materiały – ocieplenie poddasza</h2>
        @php $ociepl = $resultInsulation['ocieplenie'] ?? []; @endphp
        <ul class="space-y-2 border-t border-stone-200 pt-4">
            @foreach ($ocieplenieLabels as $key => $label)
                @if (isset($ociepl[$key]) && $ociepl[$key] > 0)
                    <li class="flex justify-between py-2 border-b border-stone-100">
                        <span>{{ $label }}</span>
                        <span class="font-medium">
                            @if (str_ends_with($key, '_m2')) {{ number_format($ociepl[$key], 2, ',', ' ') }} m²
                            @elseif (str_ends_with($key, '_mb')) {{ number_format($ociepl[$key], 2, ',', ' ') }} mb
                            @else {{ $ociepl[$key] }} szt.
                            @endif
                        </span>
                    </li>
                @endif
            @endforeach
        </ul>
        @if(!empty($resultInsulation['meta']))
            <p class="mt-4 text-sm text-stone-500">
                Powierzchnia skosów: {{ $resultInsulation['meta']['roof_area_m2'] }} m² · Warstwa 1: {{ $resultInsulation['meta']['layer1_thickness_cm'] }} cm · Warstwa 2: {{ $resultInsulation['meta']['layer2_thickness_cm'] }} cm
            </p>
        @endif
        <div class="mt-6 pt-4 border-t border-stone-200 space-y-3">
            <div>
                <label for="room_name_insulation" class="block text-sm font-medium text-stone-700 mb-1">Nazwa pomieszczenia / etapu</label>
                <input type="text" id="room_name_insulation" wire:model="room_name" maxlength="200" placeholder="np. Poddasze"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2">
                @error('room_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="button" wire:click="addToQuote" class="w-full rounded-lg bg-emerald-600 text-white font-medium py-2.5 hover:bg-emerald-700 transition">
                Dodaj do listy zakupów
            </button>
            <p class="text-center">
                <button type="button" wire:click="backToMain" class="text-orange-600 font-medium text-sm">Nowa kalkulacja</button>
            </p>
        </div>
    @endif

    {{-- Modal: Dodano do listy – inline style wymusza widoczność (nie nadpisuje dark mode) --}}
    @if($showAddToQuoteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.82);" role="dialog" aria-modal="true">
            <div class="rounded-xl shadow-2xl max-w-sm w-full p-6 space-y-4 min-w-[280px]"
                 style="background-color: #292524; color: #ffffff; border: 2px solid #78716c;">
                <p class="font-medium" style="color: #ffffff;">Dodano! Co chcesz zrobić dalej?</p>
                <div class="grid gap-2">
                    <button type="button" wire:click="closeAddToQuoteModalAndAddAnother"
                            class="w-full rounded-lg py-2.5 font-medium transition"
                            style="background-color: #44403c; color: #ffffff; border: 2px solid #78716c;">
                        Dodaj kolejne pomieszczenie
                    </button>
                    <button type="button" wire:click="goToSummary"
                            class="w-full rounded-lg font-medium py-2.5 transition"
                            style="background-color: #ea580c; color: #ffffff;">
                        Podsumuj i Drukuj
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($step === 'coming_soon')
        <p class="text-stone-600 mb-4">Ta opcja będzie dostępna wkrótce.</p>
        <button type="button" wire:click="backToMain" class="text-orange-600 font-medium text-sm">← Wróć</button>
    @endif
</div>
